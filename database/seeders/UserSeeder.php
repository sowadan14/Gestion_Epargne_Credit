<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();
         //Creation des users
         DB::table('users')->insert([
            'EmployeId'=>'1',
            'Email'=>'arsemeglo@gmail.com',
            'SuperAdmin'=>'1',
            'password'=>Hash::make('123456789'),
            // 'AnnexeID'=>'1',
            'EntrepriseId'=>'1',
            'DateCreation'=>Carbon::now(),
            'SaveNumber'=>'1',
            'ImageUser'=>'',
        ]);


         //Creation des users
        DB::table('users')->insert([
            'EmployeId'=>'1',
            'Email'=>'basile14sowadan@gmail.com',
            'SuperAdmin'=>'1',
            'password'=>Hash::make('123456789'),
            // 'AnnexeID'=>'1',
            'EntrepriseId'=>'1',
            'DateCreation'=>Carbon::now(),
            'SaveNumber'=>'1',
            'ImageUser'=>'',
        ]);
    }
}
