<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->truncate();
          //Creation client
          DB::table('clients')->insert([
            'Nom'=>'AMA',
        'Prenoms'=>'Ablam',
        'Sexe'=>'M',
        'Email'=>'ablam.semeglo@gmail.com',
        'Telephone'=>'+22891207494',
        'Adresse'=>'Ablogamé',
        'EntrepriseId'=>'1',
        
        ]);

        DB::table('clients')->insert([
            'Nom'=>'Ulrich',
        'Prenoms'=>'Basile',
        'Sexe'=>'M',
        'Email'=>'bas.sowadan@gmail.com',
        'Telephone'=>'+22890499511',
        'Adresse'=>'Nukafu',
        'EntrepriseId'=>'1',
        
        ]);
    }
}
