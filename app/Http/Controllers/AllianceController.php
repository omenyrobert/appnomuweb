<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alliance;
use App\Models\User;
use Carbon\Carbon;

class AllianceController extends Controller
{
    

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'relationship'=>'required',
                'nin'=>'required',
                'card_no'=>'required',
                'telephone'=>'required',
                'name'=>'required'
            ]);
    
            if($validated){
                $users = AuthenticationController::getUserById(session('user_id'));
                $user = User::find($users[0]['id']);
    
                if($user->telephone == $request->telephone){
                    return redirect()->back()->withErrors(['Errors'=>'You can not use your own phone number']);
                }
                if($user->NIN == $request->nin){
                    return redirect()->back()->withErrors(['Errors'=>'You can not use your own NIN']);
                }
                $user_id = rand(111111,999999);
                $sms_code = rand(111111,999999);
                $alliance = new Alliance();
                $alliance->user()->associate($user);
                $alliance->refferer = $user->Uuser_id;
                $alliance->relationship = $request->relationship;
                $alliance->NIN =  $request->nin;
                $alliance->Card_No =$request->card_no;
                $alliance->name = $request->name;
                $alliance->sms_token = $sms_code;
                $alliance->Phone_Number = $request->telephone;
                $alliance->Uuser_id = $user_id;
                $alliance->save();
                if ($alliance) {
                    # code...
                    $ret = SmsController::verify_alliases_phone($alliance->telephone,$sms_code,session('user_id'),$user->name,$alliance->name);
                    return redirect()->back()->with('Success','Alliance Saved Successfully');
                }else {
                    # code...
                    return redirect()->back()->withErrors(['Errors'=>'Alliance Saving failed']);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }       
           
        
    }

    public function confirmAlliance(Request $request){
        try {
            $users = AuthenticationController::getUserById(session('user_id'));
            $user = User::find($users[0]['id']);
            $alliance = $user->alliances()->where('sms_token',$request->token)->first();
            if($alliance)  {
               $alliance->sms_verified_at = Carbon::now();
               return redirect()->back()->with('Success','Alliance Successfully Confirmed');
            }
            return redirect()->back()->withErrors(['Errors'=>'Unknown alliance or Wrong Token']);
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors(['Error'=>'Alliance Not Successfully Confirmed']);
        }
       
    }

    public function getAlliances($id){
        try {
            $user = User::find($id);
            return $user->alliances;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }
    
   
}
