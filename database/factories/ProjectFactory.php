<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'professional_id' => mt_rand(1,100),
            'year' => mt_rand(1980, 2025)
        ];
    }
}
