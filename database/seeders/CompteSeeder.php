<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class CompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comptes')->truncate();
        //Creation compte
        DB::table('comptes')->insert([
        'Solde'=>'2000000',
        'Libelle'=>'Caisse principale',
        'DateCreation'=>Carbon::now(),
        'EntrepriseId'=>'1',
        'SaveNumber'=>'1',
        // 'Anne/xeID'=>'1',
   ]);
    }
}
