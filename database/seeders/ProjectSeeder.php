<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('projects')->insert([
           [
               'name' => 'Rumah keluarga A',
               'description' => 'Projek membangun rumah minimalis keluarga A',
               'year' => '2020',
               'professional_id' => '1'
           ],
           [
                'name' => 'Rumah keluarga B',
                'description' => 'Projek membangun rumah modern keluarga B',
                'year' => '2021',
                'professional_id' => '1'
           ],
           [
                'name' => 'Projek membangun rumah keluarga C',
                'description' => 'Projek membangun rumah keluarga C di jakarta kawasan thamrin',
                'year' => '2021',
                'professional_id' => '2'
           ],   
           [
                'name' => 'Projek desain interior rumah keluarga A',
                'description' => 'Projek membangun desain interior minimalis rumah keluarga A',
                'year' => '2021',
                'professional_id' => '3'
           ],
           [
                'name' => 'Projek desain interior rumah keluarga B',
                'description' => 'Projek membangun desain interior minimalis rumah keluarga B',
                'year' => '2021',
                'professional_id' => '3'
           ],
           [
                'name' => 'Projek Rumah Minimalis A',
                'description' => 'Projek mendesain rumah minimalis 2 lantai',
                'year' => '2016',
                'professional_id' => '4'
           ],
           [
               'name' => 'Projek Rumah Modern B',
               'description' => 'Projek mendesain rumah modern 2 lantain',
               'year' => '2018',
               'professional_id' => '4'
           ],
           [
               'name' => 'Projek Rumah Klasik C',
               'description' => 'Projek mendesain rumah klasik 2 lantai',
               'year' => '2020',
               'professional_id' => '4'
           ],
           [
               'name' => 'Renovasi Kamar tidur rumah A',
               'description' => 'Projek merenovasi rumah A',
               'year' => '2015',
               'professional_id' => '5'
           ],
           [
               'name' => 'Renovasi dapur rumah B',
               'description' => 'Projek merenovasi rumah B',
               'year' => '2016',
               'professional_id' => '5'
           ],
           [
               'name' => 'Projek Rumah Pak Joko',
               'description' => 'Projek mendesain rumah dengan gaya skandinavian',
               'year' => '2018',
               'professional_id' => '6'
           ],
           [
               'name' => 'Projek Rumah Bu Siti',
               'description' => 'Projek mendesain rumah dengan gaya kontemporer',
               'year' => '2019',
               'professional_id' => '6'
          ]
        ]);
    }
}
