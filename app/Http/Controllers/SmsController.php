<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\SmsServer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmMail;

class SmsController extends BaseController
{
    public static function sms_test($username,$key){
        $AT = SmsServer::insta($username , $key);
        $sms      = $AT->sms();
        $result   = $sms->send([
            'to'      => '+256754024461',
            'message' => 'Hello World!'
        ]);

        print_r($result);
    }

    public static function verify_phone($phone,$code,$userid){
        if(substr($phone,0,1)=='0'){
            //0754024461
            $tel = '+256'.substr($phone,1,9);
        }elseif (substr($phone,0,1)=='+') {
            # code...
            $tel = $phone;
        }

        $AT = SmsServer::insta(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));
        $message = 'Your Appnomu Account Verification Code Is:'.$code;
        $sms      = $AT->sms();
        $result   = $sms->send([
            'to'      => $tel,
            'message' => $message
        ]);

        if($result['status']=='success'){
            $ret = SmsController::saveSms($tel,'Telephone Verification',$message,$result['status'],$userid);
        }else {
            $ret = 0;
        }

        return $ret;
        
    }

    public static function saveSms($to,$title,$message,$status,$userid){
        $sms = 'SMS-'.rand(1111,9999);
        $db = DB::table('smssent')->insert([
            'Sms_Id'=>$sms,
            'user_Id'=>$userid,
            'To'=>$to,
            'Title'=>$title,
            'Message'=>$message,
            'Status'=>$status,
            'created_at'=>date('Y-m-d H:i:s', time())

        ]);

        if($db){
            return 1;
        }else{
            return 0;
        }
    }

    public static function verify_alliases_phone($phone,$code,$userid,$refferer,$all_name){
        
        if(substr($phone,0,1)=='0'){
            $tel = '+256'.substr($phone,1,9);
        }elseif (substr($phone,0,1)=='+') {
            $tel = $phone;
        }

        $AT = SmsServer::insta(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));
        $message = 'Hello '. $all_name . ' '.$refferer . ' Has Listed YOu as His/Her Alliance on Appnomu For a loan verification, to Approve please send him/her this verification code: '.$code;
        $sms      = $AT->sms();
        $result   = $sms->send([
            'to'      => $tel,
            'message' => $message
        ]);

        if($result['status']=='success'){
            $ret = SmsController::saveSms($tel,'Alliace Telephone Verification',$message,$result['status'],$userid);
        }else {
            $ret = 0;
        }

        return $ret;
        
    }

    public function sendBulks(Request $request){
        $validated = $request->validate([
            'title'=>'required',
            'list'=>'required'
        ]);

        $telephones = [];

        if (isset($request['telephones'])) {
            $telephones = explode(",",$request['telephones']);
        }
        
        $i = 0;

        //For New Contact Lists 
        if ($request['list']=='New') {
            $list = 'Lst-'.rand(11111,99999);
            $dbContactList = DB::table('contactlists')->insert([
                'user_id'=>session('user_id'),
                'List_id'=>$list,
                'List_Name'=> 'Unknown',
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);
        }else {
            # code...
            $getList = DB::table('contacts')
                ->where('List_id','=',$request['list'])
                ->get();

            $telep = json_decode($getList,true);

            foreach ($telep as $key1) {
                # code...
                array_push($telephones,$key1['telephone']);
            }
        }
        
        foreach ($telephones as $key ) {
            if(substr($key,0,1)=='0'){
                $tel = '+256'.substr($key,1,9);
            }elseif (substr($key,0,1)=='+') {
                $tel = $key;
            }else {
                # code...
                $tel ='+256754024461';
            }

            $AT = SmsServer::insta(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));

            $sms      = $AT->sms();
            $result   = $sms->send([
                'to'      => $tel,
                'message' => $request['message']
            ]);

            if ($request['list']=='New') {
                # code...
                $dbConta = DB::table('contacts')->insert([
                    'user_id'=>session('user_id'),
                    'List_id'=>$list,
                    'name'=>'Unknown',
                    'telephone'=>$tel,
                    'email'=>'Unknown',
                    'Designation'=>'Unknown',
                    'created_at'=>date('Y-m-d H:i:s',time())
                    
                ]);
            }

            if($result['status']=='success'){
                $ret = SmsController::saveSms($tel,'Bulk SMS Feature',$request['message'],$result['status'],session('user_id'));
                $i++;
            }
        }

        echo $i .' Messages Sent';
        if ($i>0) {
            # code...
            return redirect()->back()->with('Success','Successfully Sent '.$i.' Messages');
        }
    }

    public static function approvedLoan($phone,$status,$userid,$loan_key,$all_name){
        
        if(substr($phone,0,1)=='0'){
            $tel = '+256'.substr($phone,1,9);
        }elseif (substr($phone,0,1)=='+') {
            $tel = $phone;
        }

        $AT = SmsServer::insta(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));
        $message = 'Hello '. $all_name . ' Your Appnomu Loan request '.$loan_key.' Has been '.$status;
        $sms      = $AT->sms();
        $result   = $sms->send([
            'to'      => $tel,
            'message' => $message
        ]);

        if($result['status']=='success'){
            $ret = SmsController::saveSms($tel,'Loan Management',$message,$result['status'],$userid);
        }else {
            $ret = 0;
        }

        return $ret;
        
    }



}
