<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public function businesses(){
        $this->hasMany(Business::class);
    }

    public function headteachers(){
        $this->hasMany(Headteacher::class);
    }
    public function region(){
        $this->belongsTo(Region::class);
    }
}
