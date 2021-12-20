<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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
            'location' => DB::raw("(GeomFromText('POINT(".$this->faker->latitude()." ".$this->faker->longitude().")'))"),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password,
            'account_number' => $this->faker->randomNumber(9, true),
            'professional_type_id' => mt_rand(1,3),
            'city_id' => mt_rand(1,38),
            'image_path' => $this->faker->imageUrl(640, 480, 'Professional', true),
            'thumbnail' => 'https://res.cloudinary.com/dwrorg2la/image/upload/v1639017724/homelize/room/eksterior.jpg',
            'description' => $this->faker->paragraph()
        ];
    }
}
