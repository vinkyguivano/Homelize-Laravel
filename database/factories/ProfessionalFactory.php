<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->e164PhoneNumber(),
            'address' => $this->faker->address(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password,
            'account_number' => $this->faker->randomNumber(9, true),
            'professional_type_id' => mt_rand(1,3),
            'city_id' => mt_rand(1,38),
            'image_path' => $this->faker->imageUrl(640, 480, 'Professional', true)
        ];
    }
}
