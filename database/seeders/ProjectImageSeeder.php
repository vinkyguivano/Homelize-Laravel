<?php

namespace Database\Seeders;

use App\Models\ProjectImage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_images')->insert([
            [
                'project_id' => '1',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/eksterior.jpg',
                'description' => 'Eksterior rumah dengan gaya modern',
                'style_id' => '2',
                'room_id' => '8',
                'budget_id' => '3',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '1',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017727/homelize/room/ruang-keluarga.jpg',
                'description' => 'ruang keluarga dengan gaya rumah minimalis',
                'style_id' => '1',
                'room_id' => '3',
                'budget_id' => '1',
                'minimum_budget' => '3000000',
                'maximum_budget' => '4000000',    
            ],
            [
                'project_id' => '1',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017727/homelize/room/dapur.jpg',
                'description' => 'ruang dapur dengan gaya modern',
                'style_id' => '2',
                'room_id' => '1',
                'budget_id' => '2',
                'minimum_budget' => '300000000',
                'maximum_budget' => '400000000',    
            ],
            [
                'project_id' => '1',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017726/homelize/room/ruang-makan.jpg',
                'description' => 'ruang makan dengan gaya modern',
                'style_id' => '4',
                'room_id' => '2',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '2',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642068551/Example/exterior-elevation-pratibha-and-associates-img_c5111a860ce65dd5_4-9714-1-b0c36f1.jpg',
                'description' => 'Eksterior rumah dengan gaya modern',
                'style_id' => '2',
                'room_id' => '8',
                'budget_id' => '3',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '2',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kolam-renang.jpg',
                'description' => 'kolam berenang dengan gaya modern',
                'style_id' => '2',
                'room_id' => '10',
                'budget_id' => '2',
                'minimum_budget' => '10000000000',
                'maximum_budget' => '20000000000',    
            ],
            [
                'project_id' => '2',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-tidur.jpg',
                'description' => 'kamar tidur dengan gaya modern',
                'style_id' => '6',
                'room_id' => '4',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '3',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017727/homelize/room/dapur.jpg',
                'description' => 'dapur dengan gaya minimalis',
                'style_id' => '1',
                'room_id' => '1',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '3',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-mandi.jpg',
                'description' => 'kamar mandi dengan gaya minimalis',
                'style_id' => '1',
                'room_id' => '5',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '4',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-tidur.jpg',
                'description' => 'kamar tidur dengan gaya modern',
                'style_id' => '6',
                'room_id' => '4',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '4',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-mandi.jpg',
                'description' => 'kamar mandi dengan gaya skandinavian',
                'style_id' => '7',
                'room_id' => '5',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '5',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1642068650/Example/Desain-Interior-Kamar-Tidur-Utama.jpg',
                'description' => 'kamar tidur dengan gaya modern',
                'style_id' => '6',
                'room_id' => '4',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '5',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/kamar-mandi.jpg',
                'description' => 'kamar mandi dengan gaya kontemporer',
                'style_id' => '4',
                'room_id' => '5',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
            [
                'project_id' => '5',
                'image_path' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017725/homelize/room/taman.jpg',
                'description' => 'taman dengan gaya industrial',
                'style_id' => '9',
                'room_id' => '9',
                'budget_id' => '2',
                'minimum_budget' => '500000000',
                'maximum_budget' => '600000000',    
            ],
        ]);
    }
}
