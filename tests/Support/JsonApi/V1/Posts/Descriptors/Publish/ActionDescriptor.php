<?php

declare(strict_types=1);

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\JsonApi\V1\Posts\Descriptors\Publish;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use LaravelJsonApi\OpenApiSpec\Descriptors\Actions\Store;

class ActionDescriptor extends Store
{
    protected function summary(): string
    {
        return "Publish one post";
    }

    protected function requestBody(): ?RequestBody
    {
        return null;
    }
}
