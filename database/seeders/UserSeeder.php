<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use GeminiLabs\SiteReviews\Helpers\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $value='12345678';
        // $user = User::create(['name'=>'admin','email'=>'admin@appnoomu.com','password'=>Hash::make($value)]);
        $user = DB::table('sysusers')->insert([
            'id'=>rand(500,1000),
            'name'=> "Dibossman",
            'user_id'=> rand(111111,999999),
            'telephone'=> '0778991059',
            'email'=>'isaacomega16@gmail.com',
            'password'=> bcrypt('1234567890'),
            'sms_token'=>rand(111111,999999),
            'sms_verified_at' => rand(1111111,99999999),
            'email_verified_at'=> \Carbon\Carbon::now(),
            'created_at'=>\Carbon\Carbon::now(),
            'role' => 'admin',
            'verify_token'=>rand(111111,999999),
            'NIN'=>'CM98'.rand(0,9999).'ZXG'.rand(0,9999),
            'card_no'=>rand(10000,99999)
        ]);


    }
}
