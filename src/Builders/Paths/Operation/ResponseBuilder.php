<?php


namespace LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation;


use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Example;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Illuminate\Support\Collection;
use LaravelJsonApi\OpenApiSpec\Builders\Builder;
use LaravelJsonApi\OpenApiSpec\ComponentsContainer;
use LaravelJsonApi\OpenApiSpec\Concerns\ResolvesActionTraitToDescriptor;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use LaravelJsonApi\Laravel\Http\Controllers\Actions;
use LaravelJsonApi\OpenApiSpec\Descriptors\Responses;


class ResponseBuilder extends Builder
{

    use ResolvesActionTraitToDescriptor;

    protected ComponentsContainer $components;

    protected SchemaBuilder $schemaBuilder;

    protected Collection $defaults;

    protected Schema $jsonapi;

    protected array $descriptors = [
      Actions\FetchMany::class => Responses\FetchMany::class,
      Actions\FetchOne::class => Responses\FetchOne::class,
      Actions\Store::class => Responses\FetchOne::class,
      Actions\Update::class => Responses\FetchOne::class,
      Actions\Destroy::class => Responses\Destroy::class,
      Actions\FetchRelated::class => Responses\FetchRelated::class,
      Actions\AttachRelationship::class => Responses\AttachRelationship::class,
      Actions\DetachRelationship::class => Responses\DetachRelationship::class,
      Actions\FetchRelationship::class => Responses\FetchRelation::class,
      Actions\UpdateRelationship::class => Responses\UpdateRelationship::class,
    ];

    public function __construct(
      Generator $generator,
      ComponentsContainer $components,
      SchemaBuilder $schemaBuilder
    ) {
        parent::__construct($generator);
        $this->components = $components;
        $this->schemaBuilder = $schemaBuilder;


        $this->addDefaults();

    }

    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Response[]
     */
    public function build(Route $route): array
    {
        return $this->getDescriptor($route)->response();
    }

    /**
     * @param  \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema  $data
     * @param  \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema|null  $meta
     * @param  \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema|null  $links
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public static function buildResponse(
      SchemaContract $data,
      Schema $meta = null,
      Schema $links = null
    ): Schema {

        $jsonapi = Schema::object('jsonapi')
          ->properties(Schema::string('version')
            ->title('version')
            ->example('1.0')
          );

        $schemas = collect([$jsonapi, $data, $meta, $links])
          ->whereNotNull()->toArray();
        return Schema::object()
          ->properties(...$schemas)
          ->required('jsonapi', 'data');
    }

    /**
     * @param  \LaravelJsonApi\OpenApiSpec\Route  $route
     *
     * @return \LaravelJsonApi\OpenApiSpec\Descriptors\Actions\ActionDescriptor|null
     */
    protected function getDescriptor(Route $route
    ): ?Responses\ResponseDescriptor {
        $class = $this->descriptorClass($route);
        if (isset($this->descriptors[$class])) {
            return new $this->descriptors[$class](
              $this->generator,
              $route,
              $this->schemaBuilder,
              $this->defaults,
            );
        }
        return null;
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    /**
     */
    protected function addDefaults(): void
    {
        $this->jsonapi = $this->components->addSchema(
          Schema::object('helper.jsonapi')
            ->title('Helper/JSONAPI')
            ->properties(Schema::string('version')
              ->title('version')
              ->example('1.0')
            )
            ->required('version')
        );

        $errors = $this->components->addSchema(
          Schema::array('helper.errors')
            ->title('Helper/Errors')
            ->items(Schema::object('error')
              ->title('Error')
              ->properties(
                Schema::string('detail'),
                Schema::string('status'),
                Schema::string('title'),
                Schema::object('source')
                  ->properties(Schema::string('pointer'))
              )
              ->required('status', 'title')
            )
        );
        $errorBody = Schema::object()->properties($this->jsonapi, $errors);
        $this->defaults = collect([
          Response::badRequest('400')
            ->description('Bad request')
            ->content(
              MediaType::create()
                ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                ->schema($errorBody)
                ->examples(Example::create('-')->value([
                  'jsonapi' => [
                    'version' => '1.0',
                  ],
                  'errors' => [
                    [
                      'detail' => 'The member id is required.',
                      'source' => ['pointer' => '/data'],
                      'status' => '400',
                      'title' => 'Non-Compliant JSON:API Document',
                    ],
                  ],
                ])
                )
            ),
          Response::unauthorized('401')
            ->description('Unauthorized Action')
            ->content(
              MediaType::create()
                ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                ->schema($errorBody)
                ->examples(Example::create('-')->value([
                  'jsonapi' => [
                    'version' => '1.0',
                  ],
                  'errors' => [
                    [
                      'title' => 'Unauthorized.',
                      'status' => '401',
                      'detail' => 'Unauthenticated.',
                    ],
                  ],
                ])
                )
            ),
          Response::notFound('404')
            ->description('Content Not Found')
            ->content(
              MediaType::create()
                ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                ->schema($errorBody)
                ->examples(Example::create('-')->value([
                  'jsonapi' => [
                    'version' => '1.0',
                  ],
                  'errors' => [
                    [
                      'title' => 'Not Found',
                      'status' => '404',
                    ],
                  ],
                ])
                )
            ),
          Response::unprocessableEntity('422')
            ->statusCode(422)
            ->description('Unprocessable Entity')
            ->content(
              MediaType::create()
                ->mediaType(MediaTypeInterface::JSON_API_MEDIA_TYPE)
                ->schema($errorBody)
                ->examples(Example::create('-')->value([
                  'jsonapi' => [
                    'version' => '1.0',
                  ],
                  'errors' => [
                    [
                      'detail' => 'Lorem Ipsum',
                      'source' => ['pointer' => '/data/attributes/lorem'],
                      'title' => 'Unprocessable Entity',
                      'status' => '422',
                    ],
                  ],
                ])
                )
            ),
        ])
          ->mapWithKeys(function (Response $response) {
              $ref = $this->components->addResponse($response);
              return [$response->objectId => $ref];
          });
    }

}
