<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    //belongs to a parent
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function headteachers()
    {
        return $this->hasMany(Headteacher::class);
    }

    public function parents(){
        return $this->belongsToMany(ParentDetail::class,'parent_student','student_id','parent_id')
            ->withPivot('relationship')->withTimestamps();
    }
}
