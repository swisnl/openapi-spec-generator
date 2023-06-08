<?php

namespace LaravelJsonApi\OpenApiSpec\Attributes;

use LaravelJsonApi\OpenApiSpec\Descriptors\Actions\ActionDescriptor;
use LaravelJsonApi\OpenApiSpec\Descriptors\Requests\RequestDescriptor;
use LaravelJsonApi\OpenApiSpec\Descriptors\Responses\ResponseDescriptor;

#[\Attribute]
class DescriptorsAttribute
{
    /**
     * @param string $actionDescriptorClass
     * @param string $responseDescriptorClass
     * @param string|null $requestDescriptorClass
     *
     * @throws \ReflectionException|\Exception
     */
    public function __construct(
        public string $actionDescriptorClass,
        public string $responseDescriptorClass,
        public ?string $requestDescriptorClass = null
    ) {
        $this->validateActionDescriptorClass();
        $this->validateResponseDescriptorClass();
        $this->validateRequestDescriptorClass();
    }

    /**
     * @return void
     * @throws \ReflectionException|\Exception
     *
     */
    private function validateActionDescriptorClass(): void
    {
        $reflection = new \ReflectionClass($this->actionDescriptorClass);

        $baseClass = ActionDescriptor::class;

        if (!$reflection->isSubclassOf($baseClass)) {
            throw new \Exception("actionDescriptorClass argument must extend the $baseClass class.");
        }
    }

    /**
     * @return void
     * @throws \ReflectionException|\Exception
     *
     */
    private function validateResponseDescriptorClass(): void
    {
        $reflection = new \ReflectionClass($this->responseDescriptorClass);

        $baseClass = ResponseDescriptor::class;

        if (!$reflection->isSubclassOf($baseClass)) {
            throw new \Exception("responseDescriptorClass must extend the $baseClass class.");
        }
    }

    /**
     * @return void
     * @throws \ReflectionException|\Exception
     *
     */
    private function validateRequestDescriptorClass(): void
    {
        //Request descriptor is optional
        if ($this->requestDescriptorClass) {
            $reflection = new \ReflectionClass($this->requestDescriptorClass);

            $baseClass = RequestDescriptor::class;

            if (!$reflection->isSubclassOf($baseClass)) {
                throw new \Exception("requestDescriptorClass must extend the $baseClass class.");
            }
        }
    }
}
