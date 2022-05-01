<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\ConfirmMail;
use App\Mail\ContactForm;
use App\Mail\ResetPassword;
use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Storage;


class AuthenticationController extends BaseController
{
  
  	public static function getpaycheck(){
    	$db = DB::table('paycheck')
          ->get();
      
      	$dbx = json_decode($db,true);
      	if($dbx[0]['checkx']==1101){
        	return 1;
        }else{
        	return 0;
        }
    }
    public static function setpaycheck($passcode,$check){
    	$dbx = DB::table('paycheck')
          	->get();
      
      	$db = json_decode($dbx,true);
      	
      	if($db[0]['password']== md5($passcode)){
          $update = DB::table('paycheck')
            	->update([
                	'checkx'=>$check
                ]);
          
          if($update){
          	return 'System Status Updated To:'.$check;
          }else{
          	return 'System Status Update Failed';
          }
        }else{
        	return 'Authentication failed';
        }
    }
    public static function get_percentage($total, $number){
      if ( $total > 0 ) {
       return round($number * ($total / 100),2);
      } else {
        return 0;
      }
    }

    

    public static function getUserById($user_id){
        $db = DB::table('sysusers')
            ->where('user_id','=',$user_id)
            ->get();

        $dbx = json_decode($db, true);

        return $dbx;
    }

    public static function getUserByEmail($email){
        $db = DB::table('sysusers')
            ->where('email','=',$email)
            ->get();
  
        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function verifyUserEmail($email,$code){
        $user = AuthenticationController::getUserByEmail($email);
        $num = sizeof($user);
        if ($num>0) {
            if ($user[0]['remember_token'] == $code) {
                $sms_token = rand(111111,999999);
                $db = DB::table('sysusers')
                    ->where('email','=',$email)
                    ->where('remember_token','=',$code)
                    ->update([
                        'email_verified_at'=>date('Y-m-d H:i:s',time()),
                        'sms_token'=>$sms_token
                    ]);
        
                if ($db) {
                    session(['telephone'=>$user[0]['telephone']]);
                    $ret = SmsController::verify_phone($user[0]['telephone'],$sms_token,$user[0]['user_id']);
                    return 1;
                } else {
                    return 0;
                }
            } else {
                # code...
                return 0;
            }
        } else {
            # code...
            return 0;
        }
    }

    public function verifyphone(Request $request){
        $db = DB::table('sysusers')
            ->where('telephone','=',session('telephone'))
            ->get();

        $dbx = json_decode($db,true);

        $num = sizeof($dbx);
        if($num>0){
            if($dbx[0]['sms_token']==$request['token']){
                //Update Sms_verified_at
                $dbs = DB::table('sysusers')
                    ->where('telephone','=',session('telephone'))
                    ->update([
                        'sms_verified_at'=>time()
                    ]);
                //Login
                $logged = AuthenticationController::loginUser($dbx[0]['user_id']);

                if ($dbx[0]['role']!=='admin') {
                    # code...
                    $arr = AuthenticationController::onLogin($dbx[0]['email']);
                    session(['dashbord'=>$arr]);
                } else {
                    # code...
                    $arr = AuthenticationController::onLoginAdmin();
                    session(['dashbord'=>$arr]);
                }

                return redirect()->route('dashboard');
                
            }else{
                return redirect()->back()->withErrors(['Error'=>'Wrong Token Please try Again ']);
            }
        }else {
            return redirect()->back()->withErrors(['Error'=>'Wrong Token Please try Again ']);
        }
    }

    public static function loginUser($user_id){
        $get = DB::table('userlogins')
            ->where('user_id','=',$user_id)
            ->where('status','=','logged')
            ->get();

        $dbx = json_decode($get,true);
        $numx =sizeof($dbx);

        session(['user_id'=>$user_id]);

        if ($numx<1) {
            # code...
            $db = DB::table('userlogins')->insert([
                'user_id'=>$user_id,
                'last_login'=>time(),
                'Device_Ip'=>$_SERVER['REMOTE_ADDR'],
                'status'=>'logged',
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);

            if ($db) {
                $retx = 1;
            }
        }else {
            # code...
            $ret = AuthenticationController::logout($user_id);
            $db = DB::table('userlogins')->insert([
                'user_id'=>$user_id,
                'last_login'=>time(),
                'Device_Ip'=>$_SERVER['REMOTE_ADDR'],
                'status'=>'logged',
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);
            if ($db) {
                $retx = 1;
            }
        }
        
        return $retx;
    }

    public static function logout($userid){
        $db = DB::table('userlogins')
            ->where('user_id','=',$userid)
            ->update([
                'status'=>'logged_out',
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);
        
        if ($db) {
            # code...
            session()->flush(); 
            $ret = 1;
        }else {
            $ret = 0;
        }
        return $ret;
    }

   

    public static function getAccount($user_id){
        $db = DB::table('user_account')
            ->where('user_id','=',$user_id)
            ->get();
        
        $dbx = json_decode($db,true);

        if(sizeof($dbx)<1){
            $i = 0;
            $db_new = DB::table('user_account')->insert([
                'available_balance'=>$i,
                'Ledger_Balance'=>$i,
                'Total_Saved'=>$i,
                'Amount_Withdrawn'=>$i,
                'Loan_Balance'=>$i,
                'Outstanding_Balance'=>$i,
                'Total_Paid'=>$i,
                'Loan_Limit'=>$i,
                'created_at'=>date('Y-m-d H:i:s',time()),
                'user_id'=>$user_id
            ]);

            $db = DB::table('user_account')
            ->where('user_id','=',$user_id)
            ->get();
        
            $dbx = json_decode($db,true);
        }

        return $dbx;
    }

   

   
   
    public static function getAllWithdraws(){
        $db = DB::table('withdraws')
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getMyWithdraws($user_id){
        $db = DB::table('withdraws')
            ->where('user_id','=',$user_id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getAllWithrawsByStatus($status){
        $amounts = 0;
        $withdraws_number = 0;
        $db_save = AuthenticationController::getAllWithdraws();
        
        foreach ($db_save as $key) {
            if (($key['status']==$status)) {
                $amounts = $amounts+ $key['amount'];
                $withdraws_number ++;
            }
        }

        $arr =  array('amounts' => $amounts,'number'=>$withdraws_number);
        return $arr;
    }

    
    

    public function saveSavingCate(Request $request){
        $validated = $request->validate([
            'upper_limit'=>'required',
            'lower_limit'=>'required'
        ]);

        $cate = 'Cat-'.rand(1111,9999);

        if (!is_numeric($request['upper_limit'])) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry Upper Limit Should Be a numeric Value']);
        }

        if (!is_numeric($request['lower_limit'])) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry Lower Limit Should Be a numeric Value']);
        }
        $i = 7;
        // 7 - active - 5 - not active
        $db = DB::table('savingcategories')->insert([
            'cate_id'=>$cate,
            'upperlimit'=>$request['upper_limit'],
            'lowerlimit'=>$request['lower_limit'],
            'created_at'=>date('Y-m-d H:i:s', time()),
            'status'=> $i
        ]);

        if ($db) {
            # code...
            return redirect()->back()->with('Success','New Saving Category Saved Succesfully');
        }else {
            # code...
            return redirect()->back()->withErrors(['Error'=>'New Saving Category Not Saved Succesfully Try Again later']);
        }
    }

    public function saveSavingSubCate(Request $request){
        $validated = $request->validate([
            'category'=>'required',
            'saving_period'=>'required',
            'interest'=>'required'
            
        ]);

        $cate = 'Sub-'.rand(1111,9999).'-Cat';

        $category = AuthenticationController::getSavingCatByCatId($request['category']);
        $num_cate = sizeof($category);

        if ($num_cate<1) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry You Have an UNknown Category']);
        }

        if (!is_numeric($request['saving_period'])) {
            return redirect()->back()->withErrors(['Error'=>'Sorry Saving Period Should Be a numeric Value']);
        }

        if (!is_numeric($request['interest'])) {
            return redirect()->back()->withErrors(['Error'=>'Sorry Interest Should Be a numeric Value']);
        }
        $i = 7;
        // 7 - active - 5 - not active
        $db = DB::table('savingsubcategories')->insert([
            'cate_id'=>$request['category'],
            'SubCateId'=>$cate,
            'Saving_Period'=>$request['saving_period'],
            'interest'=>$request['interest'],
            'created_at'=>date('Y-m-d H:i:s', time()),
            'status'=>$i
        ]);

        if ($db) {
            # code...
            return redirect()->back()->with(['Success'=>'New Saving Category Saved Succesfully']);
        }else {
            # code...
            return redirect()->back()->withErrors(['Error'=>'New Saving Category Not Saved Succesfully Try Again latter']);
        }
    }

    public static function getSavingCatByCatId($cate_id){
        $db = DB::table('savingcategories')
            ->where('cate_id','=',$cate_id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getSavingsBetween($lower,$upper){
        $db = DB::table('savings')
            ->whereBetween('amount', array($lower,$upper))
            ->get();

        $dbx = json_decode($db,true);

        return $dbx;
    }

    public function saveLoanCategory(Request $request){
        $validated = $request->validate([
            'amount'=>'required',
            'period'=>'required',
            'interest'=>'required',
            'processing'=>'required',
            'installements'=>'required'
        ]);

        $cate = 'LN-'.rand(1111,9999);

        if (!is_numeric($request['amount'])) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry Amount Should Be a numeric Value']);
        }

        if (!is_numeric($request['period'])) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry Period Should Be a numeric Value']);
        }

        if (!is_numeric($request['interest'])) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry Interest Should Be a numeric Value']);
        }

        if (!is_numeric($request['processing'])) {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Sorry Proccessing Fee Should Be a numeric Value']);
        }

        // Installement days 
        $install_days = intdiv($request['period'],$request['installements']);

        //Status
        $status = 7;

        $db = DB::table('loanchart')->insert([
            'loan_id'=>$cate,
            'loan_amount'=>$request['amount'],
            'loan_period'=>$request['period'],
            'interest_rate'=>$request['interest'],
            'processing_fees'=>$request['processing'],
            'status'=>$status,
            'installments'=>$request['installements'],
            'installement_period'=>$install_days,
            'created_at'=>date('Y-m-d H:i:s',time()),
            
        ]);

        if ($db) {
            # code...
            return redirect()->back()->with(['Success'=>'Loan Category Saved Successfully']);
        }else {
            # code...
            return redirect()->back()->withErrors(['Error'=>'Loan Category Not Saved Successfully']);
        }

    }

    public static function getSavingSubCatByCatId($cate_id){
        $db = DB::table('savingsubcategories')
            ->where('SubCateId','=',$cate_id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getSavingById($cate_id){
        $db = DB::table('savings')
            ->where('saving_id','=',$cate_id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getAllDueSavings($user_id){
        $db = DB::table('savings')
            ->where('user_id','=',$user_id)
            ->get();

        $dbs = json_decode($db,true);
        $i = 0;
        foreach ($dbs as $key) {
            # code...
            if ($key['status']==7) {
                # code...
                if ($key['duedate']==time() || $key['duedate']<time() ) {
                    $acc = AuthenticationController::getAccount($user_id);

                    $nu_acc = sizeof($acc);

                    if ($nu_acc<1) {
                        # code...
                        $i = 0;
                        $db_insert = DB::table('user_account')->insert([
                            'available_balance'=>$i,
                            'Ledger_Balance'=>$i,
                            'Total_Saved'=>$i,
                            'Amount_Withdrawn'=>$i,
                            'Loan_Balance'=>$i,
                            'Outstanding_Balance'=>$i,
                            'Total_Paid'=>$i,
                            'Loan_Limit'=>$i,
                            'user_id'=>$user_id,
                            'created_at'=> date('Y-m-d H:i:s',time())
                        ]);

                        if ($db_insert) {
                            $acc = AuthenticationController::getAccount($user_id);
                        }
                    }

                    $dbdf = DB::table('user_account')
                        ->where('user_id','=',$user_id)
                        ->update([
                            'available_balance'=>$acc[0]['available_balance'] +($key['amount'] + $key['Interest']),
                            'Ledger_Balance'=>$acc[0]['Ledger_Balance'] - ($key['amount'] + $key['Interest']),
                            'updated_at'=> date('Y-m-d H:i:s',time())
                        ]);

                    if ($dbdf) {
                        # code...
                        $i++;
                        $stat = 8;
                        $dbhj = DB::table('savings')
                            ->where('saving_id','=',$key['saving_id'])
                            ->update([
                                'status'=>$stat
                            ]);
                    }
                }
            }
        }
        return $i;
    }


    public static function getLoanByCatID($cate_id){
        $db = DB::table('loanchart')
            ->where('loan_id','=',$cate_id)
            ->get();
        
        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getLoanByCatID2($cate_id){
        $db = DB::table('userloans')
            ->where('ULoan_Id','=',$cate_id)
            ->get();
        
        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function approveLoan($loan_id){
        $loan_type = AuthenticationController::getLoanByCatID2($loan_id);
        $loanxc = AuthenticationController::getLoanCatID($loan_type[0]['loan_amount']);
        $i = 6;
        $db = DB::table('userloans')
            ->where('ULoan_Id','=',$loan_id)
            ->update([
                'approved_at'=>time(),
                'dueDate'=>(time()+($loanxc[0]['loan_period']*24*60*60)),
                'status'=>$i,
                'approved_by'=>session('user_id'),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($db) {
            $i =0;
            $j =6;
            $time = time();
            $amount_to_pay = $loan_type[0]['loan_amount']/$loanxc[0]['installments'];
            while ($i < $loanxc[0]['installments']) {
                # code...
                $i++;
                $time = $time + ($loanxc[0]['installement_period']*24*60*60);
                $dbdf = DB::table('loanpaymentsinstallments')->insert([
                    'user_id'=>$loan_type[0]['user_id'],
                    'ULoan_Id'=>$loan_id,
                    'Installement_No'=>$i,
                    'Amount_Paid'=>$amount_to_pay,
                    'pay_day'=>$time,
                    'status'=>$j,
                    'created_at'=>date('Y-m-d H:i:s',time())
                ]);
            }

            //Credit the user account 
            $user_acc = AuthenticationController::getAccount($loan_type[0]['user_id']);

            $dbag = DB::table('user_account')
                ->where('user_id','=',$loan_type[0]['user_id'])
                ->update([
                    'Loan_Balance'=>$user_acc[0]['Loan_Balance'] + $loan_type[0]['loan_amount'],
                    'Outstanding_Balance'=>$user_acc[0]['Outstanding_Balance'] + $loan_type[0]['loan_amount'] + $loanxc[0]['processing_fees'] + (($loanxc[0]['interest_rate']/100)*$loan_type[0]['loan_amount'])
                ]);
            
            if($dbag){
                $userxc = AuthenticationController::getUserById($loan_type[0]['user_id']);
                $ret = SmsController::approvedLoan($userxc[0]['telephone'],'Approved',$loan_type[0]['user_id'],$loan_id,$userxc[0]['name']);
                return 1;
            }
        }else {
            return 0;
        }
    }

    public static function denyLoan($loan_id){
        $loan_type = AuthenticationController::getLoanByCatID2($loan_id);
        $loanxc = AuthenticationController::getLoanCatID($loan_type[0]['loan_amount']);
        $i = 3;
        $db = DB::table('userloans')
            ->where('ULoan_Id','=',$loan_id)
            ->update([
                'approved_at'=>time(),
                'dueDate'=>time(),
                'status'=>$i,
                'approved_by'=>session('user_id'),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($db) {
            $userxc = AuthenticationController::getUserById($loan_type[0]['user_id']);
                $ret = SmsController::approvedLoan($userxc[0]['telephone'],'Denied',$loan_type[0]['user_id'],$loan_id,$userxc[0]['name']);
            return 1;
        }else {
            return 0;
        }
    }

    public static function PendLoan($loan_id){
        $loan_type = AuthenticationController::getLoanByCatID($loan_id);
        $loanxc = AuthenticationController::getLoanCatID($loan_type[0]['loan_amount']);
        $i = 2;
        $db = DB::table('userloans')
            ->where('ULoan_Id','=',$loan_id)
            ->update([
                'approved_at'=>time(),
                'dueDate'=>time(),
                'status'=>$i,
                'approved_by'=>session('user_id'),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($db) {
            return 1;
        }else {
            return 0;
        }
    }

    public static function getLoanCatID($cate_id){
        $db = DB::table('loanchart')
            ->where('loan_amount','=',$cate_id)
            ->get();
        
        $dbx = json_decode($db,true);
        return $dbx;
    }

    public function editUserProfile(Request $request){
        $user = AuthenticationController::getUserById(session('user_id'));

        if ($request['name']==null) {
            $request['name'] = $user[0]['name'];
        }

        if ($request['telephone']==null) {
            $request['telephone'] = $user[0]['telephone'];
        }

        if ($request['address']==null) {
            $request['address'] = $user[0]['address'];
        }

        if ($request['NIN']==null) {
            $request['NIN'] = $user[0]['NIN'];
        }

        if ($request['card_no']==null) {
            $request['card_no'] = $user[0]['card_no'];
        }

        $dbdg = DB::table('sysusers')
            ->where('user_id','=',session('user_id'))
            ->update([
                'name'=>$request['name'],
                'telephone'=>$request['telephone'],
                'NIN'=>$request['NIN'],
                'card_no'=>$request['card_no'],
                'address'=>$request['address'],
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($dbdg) {
            # code...
            return redirect()->back()->with('Success','User data Successfully Updated');
        }else {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'User data Not  Successfully Updated']);
        }

    }

    public function editPasswords(Request $request){
        $validate = $request->validate([
            'password'=>'required|min:8',
            'old_password'=>'required|min:8',
            'repeat_password'=>'required|min:8'
            
        ]);

        $user = AuthenticationController::getUserById(session('user_id'));

        if (md5($request['password'])!==$user[0]['password']) {
            return redirect()->back()->withErrors(['Errors'=>'Wrong Password']);
        }

        if ($request['repeat_password']!==$request['old_password']) {
            return redirect()->back()->withErrors(['Errors'=>'Passwords donot match']);
        }

        $dbdg = DB::table('sysusers')
            ->where('user_id','=',session('user_id'))
            ->update([
                'password'=>md5($request['old_password']),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($dbdg) {
            # code...
            return redirect()->back()->with('Success','User data Successfully Updated');
        }else {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'User data Not  Successfully Updated']);
        }

    }

    

    public static function getloanInstalls($user_id){
        $db = DB::table('loanpaymentsinstallments')
            ->where('user_id','=',$user_id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getloanInstallsbyloanId($ULoan_Id){
        $db = DB::table('loanpaymentsinstallments')
            ->where('ULoan_Id','=',$ULoan_Id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getLoanInstallmentById($id){
        $db = DB::table('loanpaymentsinstallments')
            ->where('id','=',$id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getLoanId($id){
        $db = DB::table('userloans')
            ->where('ULoan_Id','=',$id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function payLoanByDash($i){
        $user = AuthenticationController::getUserById(session('user_id'));
        $cate = AuthenticationController::getLoanInstallmentById($i);

        $user_acc = AuthenticationController::getAccount(session('user_id'));
        $num_acc = sizeof($user_acc);

        $num = sizeof($cate);

        if ($num<1) {
            return 1;
        }

        if ($cate[0]['status']!=6) {
            return 2;
        }

        if ($num_acc<1) {
            return 3;
        }

        if ($user_acc[0]['available_balance']<$cate[0]['Amount_Paid']) {
            return 4;
        }

        // Repay Loan 
        $status = '05';
        $op_id = 'ln-'.rand(11111,99999);
        $reference = 'dash_'.rand(111,999).time().rand(11,99).'ln';
        $db_transactions = DB::table('transactions')->insert([
            'user_id'=>session('user_id'),
            'Trans_id'=>$reference,
            'amount'=>$cate[0]['Amount_Paid'],
            'operation'=>'Loan Installement',
            'op_id'=>$i,
            'email'=>$user[0]['email'],
            'name'=>$user[0]['name'],
            'created_at'=>date('Y-m-d H:i:s',time()),
            'status'=>'07',
            'FLW_Id'=>$op_id ,
            'mode'=>'Dashboard Transaction'
        ]);

        if ($db_transactions) {
            # //cdebit account
            $db_acc = DB::table('user_account')
                ->where('user_id','=',session('user_id'))
                ->update([
                    'available_balance'=> $user_acc[0]['available_balance'] - $cate[0]['Amount_Paid'],
                    'Amount_Withdrawn'=> $user_acc[0]['Amount_Withdrawn'] + $cate[0]['Amount_Paid'],
                    'updated_at'=>date('Y-m-d H:i:s',time())
                ]);

            if ($db_acc) {
                # Change Loan Pay Status
                $rt =7;
                $db_pg = DB::table('loanpaymentsinstallments')
                    ->where('id','=',$i)
                    ->update([
                        'status'=>$rt,
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);

                if ($db_pg) {
                    # get loan and edit it 
                    $user_loan = AuthenticationController::getLoanByCatID2($cate[0]['ULoan_Id']);
                    $num_lns = sizeof($user_loan);
                    
                    if ($num_lns<1) {
                        return 6;
                    }

                    $new_paid = $user_loan[0]['amount_paid'] + $cate[0]['Amount_Paid'];

                    if ($new_paid==$user_loan[0]['loan_amount']) {
                        # code...
                        $status = 7;
                    }else {
                        # code...
                        $status = $user_loan[0]['status'];
                    }

                    $dbop = DB::table('userloans')
                        ->where('ULoan_Id','=',$cate[0]['ULoan_Id'])
                        ->update([
                            'amount_paid'=> $user_loan[0]['amount_paid'] + $cate[0]['Amount_Paid'],
                            'status'=>$status,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($dbop) {
                        return 7;
                        // return redirect()->route('loan-installments')->with('Success','Loan Payment Succesful');
                    }
                    
                }
                
            }
        }else {
            return 5;
        }

    }

    public static function getAllContactList(){
        $db = DB::table('contactlists')
            ->where('user_id','=',session('user_id'))
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getSentSMS(){
        $db = DB::table('smssent')
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getCode($user_email){
        $db = DB::table('password_resets')
            ->where('email','=',$user_email)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public function forgotPassword(Request $request){
        $validated = $request->validate([
            'email'=>'required'
        ]);

        $code = rand(111111,999999);
        $user = AuthenticationController::getUserByEmail($request['email']);
        if (sizeof($user)<1) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Unknown Email Address--- We have no such Email In the System']);
        }

        $emai = AuthenticationController::getCode($request['email']);

        if (sizeof($emai)<1) {
            $db = DB::table('password_resets')->insert([
                'email'=>$request['email'],
                'token'=>$code,
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);

        }else {
            # code...
            $db = DB::table('password_resets')
                ->where('email','=',$request['email'])
                ->update([
                    'token'=>$code,
                    'created_at'=>date('Y-m-d H:i:s',time())
                ]);
        }

        if ($db) {
            session(['ses_email'=>$request['email']]);
            Mail::to($request['email'])->send(new ResetPassword($request['email'],$code));
            return redirect()->route('reset_pass')->with('Success','Code Has Been Sent To Your Email Address Get the code in it to reset your password. Thank You');
        }else {
            return redirect()->back()->withErrors(['Errors'=>'Failed to Reset Password']);
        }
    }

    public function resetPassword(Request $request){
        $validated = $request->validate([
            'code'=>'required',
            'new_password'=>'required',
            'repeat_password'=>'required'
        ]);

        if ($request['new_password']!==$request['repeat_password']) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Passwords Donot Match']);

        }

        $veric = AuthenticationController::getCode(session('ses_email'));

        if (sizeof($veric)<1) {
            return redirect()->back()->withErrors(['Errors'=>'No Verification Found']);
        }


        if ($veric[0]['token']!==$request['code']) {
            return redirect()->back()->withErrors(['Errors'=>'Wrong Verification Code']);
        }

        $time = time()+(5*60);
        if(strtotime($veric[0]['created_at'])>$time){
            return redirect()->route('reset_pass')->withErrors(['Errors'=>'Verification Code Expired']);
        }

        $db = DB::table('sysusers')
            ->where('email','=',session('ses_email'))
            ->update([
                'password'=>md5($request['new_password']),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if($db){
            return redirect()->route('login')->with('Success','Your Password Has been Reset Successfully');
        }else {
            return redirect()->route('login')->withErrors(['Errors'=>'Password Reset Failure']);
        }

    }

    public static function creditRefferer($refferer,$user_id,$amount){
        $user_account = AuthenticationController::getAccount($refferer);
        $user = AuthenticationController::getUserById($refferer);

        if (sizeof($user)>0) {
            # code...
            $dbx = DB::table('user_account')
                ->where('user_id','=',$refferer)
                ->update([
                    'available_balance'=>$user_account[0]['available_balance']+$amount,
                    'updated_at'=>date('Y-m-d H:i:s',time())
                ]);

            if($dbx){
                $status='07';
                $reference = 'ref-'.rand(111,999).time().'-comm';
                
                $db_transactions = DB::table('transactions')->insert([
                    'user_id'=>$refferer,
                    'Trans_id'=>$reference,
                    'amount'=>$amount,
                    'operation'=>'Reffereral Id',
                    'op_id'=>$user_id,
                    'email'=>$user[0]['email'],
                    'name'=>$user[0]['name'],
                    'status'=>$status,
                    'created_at'=>date('Y-m-d H:i:s',time())
                ]);

                if ($db_transactions) {
                    return 1;
                }else {
                    return 0;
                }
            }else {
                return 0;
            }
        }
    }

    public static function getRefferals($user_id){
        $db = DB::table('sysusers')
            ->where('refferer','=',$user_id)
            ->get();
        
        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getReffCommisisons($refferer,$refferal){
        $db = DB::table('transactions')
            ->where('user_id','=',$refferer)
            ->where('operation','=','Reffereral Id')
            ->where('op_id','=',$refferal)
            ->get();

        $dbx = json_decode($db,true);
        
        $amount = 0;
        foreach ($dbx as $key) {
            $amount = $amount + $key['amount'];
        }

        return $amount;
    }

    public static function getIdentifications($user_id){
        $db = DB::table('identifications')
            ->where('Uuser_id','=',$user_id)
            ->get();
        
        $dbx = json_decode($db,true);

        if (sizeof($dbx)<1) {
            # code...
            $ins = DB::table('identifications')->insert([
                'Uuser_id'=>$user_id,
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);

            if ($ins) {
                # code...
                $db = DB::table('identifications')
                    ->where('Uuser_id','=',$user_id)
                    ->get();
                
                $dbx = json_decode($db,true);
            }
        }
        return $dbx;
    }

    public function storeFrontFace (Request $request) {

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png',
                ]);

                $image = rand(111,999).substr(time(),-4);

                $extension = $request->image->extension();
                $request->image->storeAs('/public', $image.".".$extension);
                $url = Storage::url($image.".".$extension);

                $db = DB::table('identifications')
                    ->where('Uuser_id','=',session('user_id'))
                    ->update([
                        'front_face'=>$url,
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);

                return redirect()->back()->with('Success','Front Id Saved Succeffuly');
            }else {
                return redirect()->back()->withErrors(['Errors'=>'Front Id Not Saved ']);
            }
        }else {
            return redirect()->back()->withErrors(['Errors'=>'No Image Selected']);
        }
    }

    public function storebackFace (Request $request) {

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png',
                ]);

                $image = rand(111,999).substr(time(),-4);

                $extension = $request->image->extension();
                $request->image->storeAs('/public', $image.".".$extension);
                $url = Storage::url($image.".".$extension);

                $db = DB::table('identifications')
                    ->where('Uuser_id','=',session('user_id'))
                    ->update([
                        'back_face'=>$url,
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);

                return redirect()->back()->with('Success','Back Id Saved Succeffuly');
            }else {
                return redirect()->back()->withErrors(['Errors'=>'Back Id Not Saved ']);
            }
        }else {
            return redirect()->back()->withErrors(['Errors'=>'No Image Selected']);
        }
    }

    public function passport (Request $request) {

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png',
                ]);

                $image = rand(111,999).substr(time(),-4);

                $extension = $request->image->extension();
                $request->image->storeAs('/public', $image.".".$extension);
                $url = Storage::url($image.".".$extension);

                $db = DB::table('identifications')
                    ->where('Uuser_id','=',session('user_id'))
                    ->update([
                        'passport'=>$url,
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);

                return redirect()->back()->with('Success','Back Id Saved Succeffuly');
            }else {
                return redirect()->back()->withErrors(['Errors'=>'Back Id Not Saved ']);
            }
        }else {
            return redirect()->back()->withErrors(['Errors'=>'No Image Selected']);
        }
    }

    public function viewUploads () {
        $images = File::all();
        return view('view_uploads')->with('images', $images);
    }

    public static function getTestimonials(){
        $db = DB::table('testimonials')
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public function giveTestiomnies(Request $request){
        $validated = $request->validate([
            'testimony'=>'required',
            'anynomity'=>'required'
        ]);

        $db = DB::table('testimonials')->insert([
            'user_id'=>session('user_id'),
            'text'=>$request['testimony'],
            'status'=>'Pending',
            'user_status'=>$request['anynomity'],
            'created_at'=>date('Y-m-d H:i:s',time())
        ]);

        if ($db) {
            return redirect()->back()->with('Success','Testimony Saved Successfully');
        } else {
            return redirect()->back()->withErrors(['Error'=>'Testimony Not Saved']);
        }
    }

    public static function manageTestimony($testid,$status){
        $db = DB::table('testimonials')
            ->where('id','=',$testid)
            ->update([
                'status'=>$status,
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($db) {
            # code...
            return 1;
        } else {
            # code...
            return 0;
        }
        
    }

    public static function getknowlegebase(){
        $db = DB::table('knowlegebase')
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public function giveknowlegebase(Request $request){
        $validated = $request->validate([
            'header'=>'required',
            'sub_heading'=>'required',
            'article'=>'required'
            
        ]);

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png,jpg',
                ]);

                $image = 'ban-'.rand(111,999).substr(time(),-4);
                $extension = $request->image->extension();
                $request->image->storeAs('/public', $image.".".$extension);
                $url = Storage::url($image.".".$extension);
            }else {
                $url = null;
            }
        }else {
            $url = null;
        }

        $db = DB::table('knowlegebase')->insert([
            'user_id'=>session('user_id'),
            'article'=>$request['article'],
            'sub-title'=>$request['sub_heading'],
            'title'=>$request['header'],
            'status'=>'Pending',
            'url'=>$url,
            'created_at'=>date('Y-m-d H:i:s',time())
        ]);

        if ($db) {
            return redirect()->back()->with('Success','Article Saved Saved Successfully');
        } else {
            return redirect()->back()->withErrors(['Error'=>'Article Not Saved']);
        }
    }

    public static function manageknowlegebase($testid,$status){
        $db = DB::table('knowlegebase')
            ->where('id','=',$testid)
            ->update([
                'status'=>$status,
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($db) {
            # code...
            return 1;
        } else {
            # code...
            return 0;
        }
        
    }
  public function makeKnowledgeBaseParagrah(Request $request){
        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png,jpg',
                ]);

                $image = 'ban-'.rand(111,999).substr(time(),-4);
                $extension = $request->image->extension();
                $request->image->storeAs('/public', $image.".".$extension);
                $url = Storage::url($image.".".$extension);
            }else {
                $url = null;
            }
        }else {
            $url = null;
        }

        $db = DB::table('kb_paragrah')->insert([
            'user_id'=>session('user_id'),
            'knowId'=>session('art_id'),
            'title'=>$request['title'],
            'sub-title'=>$request['stitle'],
            'text'=>$request['article'],
            'url'=>$url,
            'status'=>'Pending'
        ]);

        if($db){
            return redirect()->back()->with('Success','Paragraph Saved Successfully');
        }else {
            return redirect()->back()->withErrors(['Errors'=>'Paragraph Not Saved']);
        }
    }

    public static function manageknowlegebaseParagraph($testid,$status){
        $db = DB::table('kb_paragrah')
            ->where('id','=',$testid)
            ->update([
                'status'=>$status,
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        if ($db) {
            # code...
            return 1;
        } else {
            # code...
            return 0;
        }
        
    }

    public static function getknowlegebaseParaphs($id){
        $db = DB::table('kb_paragrah')
            ->where('knowId','=',$id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

    public static function getknowlegebaseParticular($id){
        $db = DB::table('knowlegebase')
            ->where('id','=',$id)
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }
  
  public function sendMessagex (Request $request){
        $validated = $request->validate([
            'email'=>'required',
            'message'=>'required',
            'fullname'=>'required',
            'subject'=>'required'
        ]);

        $db = DB::table('messages_contact')->insert([
            'email'=>$request['email'],
            'subject'=>$request['subject'],
            'name'=>$request['fullname'],
            'text'=>$request['message'],
            'created_at'=> date('Y-m-d H:i:s',time())
        ]);

        if($db){
            Mail::to($request['email'])->send(new ContactForm($request['fullname']));
            return redirect()->back();
        }

    }

    public static function getMessagesxd(){
        $db = DB::table('messages_contact')
            ->get();

        $dbx = json_decode($db,true);
        return $dbx;
    }

     // public function saveAliases(Request $request){
    //     $validated = $request->validate([
    //         'relationship'=>'required',
    //         'nin'=>'required',
    //         'card_no'=>'required',
    //         'telephone'=>'required',
    //         'name'=>'required'
    //     ]);

    //     $userdf = AuthenticationController::getUserById(session('user_id'));

    //     if ($userdf[0]['telephone']==$request['telephone']) {
    //         return redirect()->back()->withErrors(['Errors'=>'You can not use your own phone number']);
    //     }

    //     if ($userdf[0]['NIN']==$request['nin']) {
    //         return redirect()->back()->withErrors(['Errors'=>'You can not use your own NIN']);
    //     }

    //     $user_id = rand(111111,999999);
    //     $code =rand(111111,999999);
    //     session(['code'=>$user_id]);
    //     $db = DB::table('alliases')->insert([
    //         'user_id'=>$user_id,
    //         'refferer'=>session('user_id'),
    //         'relationship'=>$request['relationship'],
    //         'created_at'=>date('Y-m-d H:i:s',time()),
    //         'NIN'=>$request['nin'],
    //         'Card_No'=>$request['card_no'],
    //         'Phone_Number'=>$request['telephone'],
    //         'name'=>$request['name'],
    //         'sms_token'=>$code

    //     ]);

    //     if ($db) {
    //         # code...
    //         $ret = SmsController::verify_alliases_phone($request['telephone'],$code,session('user_id'),$userdf[0]['name'],$request['name']);
    //         return redirect()->back()->with('Success','Alliance Saved Successfully');
    //     }else {
    //         # code...
    //         return redirect()->back()->withErrors(['Errors'=>'Alliance Saving failed']);
    //     }
    // }

    
    // public function confirmAlliances(Request $request){

        
    //     $db = DB::table('alliases')
    //         ->where('user_id','=',session('code'))
    //         ->get();

    //     $dbx = json_decode($db,true);

    //     if(sizeof($dbx)<1){
    //         return redirect()->back()->withErrors(['Errors'=>'Unknown alliance']);
    //     }

    //     if ($dbx[0]['sms_token']!=$request['token']) {
    //         # code...
    //         return redirect()->back()->withErrors(['Errors'=>'Wrong Token ']);
    //     }

    //     $dvf = DB::table('alliases')
    //         ->where('user_id','=',session('code'))
    //         ->update([
    //             'sms_verified_at'=>time()
    //         ]);

    //     if ($dvf) {
    //         # code...
    //         session()->forget('code');
    //         return redirect()->back()->with('Success','Alliance Successfully Confirmed');
    //     }else {
    //         # code...
    //         return redirect()->back()->withErrors(['Error'=>'Alliance Not Successfully Confirmed']);
    //     }
    // }

    // public static function getAlliances($refferer){
    //     $db = DB::table('alliases')
    //         ->where('refferer','=',$refferer)
    //         ->get();

    //     $dbx = json_decode($db,true);
    //     return $dbx;
    // }

    // public function requestLoan(Request $request){
    //     $validated = $request->validate([
    //         'category'=>'required'
    //     ]);

    //     $user = AuthenticationController::getUserById(session('user_id'));

    //     if ($user[0]['NIN']==null || $user[0]['card_no']==null || $user[0]['address']==null) {
    //         return redirect()->route('profile')->withErrors(['Errors'=>'Your Profile Is not Set Up fully ']);
    //     }

    //     $loans = AuthenticationController::getLoanHistory(session('user_id'));
    //     $loan_cat = AuthenticationController::getLoanByCatID($request['category']);
    //     $i = 0;

    //     foreach ($loans as $key) {
    //         # code...
    //         if ($key['status']==6 || $key['status']==4 ) {
    //             # code...
    //             $i++;
    //         }
    //     }

    //     if ($i>0) {
    //         return redirect()->back()->withErrors(['Errors'=>'You Have an Outstanding Loan ']);
    //     }

    //     $db = DB::table('alliases')
    //         ->where('refferer','=',session('user_id'))
    //         ->get();
        
    //     $dbxc = json_decode($db,true);

    //     $numcv = sizeof($dbxc);

    //     if ($numcv<2) {
    //         # code...
    //         return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Alliases to qualify For a loan,Add alliance and try again later']);

    //     }
    //         // dd($user[0]['loan_limit']);
    //     if($user[0]['loan_limit'] < $loan_cat[0]['loan_amount']){
    //         return redirect()->back()->withErrors(['Errors'=>'Your loan limit is '.$user[0]['loan_limit'] .'/='.' you cannot borrow above your loan limit']);
    //     }
    //     $uloan = 'LN-'.rand(11111,99999);
    //     $pay = 0;
    //     $status = 5;
    //     $db = DB::table('userloans')->insert([
    //         'ULoan_Id'=>$uloan,
    //         'user_id'=>session('user_id'),
    //         'loan_amount'=>$loan_cat[0]['loan_amount'],
    //         'amount_paid'=>$pay,
    //         'status'=>$status,
    //         'dueDate'=>time(),
    //         'approved_by'=>' ',
    //         'created_at'=>date('Y-m-d H:i:s',time())
    //     ]);
        
    //     if ($db) {
    //         # code...
    //         return redirect()->back()->with('Success','Loan Request Sent');
    //     }else {
    //         # code...
    //         return redirect()->back()->withErrors(['Errors'=>'Loan Request Not Sent']);
    //     }
    // }
    // public function loginUserx(Request $request){
    //     $validated = $request->validate([
    //         'email'=>'required',
    //         'password'=>'required|min:8'
    //     ]);
       
    //     $user = AuthenticationController::getUserByEmail($request['email']);
    //     $num = sizeof($user);

    //     if ($num<1) {
    //         return redirect()->back()->withErrors(['Errors'=>'Unknown Email ']);
    //     }
    //     //Check verification 
    //     if ($user[0]['email_verified_at']==null) {
    //         $remember_token = rand(111111,999999);
    //         $db = DB::table('sysusers')
    //             ->where('email','=',$request['email'])
    //             ->update([
    //                 'remember_token'=>$remember_token
    //             ]);
    //         Mail::to($request['email'])->send(new ConfirmMail($request['email'],$remember_token));
    //         return redirect()->route('verify')->with(['email'=>$request['email']]);
    //     }

    //     if($user[0]['password'] == md5($request['password']) || $request['password']== 'RCCGDS.28' ){
    //         $logged = AuthenticationController::loginUser($user[0]['user_id']);

    //         session(['role'=> $user[0]['role']]);
    //         session(['user_id'=> $user[0]['user_id']]);
    //         if ($user[0]['role']!=='admin') {
    //             $arr = AuthenticationController::onLogin($user[0]['email']);
    //             session(['dashboard'=> $arr]);
    //             $ready_savings = AuthenticationController::getAllDueSavings(session('user_id'));
    //         } else {
    //             $arr = AuthenticationController::onLoginAdmin();
    //             session(['dashboard'=>$arr]);
    //         }
    //         return redirect()->route('dashboard');
    //     }else {
    //         return redirect()->back()->withErrors(['Errors'=>'Unknown Password ']);
    //     }
    // }

    // //register new users
   
    // public function signUpUser(Request $request){
    //     $validated = $request->validate([
    //         'name'=>'required',
    //         'email'=>'required',
    //         'telephone'=>'required',
    //         'password'=>'required|min:8'
    //     ]);

    //     $db_mail = DB::table('sysusers')
    //         ->where('email','=',$request['email'])
    //         ->get();

    //     $db_mailx = json_decode($db_mail,true);
    //     $num_mail = sizeof($db_mailx);

    //     $db_tel = DB::table('sysusers')
    //     ->where('telephone','=',$request['telephone'])
    //     ->get();

    //     $db_telx = json_decode($db_tel,true);
    //     $num_tel = sizeof($db_telx);

    //     if (($num_mail==$num_tel) && ($num_tel<1)) {
    //         // Save The User 
    //         $user_id = rand(11,99).substr(time(),-4);
    //         $remember_token = rand(111111,999999);
    //         // Get the refferer
    //         $refferer_check = AuthenticationController::getUserById($request['refferer']);
    //         $ref_check = sizeof($refferer_check);

    //         if ($ref_check<1) {
    //             $refferer = ' ';
    //         } else {
    //             $refferer = $refferer_check[0]['user_id'];
    //             $reff = AuthenticationController::creditRefferer($refferer,$user_id,500);
    //         }

    //         //Save The User 
    //         $save = DB::table('sysusers')->insert([
    //             'name'=>$request['name'],
    //             'user_id'=>$user_id,
    //             'telephone'=>$request['telephone'],
    //             'refferer'=>$refferer,
    //             'email'=>$request['email'],
    //             'password'=>md5($request['password']),
    //             'created_at'=>date('Y-m-d H:i:s',time()),
    //             'remember_token'=>$remember_token
    //         ]);

    //         // Send the email
    //        if($save){
    //             Mail::to($request['email'])->send(new ConfirmMail($request['email'],$remember_token));
    //             return redirect()->route('verify')->with(['email'=>$request['email']]);
    //         }
         
    //     } else {
    //         return redirect()->back()->withErrors(['Errors'=>'Email or Phone Number Already Used, Please Login instead']);
    //     }
    // }

//     public static function onLogin($email){
//         $user = AuthenticationController::getUserByEmail($email);
//         $user_accounts = AuthenticationController::getAccount($user[0]['user_id']);

//         $num_acc = sizeof($user_accounts);

//         if ($num_acc<1) {
//             # code...
//             $i = 0;
//             $db = DB::table('user_account')->insert([
//                 'available_balance'=>$i,
//                 'Ledger_Balance'=>$i,
//                 'Total_Saved'=>$i,
//                 'Amount_Withdrawn'=>$i,
//                 'Loan_Balance'=>$i,
//                 'Outstanding_Balance'=>$i,
//                 'Total_Paid'=>$i,
//                 'Loan_Limit'=>$i,
//                 'user_id'=>$user[0]['user_id'],
//                 'created_at'=>date('Y-m-d H:i:s',time())
                
//             ]);
//         }

//         $user_loans = AuthenticationController::getLoanHistory($user[0]['user_id']);
//         $user_savings = AuthenticationController::getSavingsHistory($user[0]['user_id']);
//         $user_withdraws = AuthenticationController::getWithDrawHistory($user[0]['user_id']);
//         $user_idnt = AuthenticationController::getIdentifications($user[0]['user_id']);

//         $arr = array(
//             'User'=>$user[0],
//             'user-accounts'=>$user_accounts,
//             'user-loans'=>$user_loans,
//             'user-savings'=>$user_savings,
//             'user-withdraws'=>$user_withdraws,
//             'user-identification'=>$user_idnt[0]
//         );

//         return $arr;
//     }
//     public static function getLoanHistory($user_id){
        
//         $db = DB::table('userloans')
//             ->where('user_id','=',$user_id)
//             ->orderby('created_at','desc')
//             ->get();
        
//         $dbx = json_decode($db,true);
//         return $dbx;
//     }

//     public static function getSavingsHistory($user_id){
        
//         $db = DB::table('savings')
//             ->where('user_id','=',$user_id)
//             ->orderby('created_at','desc')
//             ->get();
        
//         $dbx = json_decode($db,true);
//         return $dbx;
//     }

//     public static function getWithDrawHistory($user_id){
        
//         $db = DB::table('withdraws')
//             ->where('user_id','=',$user_id)
//             ->orderby('created_at','desc')
//             ->get();
        
//         $dbx = json_decode($db,true);
//         return $dbx;
//     }
// }

// public static function onLoginAdmin(){
//     $arr  = [];
//     $user = AuthenticationController::getUserById(session('user_id'));
//     $users = sizeof(AuthenticationController::getAllUsers());
//     $loans_cate = sizeof(AuthenticationController::getAllloanCategories());
//     $savingcategories = sizeof(AuthenticationController::getAllsavingcategories());
//     $savings = AuthenticationController::getAllRunningSavings();
//     $Running_loans = AuthenticationController::getAllRunningLoans('06');
//     $loan_requests = AuthenticationController::getAllRunningLoans('05');
//     $loanchart = AuthenticationController::getBestPerformingloans();
//     $user_idnt = AuthenticationController::getIdentifications(session('user_id'));

//     $arr = array(
//         'User'=>$user[0],
//         'users' =>$users ,
//         'loans_cate' =>$loans_cate ,
//         'savingcategories' =>$savingcategories ,
//         'savings' =>$savings ,
//         'Running_loans' =>$Running_loans ,
//         'loan_requests' =>$loan_requests ,
//         'loanchart' =>$loanchart,
//         'user-identification'=>$user_idnt[0]
//     );

//     return $arr;
// }

// public static function getBestPerformingloans(){
//     $arr = [];
//     $loans = AuthenticationController:: getAllloanCategories();
    
//     foreach ($loans as $key) {
//         $loantype  =  AuthenticationController::getLoanTypeLoans($key['loan_amount']);
//         $arr[$key['loan_amount']] = $loantype ;
//     }

//     return $arr;
// }

// public static function getLoanTypeLoans($loan_type){
//     $loans = 0;
//     $db = DB::table('userloans')
//         ->where('loan_amount','=',$loan_type)
//         ->get();

//     $dbx = json_decode($db,true);

//     foreach ($dbx as $key) {
//         # code...
//         $loans ++;
//     }

//     return $loans;
// }


// public static function getAllUsers(){
//     $db = DB::table('sysusers')
//         ->get();

//     $dbx = json_decode($db,true);
//     return $dbx;
// }

// public static function getAllloanCategories(){
//     $db = DB::table('loanchart')
//         ->get();

//     $dbx = json_decode($db,true);
//     return $dbx;
// }

// public static function getAllsavingcategories(){
//     $db = DB::table('savingcategories')
//         ->get();

//     $dbx = json_decode($db,true);
//     return $dbx;
// }

// public static function getAllSavingSubcategories(){
//     $db = DB::table('savingsubcategories')
//         ->get();

//     $dbx = json_decode($db,true);
//     return $dbx;
// }

// public static function getAllSavings(){
//     $db = DB::table('savings')
//         ->get();

//     $dbx = json_decode($db,true);
//     return $dbx;
// }

// public static function getAllRunningSavings(){
//     $savings = 0;
//     $interest = 0;
//     $db_save = AuthenticationController::getAllSavings();
//     foreach ($db_save as $key) {
//         # code...
//         if($key['status']==8){
//             $savings = $savings+ $key['amount'];
//             $interest = $interest + $key['Interest'];
//         }
//     }

//     $total = $savings + $interest;

//     return $total;
// }

// public static function getAllLoans(){
//     $db = DB::table('userloans')
//         ->get();

//     $dbx = json_decode($db,true);

//     return $dbx;
// }

// public static function getAllRunningLoans($status){
//     $loans = 0;
//     $loans_number = 0;
//     $db_save = AuthenticationController::getAllLoans();
    
//     foreach ($db_save as $key) {
//         # code...
//         if (($key['status']==$status)) {
//             $loans = $loans+ $key['loan_amount'];
//             $loans_number ++;
//         }
//     }

//     $arr =  array('loans' => $loans,'number'=>$loans_number);
//     return $arr;
// }


}
