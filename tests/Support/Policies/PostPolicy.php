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

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Policies;

use LaravelJsonApi\Core\Store\LazyRelation;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Post;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Tag;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\User;

class PostPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        if ($post->published_at) {
            return true;
        }

        return $this->author($user, $post);
    }

    public function viewAuthor(?User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    public function viewComments(?User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    public function viewMedia(?User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    public function viewTags(?User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    public function create(?User $user): bool
    {
        return (bool) $user;
    }

    public function update(?User $user, Post $post): bool
    {
        return $this->author($user, $post);
    }

    public function updateMedia(?User $user, Post $post, LazyRelation $tags): bool
    {
        return $this->author($user, $post);
    }

    public function updateTags(?User $user, Post $post, LazyRelation $tags): bool
    {
        $tags->collect()->each(fn (Tag $tag) => $tag);

        return $this->author($user, $post);
    }

    public function attachMedia(?User $user, Post $post, LazyRelation $tags): bool
    {
        return $this->author($user, $post);
    }

    public function attachTags(?User $user, Post $post, LazyRelation $tags): bool
    {
        return $this->updateTags($user, $post, $tags);
    }

    public function detachMedia(?User $user, Post $post, LazyRelation $tags): bool
    {
        return $this->author($user, $post);
    }

    public function detachTags(?User $user, Post $post, LazyRelation $tags): bool
    {
        return $this->updateTags($user, $post, $tags);
    }

    public function delete(?User $user, Post $post): bool
    {
        return $this->author($user, $post);
    }

    public function deleteAll(?User $user): bool
    {
        return $user && $user->isAdmin();
    }

    public function author(?User $user, Post $post): bool
    {
        return $user && $post->author->is($user);
    }
}
