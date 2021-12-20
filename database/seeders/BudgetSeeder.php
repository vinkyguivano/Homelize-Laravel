<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //3
        DB::table('budgets')->insert([
            [
                'name' => 'Low',
                'nominal' => 'Rp. 1 JT - Rp 100 JT'
            ],
            [
                'name' => 'Standarf',
                'nominal' => 'Rp. 100 JT - Rp 500 JT'
            ],
            [
                'name' => 'Premium',
                'nominal' => 'Rp 500 JT - Rp 2M'
            ],
            [
                'name' => 'Luxury',
                'nominal' => 'Rp 2M lebih'
            ],

        ]);
    }
}
