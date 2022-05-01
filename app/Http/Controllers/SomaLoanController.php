<?php

namespace App\Http\Controllers;

use App\Models\SomaLoan;
use App\Models\User;
use App\Models\District;
use App\Models\Headteacher;
use App\Models\LoanCategory;
use App\Models\ParentDetail;
use App\Models\Repayment;
use App\Models\Student;
use Carbon\Carbon;
use AfricasTalking\SDK\AfricasTalking;
use App\Models\Grade;
use App\Models\Identification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SomaLoanController extends Controller
{
    //dashboard access
    public function somaDashboard(){
        $users = AuthenticationController::getUserById(session('user_id'));
        $user = User::findOrFail($users[0]['id']);
        if($user->role== 'Admin'){
            return redirect()->route('soma.index');
        }
        return redirect()->route('soma.borrower.index',['id'=>$user->id]);
    }
    //get all soma loans
    public function index(){
        try {
            $pending = SomaLoan::orderBy('created_at','DESC')->where('status','pending')->get();
            $approved = SomaLoan::orderBy('created_at','DESC')->where('status','approved')->get();
            $denied = SomaLoan::orderBy('created_at','DESC')->where('status','declined')->get();
            $held = SomaLoan::orderBy('created_at','DESC')->where('status','held')->get();
            $late = SomaLoan::orderBy('created_at','DESC')->where('status','late')->get();
            return view('soma.index',['pending'=>$pending,'approved'=>$approved,'denied'=>$denied,'hold'=>$held,'late'=>$late])->with('title','Soma Loans');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
        
    }
    //get an individuals soma loans
    public function borrowerIndex($id){
        try {
            $borrower = User::findOrFail($id);
            $loans = $borrower->soma_loans()->orderBy('created_at','DESC')->get();
            $installments = $borrower->repayments()->where('status','pending')->orWhere('status','late')->get();
            return view('soma.borrower_index',['loans'=>$loans,'installments'=>$installments])->with('page',$borrower->fname.' '.$borrower->lname.' Soma Loans');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors($th->getMessage());
        }
        
    }

    //create a soma loan
    public function create(){
        try {
            $users = AuthenticationController::getUserById(session('user_id'));
            // dd($users[0]['id']);
            $districts = District::all();
            $user  = User::findOrFail($users[0]['id']);
    //todo:artisan console        
            // $identifications = Identification::all();
            // foreach ($identifications as $id) {
            //     $id_user = User::where('user_id',$id->Uuser_id)->first();
            //     if($id_user){
            //         // $id->user()->associate($id_user);
            //         // dd($id_user);
            //         $id->user_id = $id_user->id;
            //         $id->save();
            //     }
               
            // }
           
            $grades = Grade::all();
            $loan_categories = LoanCategory::where('loan_amount','>=',100000)->Where('loan_amount','<=',2000000)->get();
            // dd($user);
            return view('soma.create',['districts'=>$districts,'user'=>$user,'grades'=>$grades,'categories'=>$loan_categories])->with('page','Soma Loan Application');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    //store the soma loan
    public function store(Request $request){
        try {
            // $user = Auth::user();
            $users = AuthenticationController::getUserById(session('user_id'));
            $user = User::findOrFail($users[0]['id']);
            if($user){
                $outstanding_loans = $user->loans()->where('status',6)->orWhere('status',4)->count();
                $outstanding_soma = $user->soma_loans()->where('status',6)->orWhere('status',4)->count();
                $outstanding_biz = $user->biz_loans()->where('status',6)->orWhere('status',4)->count();
                if($outstanding_biz || $outstanding_loans || $outstanding_soma){
                    return redirect()->back()->withErrors(['Errors'=>'you still have an outstanding loan']);
                }
                if($user->alliances()->count() < 2){
                    return redirect()->back()->withErrors(['Errors'=>'You Dont Have enough Allianses to qualify For a loan,Add alliance and try again later']);
                }
                $student = new Student();
                $student->fname = $request->student_fname;
                $student->lname = $request->student_lname;
                $student->user()->associate($user);
                $student->gender = $request->student_gender;
                $student->class_name = $request->student_class;
                $student->dob = $request->student_dob;
                $student->phone = $request->student_phone;
                $student->school_name = $request->student_school_name;
                $student->school_id_card = $request->student_id_card;
                $student->sch_admission_num = $request->sch_admin_num;
                if($request->radio_receipt_report == 'report'){
                    $student->school_report = $request->receipt_report;
                }else{
                    $student->school_receipt = $request->receipt_report;

                }
                
                $student->class_name = $request->student_class;
                $student->save();
                if($student){
                    $hm_district = District::find($request->hm_district);
                    $headteacher = new Headteacher();
                    $headteacher->fname = $request->hm_fname;
                    $headteacher->lname = $request->hm_lname;
                    $headteacher->school_name = $request->sch_name;
                    $headteacher->phone = $request->hm_contact;
                    $headteacher->district()->associate($hm_district);
                    $headteacher->student()->associate($student);
                    $headteacher->save();

                }
                // dd(typeof $request->chk_parent_applicant);
                if($request->chk_parent_applicant == true){
                    $parent = new ParentDetail();
                    $parent->name = $user->name;
                    $parent->phone = $user->telephone;
                    $parent->id_photo = $user->identification->front_face;
                    $parent->save();
                }else{
                    $parent = new ParentDetail();
                    $parent->name = $request->parent_name;
                    $parent->phone = $request->nok_contact;
                    $parent->id_photo = $request->parent_id_card;
                    $parent->save();
                }
                $parent->students()->attach($student->id,['relationship'=>$request->relationship]);
                $loan_category = LoanCategory::find($request->loan_category);
                $soma = new SomaLoan();
                $soma->user()->associate($user);
                $soma->student()->associate($student);
                $soma->loanCategory()->associate($loan_category);
                $soma->principal = $loan_category->loan_amount;
                $soma->interest_rate = $loan_category->interest_rate;
                $soma->payment_period = $loan_category->loan_period;
                $soma -> installments = $loan_category->installments;
                $soma->save();
                switch (strlen($soma->id)) {
                    case 1:
                        $soma->SLN_id = 'SLN_'.$soma->id.'00'.rand(111,999);
                        break;
                    case 2:
                        $soma->SLN_id = 'SLN_'.$soma->id.'0'.rand(111,999);
                        break;
                    case 3:
                        $soma->SLN_id = 'SLN_'.$soma->id.rand(111,999);
                        break;
                        
                    default:
                        $soma->SLN_id = 'SLN_'.$soma->id.rand(1111,9999);
                        break;
                }
                $soma->save();
                // dd($soma);
                if($soma){
                    
                    // $message = 'Dear '. $user->name .', Your '.$soma->SLN_id .' of '.$soma->principal .' has been requested pending approval';
                    
                    // $smsStatus = $this->sendSMS($message,$user->telephone);
                }
                return redirect()->route('soma.show');
            }
            return redirect()->back()->withErrors('you have to loggin to do this operation');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function approveSomaLoan(Request $request,$id){
        try {
            $users = AuthenticationController::getUserById(session('user_id'));
            // $user = Auth::user();
            $user = User::findOrFail($users[0]['id']);
            if($user && $user->role == 'Admin'){
                $soma = SomaLoan::findOrFail($id);
                $soma->approved_at = Carbon::now();
                $soma->due_date = Carbon::now()->addDays($soma->payment_period);                
                $soma->status = 'approved';
                $soma->interest = $soma->principal /$soma->interest_rate;
                $soma->payment_amount = $soma->interest + $soma->principal + $soma->loan_category->processing_fees;
                $soma->save();
                $installment_amount = $soma->payment_amount /$soma->installments;
                $installment_period = $soma->payment_period/$soma->installments;
                for ($i=1; $i <= $soma->installments ; $i++) { 
                   $installment = new Repayment();
                   $installment->repaymentable_id = $soma->id;
                   $installment->repaymentable_type = 'App/Models/SomaLoan';
                   $installment->amount = $installment_amount;
                   $last_date = $soma->latestRepayment->due_date;
                   $installment->due_date = $last_date ? $last_date->addDays($installment_period) : $soma->approved_at->addDays($installment_period);
                   $installment->save();

                }
                if($soma){
                    $message = 'Dear '. $user->name .', Your '.$soma->SLN_id .' of '.$soma->principal .' has been '.$soma->status .' and credited to your appnomu account';
                    
                    $smsStatus = $this->sendSMS($message,$user->telephone);
                    for(;$smsStatus != 'success';){
                        $smsStatus = $this->sendSMS($message,$user->telephone);
                    }
                }

                return redirect()->back();

            }
            return redirect()->back()->withErrors('you are unathorised to do this operation!');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function payInstallment($id){

    }

    //showa particular soma loan
    public function show($id){
        try {
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sendSMS($message,$phone){
        try {
            if(substr($phone,0,1)=='0'){
                //0754024461
                $tel = '+256'.substr($phone,1,9);
            }elseif (substr($phone,0,1)=='+') {
                # code...
                $tel = $phone;
            }
    
            $sms = new AfricasTalking(env('AFRICASTALKING_USERNAME') ,env('AFRICASTALKING_APIKEY'));
            $result = $sms->sms()->send(['to'=>$tel,'message'=>$message,'from'=>'Appnomu']);
            return $result['status'];
        } catch (\Throwable $th) {
            //throw $th;
            return 'failed';
        }
        

    }

}
