<?php

namespace Database\Factories;

use App\Models\PostCategoryPivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCategoryPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostCategoryPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => $this->faker->numberBetween(1,4),
            'post_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
