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
        ]);
    }
}
