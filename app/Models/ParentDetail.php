<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    
    use HasFactory;
    protected $table = 'parents';
    public function students(){
        return $this->belongsToMany(Student::class,'parent_student','parent_id','student_id')
            ->withPivot('relationship')->withTimestamps();
    }
}
