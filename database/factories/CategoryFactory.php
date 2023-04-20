<?php

namespace Database\Factories;

use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model=Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'code' => $this->faker->code,
            'slug' => $this->faker->slug,
            'description' => $this->faker->description,
            'is_disabled' =>0,
            'parent_id' =>null
        ];
    }
}
