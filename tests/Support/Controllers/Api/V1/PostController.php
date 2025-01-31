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

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Controllers\Api\V1;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use LaravelJsonApi\Core\Responses\DataResponse;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Controllers\Controller;
use LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Posts\PostQuery;
use LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Posts\PostSchema;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Post;

class PostController extends Controller
{
    use Actions\AttachRelationship;
    use Actions\Destroy;
    use Actions\DetachRelationship;
    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\FetchRelated;
    use Actions\FetchRelationship;
    use Actions\Store;
    use Actions\Update;
    use Actions\UpdateRelationship;

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function purge(): Response
    {
        $this->authorize('deleteAll', Post::class);

        Post::query()->forceDelete();

        return response('', 204);
    }

    /**
     * Publish a post.
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publish(PostSchema $schema, PostQuery $query, Post $post): Responsable
    {
        $this->authorize('update', $post);

        abort_if($post->published_at, 403, 'Post is already published.');

        $post->update(['published_at' => now()]);

        $model = $schema
            ->repository()
            ->queryOne($post)
            ->withRequest($query)
            ->first();

        return new DataResponse($model);
    }
}
