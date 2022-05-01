<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function biz_loan(){
        return $this->hasMany(BusinessLoan::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }
}
