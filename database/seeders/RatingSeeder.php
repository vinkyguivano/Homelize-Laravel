<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rating::factory()
            ->count(50)
            ->state(new Sequence( fn($sequence) => ['order_id' => $sequence->index+1]))
            ->create();
    }
}
