<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SexeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sexes')->insert([
            'text'=>'Masculin',
            'value'=>'M',
        ]);

        DB::table('sexes')->insert([
            'text'=>'Féminin',
            'value'=>'F',
        ]);
    }
    }
}
