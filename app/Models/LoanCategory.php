<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    use HasFactory;
    protected $table = 'loanchart';

    public function soma_loans(){
        return $this->hasMany(SomaLoan::class,'loan_category_id','id');
    } 

    public function biz_loans(){
        return $this->hasMany(BusinessLoan::class,'loan_category_id','id');
    } 

    public function loans(){
        return $this->hasMany(Loan::class,'loan_category_id','id');
    }
}
