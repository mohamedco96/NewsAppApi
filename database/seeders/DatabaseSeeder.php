<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Author::factory(30)->create();
        // \App\Models\Post::factory(100)->create();
        // \App\Models\PostCategoryPivot::factory(50)->create();
        \App\Models\PostCommentPivot::factory(50)->create();
        // \App\Models\Comment::factory(50)->create();




    }
}
