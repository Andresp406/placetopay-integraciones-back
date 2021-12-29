<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'img' => $this->faker->imageUrl(300,300),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'status' => $this->faker->randomElement(['CREATED', 'APPROVED', 'REJECTED']),
        ];
    }
}
