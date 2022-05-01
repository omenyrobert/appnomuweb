<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $table = 'userloans';
    public function loan_product(){
        return $this->belongsTo(LoanProduct::class,'loan_product_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }
    public function loanCategory(){
        return $this->belongsTo(loanCategory::class,'loan_category_id','id');
    }

    public function repayments(){
        return $this->morphMany(Repayment::class,'repaymentable');
    }

    

}
