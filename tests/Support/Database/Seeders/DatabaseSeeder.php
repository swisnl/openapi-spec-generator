<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Comment;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Image;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Post;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Tag;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Video;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Post::factory(10)->create();
        Comment::factory(10)->create();
        Image::factory(10)->create();
        Video::factory(10)->create();
        Tag::factory(10)->create();
    }
}
