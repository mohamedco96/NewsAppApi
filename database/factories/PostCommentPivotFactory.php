<?php

namespace Database\Factories;

use App\Models\PostCommentPivot;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentPivotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostCommentPivot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment_id' => $this->faker->numberBetween(1,50),
            'post_id' => $this->faker->numberBetween(1,100),
        ];
    }
}
