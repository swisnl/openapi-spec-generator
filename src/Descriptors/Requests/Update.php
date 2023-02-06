<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors\Requests;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;

class Update extends RequestDescriptor
{
    /**
     * {@inheritDoc}
     *
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function request(): RequestBody
    {
        return RequestBody::create()
          ->content(
              MediaType::create()
                ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                ->schema(
                    Schema::object()->properties(
                        $this->schemaBuilder->build($this->route, true)
                          ->objectId('data')
                    )
                      ->required('data')
                )
          );
    }
}
