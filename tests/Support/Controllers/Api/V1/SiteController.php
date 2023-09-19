<?php

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Controllers\Api\V1;

use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Controllers\Controller;

class SiteController extends Controller
{
    use Actions\FetchMany;
    use Actions\FetchOne;
    use Actions\Store;
}
