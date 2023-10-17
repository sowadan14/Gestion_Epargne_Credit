<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class EntrepriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('entreprises')->insert([
        'id'=>'1',
        'Libelle'=>'NORA SHOP',
        'Email'=>'arsemeglo@gmail.com',
        'Telephone'=>'+22891207494',
        'Adresse'=>'',
        'DateCreation'=>Carbon::now(),
        'SaveNumber'=>'1',
        'Categ'=>'1',
    ]);
    }
}
