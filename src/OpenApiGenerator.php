<?php

namespace LaravelJsonApi\OpenApiSpec;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class OpenApiGenerator
{
    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\ValidationException
     */
    public function generate(string $serverKey, string $format = 'yaml'): string
    {
        $generator = new Generator($serverKey);
        $openapi = $generator->generate();

        $openapi->validate();

        $storageDisk = Storage::disk(config('openapi.filesystem_disk'));

        $fileName = $serverKey . '_openapi.' . $format;

        if ($format === 'yaml') {
            $output = Yaml::dump(
                $openapi->toArray(),
                config('openapi.yaml.format.inline', 2),
                config('openapi.yaml.format.indent', 4)
            );
        } elseif ($format === 'json') {
            $output = json_encode($openapi->toArray(), JSON_PRETTY_PRINT);
        }

        $storageDisk->put($fileName, $output);

        return $output;
    }
}
