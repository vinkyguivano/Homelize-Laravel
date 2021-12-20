<?php

namespace Database\Seeders;

use App\Models\ProjectImage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProjectImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectImage::factory()
        ->count(500)
        ->state(new Sequence(
            [
                'room_id' => 1, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709800/homelize/design/dapur.jpg'
            ],
            [
                'room_id' => 2, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709802/homelize/design/ruang-makan-minimalis.jpg'
            ],
            [
                'room_id' => 3, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709803/homelize/design/ruang-keluarga.jpg'
            ],
            [
                'room_id' => 4, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709803/homelize/design/kamar-tidur.jpg'
            ],
            [
                'room_id' => 5, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709800/homelize/design/kamar-mandi.jpg'
            ],
            [
                'room_id' => 6, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709801/homelize/design/ruang-kerja.jpg'
            ],
            [
                'room_id' => 7, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709800/homelize/design/ruang-laundry.jpg'
            ],
            [
                'room_id' => 8, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709802/homelize/design/desain-eksterior.png'
            ],
            [
                'room_id' => 9, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709802/homelize/design/kolam-renang.jpg'
            ],
            [
                'room_id' => 10, 
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1638709802/homelize/design/taman-belakang.jpg'
            ],
        ))
        ->create();
    }
}
