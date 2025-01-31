<?php

namespace LaravelJsonApi\OpenApiSpec\Descriptors;

use GoldSpecDigital\ObjectOrientedOAS\Objects;
use LaravelJsonApi\OpenApiSpec\Descriptors\Descriptor as BaseDescriptor;

class Server extends BaseDescriptor
{
    /**
     * @todo Add contact
     * @todo Add TOS
     * @todo Add License
     */
    public function info(): Objects\Info
    {
        return Objects\Info::create()
            ->title(config("openapi.servers.{$this->generator->key()}.info.title"))
            ->description(config("openapi.servers.{$this->generator->key()}.info.description"))
            ->version(config("openapi.servers.{$this->generator->key()}.info.version"));
    }

    /**
     * @return \LaravelJsonApi\Core\Server\Server[]
     *
     * @todo Allow Configuration
     * @todo Use for enums?
     * @todo Extract only URI Server Prefix and let domain be set separately
     */
    public function servers(): array
    {
        return [
            Objects\Server::create()
                ->url('{serverUrl}')
                ->variables(Objects\ServerVariable::create('serverUrl')
                    ->default($this->generator->server()->url())
                ),
        ];
    }
}
