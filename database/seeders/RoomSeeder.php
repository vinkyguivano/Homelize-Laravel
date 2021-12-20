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
                'name' => 'Dapur',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017727/homelize/room/dapur.jpg'
            ],
            [
                'name' => 'Ruang Makan',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017726/homelize/room/ruang-makan.jpg'
            ],
            [
                'name' => 'Ruang Keluarga',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017727/homelize/room/ruang-keluarga.jpg'
            ],
            [
                'name' => 'Kamar Tidur',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-tidur.jpg'
            ],
            [
                'name' => 'Kamar Mandi',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-mandi.jpg'
            ],
            [
                'name' => 'Ruang Kerja',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017725/homelize/room/ruang-kerja.jpg'
            ],
            [
                'name' => 'Laundry',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/laundry.jpg'
            ],
            [
                'name' => 'Eksterior',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/eksterior.jpg'
            ],
            [
                'name' => 'Taman',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017725/homelize/room/taman.jpg'
            ],
            [
                'name' => 'Kolam renang',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kolam-renang.jpg'
            ],
        ]);
    }
}
