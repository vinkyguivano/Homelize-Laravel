<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => mt_rand(1,150),
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'project_id' => mt_rand(1,150),
            'style_id' => mt_rand(1,10),
            'budget_id' => mt_rand(1,3)
        ];
    }
}
