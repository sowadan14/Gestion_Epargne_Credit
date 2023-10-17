<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class CreateParametreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parametres')->truncate();
        
        //Creation emballage
        DB::table('parametres')->insert([
           'Taille'=>'14',
           'Police'=>'Cambria',
           'ColorEntete'=>'rgb(3, 39, 60)',
           'EmailNotification'=>'',
           'PasswordNotification'=>'0',
           'EntrepriseId'=>'1',
           'DateCreation'=>Carbon::now(),
           ]);
    }
}
