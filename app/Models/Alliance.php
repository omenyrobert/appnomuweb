<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    use HasFactory;
    protected $table = 'alliases';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
