<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use App\Http\Controllers\AuthenticationController as Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmMail;
use App\Http\Controllers\SmsController;

class FlutterwaveController extends BaseController
{
    //
    public function initialize()
    {
        $user = Auth::getUserById(session('user_id'));
        //Getting the saving category
        $cate = Auth::getSavingSubCatByCatId(request()->category);

        $num = sizeof($cate);

        if ($num<1) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Unsupported Saving Category Choose Another ']);
        }

        $real_cate = Auth::getSavingCatByCatId($cate[0]['cate_id']);

        if ((request()->amount < $real_cate[0]['lowerlimit']) || (request()->amount > $real_cate[0]['upperlimit'])  ) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Amount to be saved doesnot fall under this category choose supported category...']);
        }
        //This generates a payment reference
        $reference =Flutterwave::generateReference().'_sv';

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer,mobile_money_ug',
            'amount' => request()->amount,
            'email' => $user[0]['email'],
            'tx_ref' => $reference,
            'currency' => "UGX",
            'redirect_url' => route('callback'),
            'customer' => [
                'email' => $user[0]['email'],
                "phone_number" => $user[0]['telephone'],
                "name" => $user[0]['name']
            ],

            "customizations" => [
                "title" => 'Appnomu Savings'
            ]
        ];

        $payment = Flutterwave::initializePayment($data);
        if ($payment['status'] !== 'success') {
            return redirect()->back()->withErrors(['Errors'=>'Transaction Failed To Start']);
        }elseif ($payment['status'] == 'success') {
            # code...
            $status = '06';
            $op_id = 'Save-'.rand(11111,99999);
            $db_transactions = DB::table('transactions')->insert([
                'user_id'=>session('user_id'),
                'Trans_id'=>$reference,
                'amount'=>request()->amount,
                'operation'=>'Saving',
                'op_id'=>$op_id,
                'email'=>$user[0]['email'],
                'name'=>$user[0]['name'],
                'status'=>$status,
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);
            $status2 = 6;
            $processing = 0;
            $db_saving = DB::table('savings')->insert([
                'SubCateId'=>request()->category,
                'saving_id'=>$op_id,
                'user_id'=>session('user_id'),
                'amount'=>request()->amount,
                'status'=>$status2,
                'Interest'=>(request()->amount)*($cate[0]['Interest']/100),
                'duedate'=>time(),
                'savingdate'=>time(),
                'processing_fees'=>$processing,
                'created_at'=> date('Y-m-d H:i:s',time())
            ]);

        }

        return redirect($payment['data']['link']);
    }

    
    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        
        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
        
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            // dd($data);
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($data['status']=='success' && $status=='successful' && $dbx[0]['status']=='06' ) {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'07',
                        'FLW_Id'=>$data['data']['id'],
                        'flw_charge'=>$data['data']['app_fee'],
                        'mode'=>$data['data']['auth_model'],
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 7;
                if(1){
                    $savings = Auth::getSavingById($dbx[0]['op_id']);
                    $getting_sub_Cat = Auth::getSavingSubCatByCatId($savings[0]['SubCateId']);

                    $witdraw_date = time()+($getting_sub_Cat[0]['Saving_Period']*24*60*60);
                    $db_pg = DB::table('savings')
                        ->where('saving_id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'duedate'=>$witdraw_date,
                            'savingdate'=>time(),
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # code...
                        $user_acc = Auth::getAccount($dbx[0]['user_id']);
                        $userxc = Auth::getUserById($dbx[0]['user_id']);
                        //refferal Commission
                        if ($savings[0]['amount']<50000) {
                            $amount = 0;
                        } elseif ($savings[0]['amount']>50000 && $savings[0]['amount']<300000) {
                            $amount = 0;
                        } elseif($savings[0]['amount']>300000) {
                            $amount = 0;
                        }
                        
                        $reff = Auth::creditRefferer($userxc[0]['refferer'],$dbx[0]['user_id'], $amount);
                        //Refferal Commission Finish
                        $dbop = DB::table('user_account')
                            ->where('user_id','=',$dbx[0]['user_id'])
                            ->update([
                                'Ledger_Balance'=>$user_acc[0]['Ledger_Balance'] + ($savings[0]['amount'] + $savings[0]['Interest'] ),
                                'Total_Saved'=>$user_acc[0]['Total_Saved'] + $savings[0]['amount'] 
                            ]);

                        if ($dbop) {
                            return redirect()->route('all_savings')->with(['Success'=>'Saving Succesful']);
                        }
                        
                    }
                }
            }else {
                # code..
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        elseif ($status ==  'cancelled'){
            //Put desired action/code after transaction has been cancelled here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='06' && request()->status=='cancelled') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'05',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                    $db_pg = DB::table('savings')
                        ->where('saving_id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # code...
                        return redirect()->route('all_savings')->withErrors(['Error'=>'Transaction Failed']);
                    }
                }
            }else {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        else{
            //Put desired action/code after transaction has failed here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='06' && request()->status=='failed') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'05',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                    $db_pg = DB::table('savings')
                        ->where('saving_id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # code...
                        return redirect()->route('all_savings')->withErrors(['Error'=>'Transaction Failed']);
                    }
                }
            }else {
                # code...
                return redirect()->route('all_savings')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }


        }

    }

    public static function pay_loan($i)
    {
        $user = Auth::getUserById(session('user_id'));
        $cate = Auth::getLoanInstallmentById($i);
        $lons = Auth::getLoanId($cate[0]['ULoan_Id']);
        $lonx = Auth::getLoanCatID($lons[0]['loan_amount']);

        $num = sizeof($cate);

        if ($num<1) {
            return 1;
        }

        if ($cate[0]['status']!=6) {
            return 2;
        }

        //This generates a payment reference
        $reference =Flutterwave::generateReference().'_ln';

        // Enter the details of the payment
        $data = [
            'payment_options' => 'card,banktransfer,mobile_money_ug',
            'amount' => $cate[0]['Amount_Paid']+(($lonx[0]['interest_rate']/100)*$cate[0]['Amount_Paid'])+$lonx[0]['processing_fees'],
            'email' => $user[0]['email'],
            'tx_ref' => $reference,
            'currency' => "UGX",
            'redirect_url' => route('callback_loans'),
            'customer' => [
                'email' => $user[0]['email'],
                "phone_number" => $user[0]['telephone'],
                "name" => $user[0]['name']
            ],

            "customizations" => [
                "title" => 'Appnomu Loan Repayment'
            ]
        ];

        $payment = Flutterwave::initializePayment($data);
        if ($payment['status'] !== 'success') {
            return 3;
        }elseif ($payment['status'] == 'success') {
            # code...
            $status = '06';
            $op_id = 'ln-'.rand(11111,99999);
            $db_transactions = DB::table('transactions')->insert([
                'user_id'=>session('user_id'),
                'Trans_id'=>$reference,
                'amount'=>$cate[0]['Amount_Paid']+(($lonx[0]['interest_rate']/100)*$cate[0]['Amount_Paid'])+$lonx[0]['processing_fees'],
                'operation'=>'Loan Installement',
                'op_id'=>$i,
                'email'=>$user[0]['email'],
                'name'=>$user[0]['name'],
                'status'=>$status,
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);

        }
        return $payment['data']['link'];
    }

    public function loan_callback()
    {
        
        $status = request()->status;

        //if payment is successful
        if ($status ==  'successful') {
        
            $transactionID = Flutterwave::getTransactionIDFromCallback();
            $data = Flutterwave::verifyTransaction($transactionID);

            // dd($data);
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($data['status']=='success' && $status=='successful' && $dbx[0]['status']=='06' ) {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'07',
                        'FLW_Id'=>$data['data']['id'],
                        'flw_charge'=>$data['data']['app_fee'],
                        'mode'=>$data['data']['auth_model'],
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 7;

                if($db_up){
                    $install = Auth::getLoanInstallmentById($dbx[0]['op_id']);
                    // if ( $install[0]['Amount_Paid'] !==  $dbx[0]['amount']) {
                    //     return redirect()->back()->withErrors(['Errors'=>'Loan Amount is less']);
                    // }

                    $rt =7;
                    $db_pg = DB::table('loanpaymentsinstallments')
                        ->where('id','=',$dbx[0]['op_id'])
                        ->update([
                            'status'=>$rt,
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db_pg) {
                        # get loan and edit it 
                        $user_loan = Auth::getLoanByCatID2($install[0]['ULoan_Id']);
                        $num_lns = sizeof($user_loan);
                        
                        $lonx = Auth::getLoanCatID($user_loan[0]['loan_amount']);

                        if ($num_lns<1) {
                            return 6;
                        }
                        
                        $total_loan = $user_loan[0]['loan_amount'] + $lonx[0]['processing_fees'] +(($lonx[0]['interest_rate']/100)*$user_loan[0]['loan_amount']);

                        $paid = $user_loan[0]['amount_paid'] + $dbx[0]['amount'];

                        if ($paid > $total_loan || $paid == $total_loan ) {
                            
                            $statusdf = 7;
                        } else {
                            # code...
                            $statusdf =  $user_loan[0]['status'];
                        }
                        
                        $dbop = DB::table('userloans')
                            ->where('ULoan_Id','=',$install[0]['ULoan_Id'])
                            ->update([
                                'status'=>$statusdf,
                                'amount_paid'=> $user_loan[0]['amount_paid'] + $dbx[0]['amount'],
                                'updated_at'=>date('Y-m-d H:i:s',time())
                            ]);
                        

                        if ($dbop) {
                            $user_accv= Auth::getAccount($dbx[0]['user_id']);
                            $userxc = Auth::getUserById($dbx[0]['user_id']);
                            //refferal Commission
                            if ($dbx[0]['amount']<50000) {
                                $amount = 500;
                            } elseif ($dbx[0]['amount']>50000 && $dbx[0]['amount']) {
                                $amount = 500;
                            } elseif($dbx[0]['amount']>300000) {
                                $amount = 500;
                            }
                            
                            $reff = Auth::creditRefferer($userxc[0]['refferer'],$dbx[0]['user_id'], $amount);
                            //Refferal Commission Finish

                            //Edit the user account

                            $user_acc = Auth::getAccount($dbx[0]['user_id']);

                            $db_acg = DB::table('user_account')
                                ->where('user_id','=',$dbx[0]['user_id'])
                                ->update([
                                    'Outstanding_Balance'=>$user_acc[0]['Outstanding_Balance'] - $dbx[0]['amount'],
                                    'Total_Paid'=>$user_acc[0]['Total_Paid'] + $dbx[0]['amount']
                                ]);
                            //finish edititng user account 
                            return redirect()->route('loan-installments')->with('Success','Loan Payment Succesful');
                        }
                        
                    }
                }
            }else {
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        elseif ($status ==  'cancelled'){
            //Put desired action/code after transaction has been cancelled here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='06'){
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='06' && request()->status=='cancelled') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'05',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                     return redirect()->route('loan-installments')->withErrors(['Error'=>'Transaction Failed']);
                    
                }
            }else {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }
        }
        else{
            //Put desired action/code after transaction has failed here
            $db_trans_update = DB::table('transactions')
                ->where('Trans_id','=',request()->tx_ref)
                ->get();

            $dbx = json_decode($db_trans_update,true);
            $num = sizeof($dbx);

            if ($num<1) {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Transaction Reference']);
            }

            if($dbx[0]['status']!='05'){
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }

            if ($dbx[0]['status']=='05' && request()->status=='failed') {
                # code...
                $db_up = DB::table('transactions')
                    ->where('Trans_id','=',request()->tx_ref)
                    ->update([
                        'status'=>'08',
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    ]);
                $rt = 9;
                if($db_up){
                
                    return redirect()->route('loan-installments')->withErrors(['Error'=>'Transaction Failed']); 
                }
            }else {
                # code...
                return redirect()->route('loan-installments')->withErrors(['Error'=>'Unknown Operation On Transaction']);
            }


        }

    }

    public function webhook(Request $request){
        //This verifies the webhook is sent from Flutterwave
        $verified = Flutterwave::verifyWebhook();

        // if it is a charge event, verify and confirm it is a successful transaction
        if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
            $verificationData = Flutterwave::verifyPayment($request->data['id']);
            if ($verificationData['status'] === 'success') {
            // process for successful charge

            }

        }

        // if it is a transfer event, verify and confirm it is a successful transfer
        if ($verified && $request->event == 'transfer.completed') {

            $transfer = Flutterwave::transfers()->fetch($request->data['id']);

            if($transfer['data']['status'] === 'SUCCESSFUL') {
                // update transfer status to successful in your db
                $tran = FlutterwaveController::getTransactions($transfer['data']['reference']);

                if ($tran[0]['Trans_id']=='06') {
                    # code...
                    $db = DB::table('transactions')
                        ->where('Trans_id','=',$transfer['data']['reference'])
                        ->update([
                            'status'=>'07',
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);
                }


            } else if ($transfer['data']['status'] === 'FAILED') {
                // update transfer status to failed in your db
                $tran = FlutterwaveController::getTransactions($transfer['data']['reference']);

                if ($tran[0]['Trans_id']=='06') {
                    # code...
                    $db = DB::table('transactions')
                        ->where('Trans_id','=',$transfer['data']['reference'])
                        ->update([
                            'status'=>'05',
                            'updated_at'=>date('Y-m-d H:i:s',time())
                        ]);

                    if ($db) {
                        # Take Back the money
                        $withdraws = FlutterwaveController::getWithdrawTransactions($transfer['data']['reference']);

                        $user_acc = Auth::getAccount($withdraws[0]['user_id']);

                        if ($withdraws[0]['withdraw_from']=='savings') {
                            # code...
                            $db_up = DB::table('user_account')
                                ->where('user_id','=',$withdraws[0]['user_id'])
                                ->update([
                                    'available_balance'=> $user_acc[0]['available_balance'] + ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    'Amount_Withdrawn'=> $user_acc[0]['Amount_Withdrawn'] - ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    
                                ]);
                        }elseif ($withdraws[0]['withdraw_from']=='loans') {
                            # code...
                            $db_up = DB::table('user_account')
                                ->where('user_id','=',$withdraws[0]['user_id'])
                                ->update([
                                    'Loan_Balance'=> $user_acc[0]['Loan_Balance'] + ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    'Amount_Withdrawn'=> $user_acc[0]['Amount_Withdrawn'] - ($transfer['data']['amount'] + $transfer['data']['fee'] ),
                                    
                                ]);
                        }

                        $db_with = DB::table('withdraws')
                            ->where('trans_id','=',$transfer['data']['reference'])
                            ->update([
                                'status'=>'05'
                            ]);
                    }
                }
                // revert customer balance back
            } else if ($transfer['data']['status'] === 'PENDING') {
                // update transfer status to pending in your db
            }

        }
    }

    public function withdraw(Request $request){
        $validated = $request->validate([
            'account'=>'required',
            'amount'=>'required'
        ]);

        $user = Auth::getUserById(session('user_id'));
        $user_acc = Auth::getAccount(session('user_id'));

        if (sizeof($user)<1) {
            return redirect()->back()->withErrors(['Errors'=>'User Account Error Contact Administration']);
        }

        if (sizeof($user_acc)<1) {
            return redirect()->back()->withErrors(['Errors'=>'User Account Error Contact Administration']);
        }

        if ($user[0]['sms_verified_at']==null) {
            return redirect()->route('profile')->withErrors(['Errors'=>'No Verified Phone Number Has Been Found Please Verify Your Phone Number Before You Can Withdraw Your Money']);
        }

        if ($request['amount']<1000) {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Your Minimum Value of Withdraw Should be UGX.1000']);
        }

        if($request['account']=='savings'){
            if(($request['amount']+500)>$user_acc[0]['available_balance'] ){
                $stat = '09';
            }else {
                $stat = '07';
            }
        }elseif ($request['account']=='loans'){
            if(($request['amount']+500)>$user_acc[0]['Loan_Balance'] || $request['amount']==$user_acc[0]['Loan_Balance']){
                $stat = '09';
            }else {
                $stat = '07';
            }
        }

        if ($stat=='09') {
            return redirect()->back()->withErrors(['Errors'=>'You Dont have sufficient Funds on Your Account']);
        }
        

        $reference = Flutterwave::generateReference().'_wd';

        $data = [
            "account_bank" => "MPS",
            "account_number" => $user[0]['telephone'],
            "amount" => $request['amount'],
            "narration" => "Appnomu Withdraws",
            "currency" => "UGX",
            "reference" => $reference,
            "beneficiary_name" => $user[0]['name']
        ];
        
        $transfer = Flutterwave::transfers()->initiate($data);

        if ($transfer['status']!=='success') {
            return redirect()->back()->withErrors(['Errors'=>'Transaction Failed To Start']);
        } elseif ($transfer['status']=='success') {
            $status = '06';
            $op_id = 'Withdraws-'.rand(11111,99999);
            $db_transactions = DB::table('transactions')->insert([
                'user_id'=>session('user_id'),
                'Trans_id'=>$reference,
                'amount'=>$request['amount'],
                'operation'=>'Withdraw',
                'op_id'=>$op_id,
                'email'=>$user[0]['email'],
                'name'=>$user[0]['name'],
                'status'=>$status,
                'created_at'=>date('Y-m-d H:i:s',time()),
                'FLW_Id'=>$transfer['data']['id'],
                'FLW_txref'=>$transfer['data']['reference'],
                'mode'=>$transfer['data']['bank_name'],
                'flw_charge'=>$transfer['data']['fee']
            ]);

            $db_withdraws = DB::table('withdraws')->insert([
                'user_id'=>session('user_id'),
                'trans_id'=>$reference,
                'accounts_number'=>$transfer['data']['account_number'],
                'Currency'=>$transfer['data']['currency'],
                'amount'=>$transfer['data']['amount'],
                'withdraw_from'=>$request['account'],
                'status'=>$status,
                'mode'=>$transfer['data']['amount'],
                'created_at'=>date('Y-m-d H:i:s',time())
            ]);

            if ($request['account']=='savings') {
                $db_acc = DB::table('user_account')
                    ->where('user_id','=',session('user_id'))
                    ->update([
                        'available_balance'=> $user_acc[0]['available_balance'] - ($transfer['data']['amount'] + $transfer['data']['fee']),
                        'Amount_Withdrawn'=>$user_acc[0]['Amount_Withdrawn'] + ($transfer['data']['amount'] + $transfer['data']['fee'])
                    ]);
            } elseif ($request['account']=='loans') {
                $db_acc = DB::table('user_account')
                    ->where('user_id','=',session('user_id'))
                    ->update([
                        'available_balance'=> $user_acc[0]['Loan_Balance'] - ($transfer['data']['amount'] + $transfer['data']['fee']),
                        'Amount_Withdrawn'=>$user_acc[0]['Amount_Withdrawn'] + ($transfer['data']['amount'] + $transfer['data']['fee'])
                    ]);
            }
            
            return redirect()->back()->with('Success','Withdraw Initiated Successfully');

        } 
    }

    public static function getTransactions($transid){
        $db = DB::table('transactions')
            ->where('Trans_id','=',$transid)
            ->get();
    
        $dbx = json_decode($db,true);

        return $dbx;
    }

    public static function getWithdrawTransactions($transid){
        $db = DB::table('withdraws')
            ->where('trans_id','=',$transid)
            ->get();
    
        $dbx = json_decode($db,true);

        return $dbx;
    }

}
