<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class PosteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('postes')->truncate();
        //Creation des users
        DB::table('postes')->insert([
           'Libelle'=>'Ditecteur général',
           'DateCreation'=>Carbon::now(),
       ]);

       DB::table('postes')->truncate();
       //Creation des users
       DB::table('postes')->insert([
          'Libelle'=>'Ditecteur Adjoint',
          'DateCreation'=>Carbon::now(),
      ]);

      DB::table('postes')->truncate();
      //Creation des users
      DB::table('postes')->insert([
         'Libelle'=>'Secrétaire',
         'DateCreation'=>Carbon::now(),
     ]);
    }
}
