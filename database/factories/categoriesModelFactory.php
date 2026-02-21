<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\categoriesModel>
 */
class categoriesModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'  => 'test',
            'image' => 'https://images.squarespace-cdn.com/content/v1/598bddd68419c2a2f24c6bf7/1582660833582-9HP8SXIRG9I07ZEL70O9/IMG_1749.JPG',
          
        ];
    }
}
