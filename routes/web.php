<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SomaLoanController;
use App\Http\Controllers\AllianceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
$auth = new AuthenticationController();
view()->share('auth',$auth);

Route::get('/dashboard',[HomeController::class,'dashboard'])->name('dashboard');
Route::get('/dashboard/admin',[HomeController::class,'adminDashboard'])->name('dashboard.admin');
Route::get('/dashboard/client',[HomeController::class,'clientDashboard'])->name('dashboard.client');
Route::get('/login', function () {
    view()->share('page','Login');
    return view('view.login');
})->name('login');

Route::get('/register', function () {
    view()->share('page','Register');
    return view('view.register');
});



Route::get('/forgot', function () {
    view()->share('page','Forgot Password');
    return view('view.forgot');
});

Route::get('/reset-password', function () {
    view()->share('page','New Password');
    return view('view.new_pass');
})->name('reset_pass');

Route::get('/tables', function () {
    view()->share('page','Tables');
    return view('view.tables');
});

Route::get('/savings', function () {
    view()->share('page','My Savings');
    return view('view.all-savings');
})->name('all_savings');

Route::get('/forms', function () {
    view()->share('page','Forms');
    return view('view.forms');
});

Route::get('/alliases', function () {
    view()->share('page','User Alliase');
    return view('view.alliases');
});

Route::get('/my-alliances', function () {
    view()->share('page','My Alliases');
    return view('view.my-alias');
});

Route::get('/profile', function () {
    view()->share('page','User Profile');
    return view('view.profile');
})->name('profile');


Route::get('/deposit', function () {
    view()->share('page','Make Deposit');
    return view('view.deposit');
});

Route::get('/withdraws', function () {
  	$sand = 0;
  	if($sand==1){
      	return redirect()->route('dashboard')->withErrors(['Errors'=>'System Withdraws Under Review Contact Support For More Information']);
    }else{
        view()->share('page','Make Withdraw');
    	return view('view.withdraws');
    }
    
});

Route::get('/allSavings', function () {
    view()->share('page','All Savings');
    return view('view.alSavings');
});

Route::get('/make-article', function () {
    view()->share('page','Articles');
    return view('view.knowledgebase');
});

Route::get('/all-withdraws', function () {
    view()->share('page','All Withdraws');
    return view('view.all-withdraws');
});

Route::get('/loans', function () {
    view()->share('page','Loans');
    return view('view.loans');
});

Route::get('/my-refferals', function () {
    view()->share('page','My Refferals');
    return view('view.refferals');
});

Route::get('/users', function () {
    view()->share('page','All Users');
    return view('view.users');
});

Route::get('/loan-chart', function () {
    view()->share('page','Loan Categories');
    return view('view.loan-categories');
});

Route::get('/user-loans', function () {
    view()->share('page','User Loans');
    return view('view.user-loans');
});

Route::get('/loan-installments', function () {
    view()->share('page','My Loan Installements');
    return view('view.loan-instals');
})->name('loan-installments');

Route::get('/saving-categories', function () {
    
    view()->share('page','Savings Categories');
    return view('view.saving-categories');
});

Route::get('/sub-categories', function () {
    view()->share('page','Savings Sub Categories');
    return view('view.sub-cate');
});

Route::get('/general', function () {
    view()->share('page','Savings Sub Categories');
    return view('view.general');
});

Route::get('/api-settings', function () {
    view()->share('page','Savings Sub Categories');
    return view('view.api-settings');
});

Route::get('/verify', function () {
    if (isset($_GET['e']) && isset($_GET['c'])) {
        $ret = AuthenticationController::verifyUserEmail($_GET['e'],$_GET['c']);
        if ($ret==1) {
            view()->share('page','Verify Phone Number');
            return view('view.verify-phone');
        }
    } else {
        view()->share('page','Verify Email');
        return view('view.verify');
    }
})->name('verify');

Route::get('/verify-phone', function () {
    view()->share('page','Verify phone');
    return view('view.verify-phone');
})->name('verify2');

Route::get('/logout', function () {
    $log = AuthenticationController::logout(session('user_id'));
    view()->share('page','Verify phone');
    return redirect()->route('login');
});

Route::get('/send_sms',function(){
    $sms = SmsController::sms_test(env('AFRICASTALKING_USERNAME'),env('AFRICASTALKING_APIKEY'));
    echo $sms;
});

Route::get('/approve', function () {
    if (isset($_GET['l'])) {
        # code...
        $ret= AuthenticationController::approveLoan($_GET['l']);
        if ($ret==1) {
            # code...
            return redirect()->back()->with('Success','Loan Has Been Approved Successfully');
        } else {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Loan Has Not Been Approved Successfully']);
        }
        
    }else {
        return redirect()->back()->withErrors(['Errors'=>'No Loans Selected']);
        
    }
});

Route::get('/deny', function () {
    if (isset($_GET['l'])) {
        # code...
        $ret= AuthenticationController::denyLoan($_GET['l']);
        if ($ret==1) {
            # code...
            return redirect()->back()->with('Success','Loan Has Been denied Successfully');
        } else {
            # code...
            return redirect()->back()->withErrors(['Errors'=>'Loan Has Not Been denied Successfully']);
        }
        
    }else {
        return redirect()->back()->withErrors(['Errors'=>'No Loans Selected']);
        
    }
});

Route::get('/sendSms', function () {
    view()->share('page','Send SMS');
    return view('view.sms-temp');
});

Route::get('/allSms', function () {
    view()->share('page','All SMSes');
    return view('view.sentSms');
});

Route::get('/knowledge-base', function () {
    view()->share('page','Knowledge Base Preview');
    return view('view.know');
})->name('man-k');

Route::get('/manage-knowledge', function () {
    if (isset($_GET['a']) && isset($_GET['s'])) {
        # code...
        $ret = AuthenticationController::manageknowlegebase($_GET['a'],$_GET['s']);
        return redirect()->route('man-k');
    } else {
        # code...
        return redirect()->back();
    }
    
    view()->share('page','Knowledge Base Preview');
    return view('view.know');
});

Route::get('/user-profile', function () {
    if (isset($_GET['u'])) {
        $user_idnt = AuthenticationController::getIdentifications($_GET['u']);
        $User = AuthenticationController::getUserById($_GET['u']);
        $user_accounts = AuthenticationController::getAccount($_GET['u']);
        view()->share('page',$User[0]['name']);
        view()->share('User',$User);
        view()->share('user_accounts',$user_accounts);
        view()->share('user_idnt',$user_idnt[0]);
        return view('view.view-profile');
    }
    return redirect()->back()->withErrors(['Errors'=>'No User Selected']);
});

Route::get('/loan-view', function () {
    if (isset($_GET['l'])) {
        $loan = AuthenticationController::getLoanId($_GET['l']);
        $installs = AuthenticationController::getloanInstallsbyloanId($_GET['l']);
        $User = AuthenticationController::getUserById($loan[0]['user_id'] );
        $user_accounts = AuthenticationController::getAccount($loan[0]['user_id']);
        view()->share('page',$User[0]['name']);
        view()->share('User',$User);
        view()->share('user_accounts',$user_accounts);
        view()->share('loan_installs',$installs );
        view()->share('loan',$loan);
        return view('view.viewLoan');
    }
    return redirect()->back()->withErrors(['Errors'=>'No Loan Selected']);
});

Route::post('/sendSms',[SmsController::class,'sendBulks']);
Route::post('/verification',[AuthenticationController::class,'verifyphone']);
Route::post('/save_saving_category',[AuthenticationController::class,'saveSavingCate']);
Route::post('/save_saving_sub_category',[AuthenticationController::class,'saveSavingSubCate']);
Route::post('/save_loan_category',[AuthenticationController::class,'saveLoanCategory']);
Route::post('/pay', [FlutterwaveController::class, 'initialize'])->name('pay');
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');
Route::post('/request_loan',[LoanController::class,'requestLoan'])->name('loan.request'); 
Route::post('/loginty',[LoginController::class,'authenticate'])->name('user.login');
Route::post('/register_user',[LoginController::class,'register'])->name('user.register');
// Route::post('/register_user',[AuthenticationController::class,'signUpUser']);
// Route::post('/loginty',[AuthenticationController::class,'loginUserx']);
// Route::post('/request_loan',[AuthenticationController::class,'requestLoan']);  //request loan
// Route::post('/save-alliaces',[AuthenticationController::class,'saveAliases']);
// Route::get('/dashboard', function () {
//     if (session('dashboard')==null) {
//         return redirect()->route('login');
//     }else {
//         view()->share('page','Dashboard');
//         return view('view.index');
//     }
// })->name('dashboard');
// Route::post('/confirm-alliaces',[AuthenticationController::class,'confirmAlliances']);
Route::post('/save-alliaces',[AllianceController::class,'store'])->name('alliance->store');
Route::post('/confirm-alliaces',[AllianceController::class,'confirmAlliance'])->name('alliance.confirm');
Route::post('/editProfile',[AuthenticationController::class,'editUserProfile']);
Route::post('/editPasswords',[AuthenticationController::class,'editPasswords']);
Route::post('/webhook/rave', [FlutterwaveController::class, 'webhook'])->name('webhook');
Route::get('/rave/loans_callback', [FlutterwaveController::class, 'loan_callback'])->name('callback_loans');
Route::post('/rave/withdraws', [FlutterwaveController::class, 'withdraw'])->name('withdraw');
Route::post('/reset_pass', [AuthenticationController::class, 'forgotPassword'])->name('reset');
Route::post('/new_pass', [AuthenticationController::class, 'resetPassword'])->name('new_pass');
Route::post('/frontface', [AuthenticationController::class, 'storeFrontFace']);
Route::post('/backface', [AuthenticationController::class, 'storebackFace']);
Route::post('/passport', [AuthenticationController::class, 'passport']);
Route::post('/article', [AuthenticationController::class, 'giveknowlegebase']);

Route::get('/pay_loan',function(){
    if (isset($_GET['l']) && isset($_GET['m'])) {
        if ($_GET['m']=='momo'){
            $pay = FlutterwaveController::pay_loan($_GET['l']);
            if ($pay==1) {
                return redirect()->back()->withErrors(['Errors'=>'We cant Find the installement']);
            }elseif ($pay==2) {
                return redirect()->back()->withErrors(['Errors'=>'This Installement Cannot be Paid for now']);
            }elseif ($pay==3) {
                return redirect()->back()->withErrors(['Errors'=>'Transaction Failed To Start']);
            }else{
                return redirect($pay);
            }
        }elseif ($_GET['m']=='dash') {
            # code...
            $pay = AuthenticationController::payLoanByDash($_GET['l']);
            if ($pay==1) {
                return redirect()->back()->withErrors(['Errors'=>'We cant Find the installement']);
            }elseif ($pay==2) {
                return redirect()->back()->withErrors(['Errors'=>'This Installement Cannot be Paid for now']);
            }elseif ($pay==3) {
                return redirect()->back()->withErrors(['Errors'=>'No User Account Found Contact Support Please']);
            } elseif ($pay==4) {
                return redirect()->back()->withErrors(['Errors'=>'Account Balance Too Low To Complete This Loan Payment Please Choose another Payment Format']);
            } elseif ($pay==6) {
                return redirect()->back()->withErrors(['Errors'=>'User Loan Error Contact Support Please']);
            } elseif ($pay==5) {
                return redirect()->back()->withErrors(['Errors'=>'Transaction Failed To Start Try Again Latter Or Contact Support']);

            } else{
                return redirect()->route('loan-installments')->with('Success','Loan Payment Succesful');            
            }
        }
        
    }else {
        return redirect()->back()->withErrors(['Errors'=>'Expected Parameters Missing ---- Broken URL']);
    }
});

//Website routes

Route::get('/', function () {
    return view('view.home');
});

Route::get('/about', function () {
    return view('view.about');
});

Route::get('/contact-us', function () {
    return view('view.contactus');
});

Route::get('/blogs', function () {
    return view('view.blog');
});

Route::get('/terms', function () {
    return view('view.terms');
});

Route::get('/Knowledge', function () {
    return view('view.knowledge');
});

Route::get('/knowl-base', function () {
    if (isset($_GET['id'])) {
        $article = AuthenticationController::getknowlegebaseParticular($_GET['id']);
        $paragrahs = AuthenticationController::getknowlegebaseParaphs($_GET['id']);
        view()->share('paras',$paragrahs);
        view()->share('articles',$article);
        
        return view('view.blog');
    }else {
        return redirect()->route('kb1');
    }
});

Route::get('/knbase', function () {
    if (isset($_GET['id'])) {
        $article = AuthenticationController::getknowlegebaseParticular($_GET['id']);
        $paragrahs = AuthenticationController::getknowlegebaseParaphs($_GET['id']);
        view()->share('paras',$paragrahs);
        view()->share('articles',$article);
        session(['art_id'=>$_GET['id']]);
        view()->share('page','Knowledge Base Paragraph Preview');
        return view('view.knParagh');
    }else {
        return redirect()->route('kb1');
    }
})->name('nan-k');

Route::get('/manage-para', function () {
    if (isset($_GET['a']) && isset($_GET['s'])) {
        # code...
        $ret = AuthenticationController::manageknowlegebaseParagraph($_GET['a'],$_GET['s']);
        return redirect()->back();
    } else {
        # code...
        return redirect()->back();
    }
});

Route::post('/paragraph',[AuthenticationController::class,'makeKnowledgeBaseParagrah']);

Route::get('/privacy', function () {
    return view('view.privacy');
});

Route::get('/faqs', function () {
    return view('view.faq');
});

Route::get('/commercials', function () {
    return view('view.commercial');
});

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::get('/messages-contact', function () {
    view()->share('page','Contact Page');
    return view('view.messages_conts');
});

Route::get('/paycheck', function () {
  	if(isset($_GET['p']) && isset($_GET['c'])){
      $pay = AuthenticationController::setpaycheck($_GET['p'],$_GET['c']);
      $message = $pay;
    }else{
    	$message = '';
    }
  	view()->share('message',$message);
    return view('view.paycheck');
});



Route::post('/contact_pagexc',[AuthenticationController::class,'sendMessagex']);
// soma loan routes
Route::get('loans/soma/index', [SomaLoanController::class,'index'])->name('soma.index');
Route::get('loans/soma/index/{id}', [SomaLoanController::class,'borrowerIndex'])->name('soma.borrower.index');
Route::post('loans/soma/new', [SomaLoanController::class,'store'])->name('soma.store');
Route::match(['get', 'put'], '/loans/soma/approve/{id}', [SomaLoanController::class,'approveSomaLoan'])->name('soma.approve');
Route::get('loans/soma/create', [SomaLoanController::class,'create'])->name('soma.create');
Route::get('loans/soma/dashboard', [SomaLoanController::class,'somaDashboard'])->name('soma.dashboard');