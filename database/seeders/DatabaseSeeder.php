<?php

namespace Database\Seeders;

use App\Models\ProjectImage;
use App\Models\Style;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CitySeeder::class,
            ProfesionalTypeSeeder::class,
            BudgetSeeder::class,
            StyleSeeder::class,
            RoomSeeder::class,
            ProfessionalSeeder::class,
            ProjectSeeder::class,
            ProjectImageSeeder::class,
            RefOrderStatusSeeder::class,
            RefProfessionalStatusSeeder::class
            // OrderSeeder::class,
            // RatingSeeder::class
        ]);
    }
}
