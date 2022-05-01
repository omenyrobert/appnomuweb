<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    //get all businesses
    public function index(){
        $businesses = Business::orderBy('created_at','DESC')->get();
        return view('bussiness.index',['businesses'=>$businesses])->with('title','Bussinesses');

    }

    //create a business
    public function create(){
        return view('bussiness.create')->with('title','Create Bussiness');
    }

    public function store(Request $request,$id){
        try {
            
            $user = User::findOrFail($id);
            $district = District::find($request->district);
            if($user && $district){
                $business = new Business();
                $business->user()->associate($user);
                $business->district()->associate($district);
                $business->name = $request->name;
                $business->location = $request->location;
                $business->premises_photo = $request->premises;
                $business->license_photo = $request->license;
                $business->business_photo = $request->business_photo;
                $business->save();
            }

            return redirect()->back()->withErrors('please try again');

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
