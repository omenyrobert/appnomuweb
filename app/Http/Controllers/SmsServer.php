<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
class SmsServer extends Controller
{
    public static function insta($username , $key){
        $AT = new AfricasTalking($username, $key);
        return $AT;
    }
}
