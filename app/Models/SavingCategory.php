<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingCategory extends Model
{
    use HasFactory;
    protected $table = 'savingcategories';

    public function savingSubCategories(){
        return $this->hasMany(SavingSubCategory::class,'cate_id','cate_id');
    }

    public function savings(){
        return $this->hasManyThrough(Save::class,SavingSubCategory::class);
    }
}
