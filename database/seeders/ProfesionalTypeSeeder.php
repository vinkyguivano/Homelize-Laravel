<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesionalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //3
        DB::table('professional_types')->insert([
            [
                'name' =>  'Arsitek'
            ],
            [
                'name' => 'Desain Interior'
            ],
            [
                'name' => 'Arsitek & Desain Interior'
            ]
        ]);
    }
}
