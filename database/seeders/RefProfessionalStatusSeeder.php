<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefProfessionalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ref_professional_statues')->insert([
            ['name' => 'PENDING'],
            ['name' => 'ACTIVE'],
            ['name' => 'INACTIVE']
        ]);
    }
}
