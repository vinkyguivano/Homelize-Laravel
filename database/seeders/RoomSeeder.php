<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //13
        DB::table('rooms')->insert([
            [
                'name' => 'Dapur'
            ],
            [
                'name' => 'Ruang Makan'
            ],
            [
                'name' => 'Ruang Keluarga'
            ],
            [
                'name' => 'Kamar Tidur'
            ],
            [
                'name' => 'Kamar Mandi'
            ],
            [
                'name' => 'Ruang Tamu'
            ],
            [
                'name' => 'Ruang Belajar'
            ],
            [
                'name' => 'Ruang Kerja'
            ],
            [
                'name' => 'Garasi'
            ],
            [
                'name' => 'Laundry'
            ],
            [
                'name' => 'Eksterior'
            ],
            [
                'name' => 'Kolam renang'
            ],
            [
                'name' => 'Taman belakang'
            ]
        ]);
    }
}
