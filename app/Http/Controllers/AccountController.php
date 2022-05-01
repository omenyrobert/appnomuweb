<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    //change loan limit
    public function updateLoanLimit(Request $request,$id){
        try {
            $logged = User::find(Auth::id());
            if($logged->role == 'Admin'){
                $user = User::find($id);

                if($user){
                    $account = $user->account;
                    $account->loan_limit = (int)$request->loan_limit;
                    $account->save();
                }
                return response('user account does not exist',401);
            }
            return response('unathorized',401);
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    
}
