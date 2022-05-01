<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Save;
use App\Models\SavingCategory;
use App\Models\SavingSubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function clientDashboard(){
        try {
            $user = User::find(Auth::id());
            if($user ){
                if($user->account){

                    $account = $user->account;
                }else{
                    $account = new Account();
                    $account->user()->associate($user);
                    $account->Uuser_id = $user->user_id;
                    $account->save();

                }
                $loans = $user->loans()->orderBy('created_at','desc')->paginate(10);
                $withdraws = $user->withdraws()->orderBy('created_at','desc')->paginate(10);
                $savings = $user->savings()->orderBy('created_at','desc')->paginate(10);
                $identification = $user->identification;
                return view('view.index',['user'=>$user,'loans'=>$loans,'withdraws'=>$withdraws,'savings'=>$savings,'identification'=>$identification])
                    ->with('page','dashboard');
            }
            return redirect()->route('login')->withErrors('errors','please login to access your account');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function adminDashboard(){
        try {
            $user= User::find(Auth::id());
            if($user && $user->role == 'admin'){
                $users = User::all();
                $categories = LoanCategory::withCount('loans')->get();
                $saving_categories = SavingCategory::all();
                $saving_sub_cats = SavingSubCategory::all();
                $savings = Save::where('status',8)->sum('amount');
                $savings = Save::where('status',8)->count('amount');
                $Running_loans_count = Loan::where('status','06')->count();
                $Running_loans = Loan::where('status','06')->sum('loan_amount');
                $loan_requests =Loan::where('status','05')->sum('loan_amount');
                $loan_requests =Loan::where('status','05')->count();
                $identification = $user->identification ? $user->identification : "";
                return view('view.index',['users'=>$users,'user'=>$user,
                'loans_cate'=>$categories,'savingcategories'=>$saving_categories,
                'Running_loans'=>$Running_loans,'loan_requests'=>$loan_requests,
                'savings'=>$savings,'user_idnt'=>$identification,''])
                ->with('page','dashboard');
            }
            return redirect()->route('login')->withErrors('errors','please login to access your account');
        } catch (\Throwable $th) {
            throw $th;
        }

    }

   public function dashboard(){
       
       if(Auth::check()){
        $user = Auth::user();
        if($user->role == 'admin'){
         return  redirect()->route('dashboard.admin');
        }
        return  redirect()->route('dashboard.client');
       }
   }



}