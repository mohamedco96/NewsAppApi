<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'tittle' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'author' => $this->faker->numberBetween(1,30),
            'content'=> $this->faker->paragraph,
            'image' => $this->faker->imageUrl($width = 300, $height = 300, 'sports', true, 'Faker'),
            'vote_up' => $this->faker->numberBetween(1,100),
            'vote_down' => $this->faker->numberBetween(1,100),
        ];
    }
}
