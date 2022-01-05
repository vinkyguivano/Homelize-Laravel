<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('ref_order_statues')->insert([
            [ 'name' => 'MENUNGGU PEMBAYARAN' ],
            [ 'name' => 'KONFIRMASI PEMBAYARAN' ],
            [ 'name' => 'PROSES PENGERJAAN' ],
            [ 'name' => 'SELESAI'],
            [ 'name' => 'BATAL']
        ]);
    }
}
