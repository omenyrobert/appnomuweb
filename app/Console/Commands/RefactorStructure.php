<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Identification;
use App\Models\Alliance;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\LoanPayment;
use App\Models\Repayment;
use App\Models\Save;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RefactorStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command refactors the database to match the structure of the laravel logic';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('updating identifications table');
        $ident = $this->updateIdentifications();
        if($ident == 'success'){
            $this->info('finished updating identifications table');
        }else{
            $this->error($ident);
        }

        $this->info('updating alliancess table');
        $res = $this->updateAlliances();
        if($res == 'success'){
            $this->info('finished updating alliances table');
        }else{
            $this->error($res);
        }

        $this->info('updating accounts table');
        $res = $this->updateAccounts();
        if($res == 'success'){
            $this->info('finished updating accounts table');
        }else{
            $this->error($res);
        }

        $this->info('updating loans table');
        $res = $this->updateLoans();
        if($res == 'success'){
            $this->info('finished updating loans table');
        }else{
            $this->error($res);
        }

        $this->info('updating savings table');
        $res = $this->updateSavings();
        if($res == 'success'){
            $this->info('finished updating savings table');
        }else{
            $this->error($res);
        }

        $this->info('updating repayments table');
        $res = $this->repayments();
        if($res == 'success'){
            $this->info('finished updating repayments table');
        }else{
            $this->error($res);
        }


        return 0;
    }

    private function updateIdentifications()
    {
        try {
            $identifications = Identification::all();
            foreach($identifications as $identification){
                $user = User::where('user_id',$identification->Uuser_id)->first();
                if($user){
                    $identification->user()->associate($user);
                    $identification->save();
                }else{
                    $identification->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
        
    }

    private function updateAlliances(){
        try {
            $alliances = Alliance::all();
            foreach($alliances as $alliance){
                $user = User::where('user_id',$alliance->refferer)->first();
                if ($user) {
                    $alliance->user()->associate($user);
                    $alliance->save();
                }else{
                    $alliance->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function updateAccounts(){
        try {
            $accounts = Account::all();
            foreach($accounts as $account){
                $user = User::where('user_id',$account->Uuser_id)->first();
                if ($user) {
                    $account->user()->associate($user);
                    $account->save();
                }else{
                    $account->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function updateLoans(){
        try {
            $loans = Loan::all();
            foreach($loans as $loan){
                $user = User::where('user_id',$loan->Uuser_id)->first();
                $cat = LoanCategory::where('loan_amount',$loan->loan_amount)->first();
                if ($user) {
                    $loan->user()->associate($user);
                    $loan->loanCategory()->associate($cat);
                    $loan->account()->associate($user->account);
                    $loan->save();
                }else{
                    $loan->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function updateSavings(){
        try {
            $savings = Save::all();
            foreach($savings as $saving){
                $user = User::where('user_id',$saving->Uuser_id)->first();
                if ($user) {
                    $saving->user()->associate($user);
                    $saving->account()->associate($user->account);
                    $saving->save();
                }else{
                    $saving->delete();
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    private function repayments(){
        try {
            $installments = LoanPayment::all();
            foreach($installments as $installment){
                $this->info('checking installments');
                $user = User::where('user_id',$installment->Uuser_id)->first();
                if ($user) {
                    $this->info('user found');
                    $this->info($installment->ULoan_Id);
                    $loan = Loan::where('ULoan_Id',$installment->ULoan_Id)->first();
                    if($loan){
                        $this->info('loan found');
                        $payment = new Repayment();
                        $payment->user()->associate($user);
                        $payment->amount = $installment->Amount_Paid;
                        $payment->amount_paid = $installment->Amount_Paid;
                        $payment->repaymentable()->associate($loan);
                        $payment->created_at = $installment->created_at;
                        $payment->updated_at = $installment->updated_at;
                        $payment->due_date = $installment->updated_at ? $installment->updated_at : Carbon::now();
                        switch ($installment->status) {
                            case 7:
                                $payment->status = 'paid';
                                break;
                            case 6:
                                $payment->status = 'approved';
                                break;
                            case 3:
                                $payment->status = 'denied';
                                break;
                            case 4:
                                $payment->status = 'overdue';
                                break;
                            case 2:
                                $payment->status = 'waiting';
                                break;
                                    
                            
                           
                        }
                        $payment->save();
                        if($payment){
                            $this->info('payment saved');
                        }else{
                            $this->error('payment not saved');
                        }
                        
                    }else{
                        // return 'loan '.$installment->ULoan_id.' not found';
                        $this->error('loan not found');
                        continue;
                    }
                    
                }else{
                    // $installment->delete();
                    // return 'user '.$installment->Uuser_id.' not found';
                    $this->error('user not found');
                    continue;                    
                }
            }
            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            // return $th->getMessage();
            $this->error($th->getMessage());
        }
    }

    


}
