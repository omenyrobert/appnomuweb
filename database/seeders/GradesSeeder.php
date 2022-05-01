<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $grades = [['id'=>1,'name'=>'Nursery'],
        ['id'=>2,'name'=>'Primary 1'],
        ['id'=>3,'name'=>'Primary 2'],
        ['id'=>4,'name'=>'Primary 3'],
        ['id'=>5,'name'=>'Primary 4'],
        ['id'=>6,'name'=>'Primary 5'],
        ['id'=>7,'name'=>'Primary 6'],
        ['id'=>8,'name'=>'Primary 7'],
        ['id'=>9,'name'=>'Senior 1'],
        ['id'=>10,'name'=>'Senior 2'],
        ['id'=>11,'name'=>'Senior 3'],
        ['id'=>12,'name'=>'Senior 4'],
        ['id'=>13,'name'=>'Senior 5'],
        ['id'=>14,'name'=>'Senior 6']];

        DB::table('grades')->insert($grades);
    }
}
