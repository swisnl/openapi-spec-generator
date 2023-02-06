<?php
/*
 * Copyright 2021 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Post;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\User;

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
            'author_id' => User::factory(),
            'content' => $this->faker->text,
            'published_at' => $this->faker->dateTimeThisMonth,
            'slug' => $this->faker->unique()->slug,
            'synopsis' => $this->faker->sentence,
            'title' => $this->faker->words(3, true),
        ];
    }
}
