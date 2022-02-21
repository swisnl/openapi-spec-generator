<?php


namespace LaravelJsonApi\OpenApiSpec\Descriptors\Schema;


use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use LaravelJsonApi\Contracts\Schema\Field;
use LaravelJsonApi\Contracts\Schema\PolymorphicRelation;
use LaravelJsonApi\Contracts\Schema\Schema as JASchema;
use LaravelJsonApi\Contracts\Schema\Sortable;
use LaravelJsonApi\Core\Resources\JsonApiResource;
use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Fields\Attribute;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Map;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Relations\Relation;
use LaravelJsonApi\Eloquent\Pagination\CursorPagination;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\OpenApiSpec\Builders\Paths\Operation\SchemaBuilder;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Schema\PaginationDescriptor;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\Schema\SortablesDescriptor;
use LaravelJsonApi\OpenApiSpec\Contracts\Descriptors\SchemaDescriptor;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema as OASchema;
use LaravelJsonApi\OpenApiSpec\Descriptors\Descriptor;
use LaravelJsonApi\OpenApiSpec\Descriptors\Schema\Filters;
use LaravelJsonApi\Eloquent;
use LaravelJsonApi\OpenApiSpec\Generator;
use LaravelJsonApi\OpenApiSpec\Route;

class Schema extends Descriptor implements SchemaDescriptor, SortablesDescriptor, PaginationDescriptor
{

    protected array $filterDescriptors = [
      Eloquent\Filters\WhereIdIn::class => Filters\WhereIdIn::class,
      Eloquent\Filters\WhereIn::class => Filters\WhereIn::class,
      Eloquent\Filters\Scope::class => Filters\Scope::class,
      Eloquent\Filters\WithTrashed::class => Filters\WithTrashed::class,
      Eloquent\Filters\Where::class => Filters\Where::class,
    ];

    /**
     * @param  \LaravelJsonApi\Contracts\Schema\Schema  $schema
     * @param  string  $objectId
     * @param  string  $type
     * @param  string  $name
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function fetch(
      JASchema $schema,
      string $objectId,
      string $type,
      string $name
    ): OASchema {

        $resource = $this->generator
          ->resources()
          ->resource($schema::model());

        $fields = $this->fields($schema->fields(), $resource);
        $properties = [
          OASchema::string('type')
            ->title('type')
            ->default($type),
          OASchema::string('id')
            ->example($resource->id()),
          OASchema::object('attributes')
            ->properties(...$fields->get('attributes')),
        ];

        if ($fields->has('relationships')) {
            $properties[] = OASchema::object('relationships')
              ->properties(...$fields->get('relationships'));
        }
        return OASchema::object($objectId)
          ->title('Resource/'.ucfirst($name).'/Fetch')
          ->required('type', 'id', 'attributes')
          ->properties(...$properties);
    }

    /**
     * @param  \LaravelJsonApi\OpenApiSpec\Route  $route
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function store(Route $route): OASchema
    {
        $objectId = SchemaBuilder::objectId($route);

        $resource = $this->generator->resources()
          ->resource($route->schema()::model());

        $fields = $this->fields($route->schema()->fields(), $resource);

        return OASchema::object($objectId)
          ->title('Resource/'.ucfirst($route->name(true))."/Store")
          ->required('type', 'attributes')
          ->properties(
            OASchema::string('type')
              ->title('type')
              ->default($route->name()),
            OASchema::object('attributes')
              ->properties(...$fields->get('attributes')),
            OASchema::object('relationships')
              ->properties(...$fields->get('relationships'))
          );
    }

    /**
     * @param  \LaravelJsonApi\OpenApiSpec\Route  $route
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function update(Route $route): OASchema
    {
        $objectId = SchemaBuilder::objectId($route);
        $resource = $this->generator->resources()
          ->resource($route->schema()::model());

        $fields = $this->fields($route->schema()->fields(), $resource);

        return OASchema::object($objectId)
          ->title('Resource/'.ucfirst($route->name(true)).'/Update')
          ->properties(
            OASchema::string('type')
              ->title('type')
              ->default($route->name()),
            OASchema::string('id')
              ->example($resource->id()),
            OASchema::object('attributes')
              ->properties(...$fields->get('attributes')),
            OASchema::object('relationships')
              ->properties(...$fields->get('relationships'))
          )
          ->required('type', 'id', 'attributes');
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function fetchRelationship(Route $route): OASchema
    {
        if(!$route->isPolymorphic()){
            $resource = $this->generator->resources()
              ->resource($route->inversSchema()::model());
        }
        else{
            $resource = $this->generator->resources()
              ->resource(Arr::first($route->inversSchemas())::model());
        }

      $inverseRelation = $route->relation() !== null ? $route->relation()->inverse() : null;
      return $this->relationshipData($route->relation(), $resource,
        $inverseRelation)
          ->title('Resource/'.ucfirst($route->name(true)).'/Relationship/'.ucfirst($route->relationName()).'/Fetch');
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function updateRelationship(Route $route): OASchema
    {
        if(!$route->isPolymorphic()){
            $resource = $this->generator->resources()
              ->resource($route->inversSchema()::model());
        }
        else{
            $resource = $this->generator->resources()
              ->resource(Arr::first($route->inversSchemas())::model());
        }

        $dataSchema = $this->getDataSchema($route, $resource);

        return $dataSchema->title('Resource/'.ucfirst($route->name(true)).'/Relationship/'.ucfirst($route->relationName()).'/Update');
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function attachRelationship(Route $route): OASchema
    {
        if(!$route->isPolymorphic()){
            $resource = $this->generator->resources()
              ->resource($route->inversSchema()::model());
        }
        else{
            $resource = $this->generator->resources()
              ->resource(Arr::first($route->inversSchemas())::model());
        }

        $dataSchema = $this->getDataSchema($route, $resource);

        return $dataSchema->title('Resource/'.ucfirst($route->name(true)).'/Relationship/'.ucfirst($route->relationName()).'/Attach');
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function detachRelationship(Route $route): OASchema
    {
        if(!$route->isPolymorphic()){
            $resource = $this->generator->resources()
              ->resource($route->inversSchema()::model());
        }
        else{
            $resource = $this->generator->resources()
              ->resource(Arr::first($route->inversSchemas())::model());
        }
        $dataSchema = $this->getDataSchema($route, $resource);

        return $dataSchema->title('Resource/'.ucfirst($route->name(true)).'/Relationship/'.ucfirst($route->relationName()).'/Detach');
    }

    /**
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    public function fetchPolymorphicRelationship(
      Route $route,
      $objectId
    ): OASchema {
        $resource = $this->generator->resources()
          ->resource($route->schema()::model());

        $inverseRelation = $route->relation() !== null ? $route->relation()->inverse() : null;
        return $this->relationshipData($route->relation(), $resource,
          $inverseRelation)
          ->objectId($objectId)
          ->title('Resource/'.ucfirst($route->name(true)).'/Relationship/'.ucfirst($route->relationName()).'/Fetch');
    }

    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter[]
     */
    public function sortables($route): array
    {
        $fields = collect($route->schema()->sortFields())
          ->merge(collect($route->schema()->sortables())
            ->map(function (Sortable $sortable) {
                return $sortable->sortField();
            })->whereNotNull())
          ->map(function (string $field) {
              return [$field, '-'.$field];
          })->flatten()->toArray();

        return [
          Parameter::query('sort')
            ->name('sort')
            ->schema(OASchema::array()
              ->items(OASchema::string()->enum(...$fields))
            )
            ->allowEmptyValue(false)
            ->required(false),
        ];
    }

    /**
     * @param  \LaravelJsonApi\OpenApiSpec\Route  $route
     *
     * @return array
     */
    public function pagination(Route $route): array
    {
        $pagination = $route->schema()->pagination();
        if ($pagination instanceof PagePagination) {
            return [
              Parameter::query('pageSize')
                ->name('page[size]')
                ->description('The page size for paginated results')
                ->required(false)
                ->allowEmptyValue(false)
                ->schema(OASchema::integer()),
              Parameter::query('pageNumber')
                ->name('page[number]')
                ->description('The page number for paginated results')
                ->required(false)
                ->allowEmptyValue(false)
                ->schema(OASchema::integer()),
            ];
        }

        if ($pagination instanceof CursorPagination) {
            return [
              Parameter::query('pageLimit')
                ->name('page[limit]')
                ->description('The page limit for paginated results')
                ->required(false)
                ->allowEmptyValue(false)
                ->schema(OASchema::integer()),
              Parameter::query('pageAfter')
                ->name('page[after]')
                ->description('The page offset for paginated results')
                ->required(false)
                ->allowEmptyValue(false)
                ->schema(OASchema::string()),
              Parameter::query('pageBefore')
                ->name('page[before]')
                ->description('The page offset for paginated results')
                ->required(false)
                ->allowEmptyValue(false)
                ->schema(OASchema::string()),
            ];
        }

        return [];
    }

    /**
     * @param $route
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter[]
     */
    public function filters($route): array
    {
        return collect($route->schema()->filters())
          ->map(function (Eloquent\Contracts\Filter $filterInstance) use ($route
          ) {
            $descriptor = $this->getDescriptor($filterInstance);
            return (new $descriptor($this->generator, $route, $filterInstance))->filter();
          })
          ->flatten()
          ->toArray();
    }

    /**
     * @param  \LaravelJsonApi\Contracts\Schema\Field[]  $fields
     * @param  \LaravelJsonApi\Core\Resources\JsonApiResource  $resource
     *
     * @return \Illuminate\Support\Collection
     */
    protected function fields(
      array $fields,
      JsonApiResource $resource
    ): Collection {
        return collect($fields)
          ->mapToGroups(function (Field $field) {
              switch (true) {
                case $field instanceof Attribute:
                  $key = 'attributes';
                  break;
                case $field instanceof Relation:
                  $key = 'relationships';
                  break;
                default:
                  $key = 'unknown';
                }
                return [$key => $field];
          })
          ->map(function ($fields, $type) use ($resource) {
              switch ($type) {
                case 'attributes':
                  return $this->attributes($fields, $resource);
                case 'relationships':
                  return $this->relationships($fields, $resource);
                default:
                  return null;
              }
          });
    }

    /**
     * @return Schema[]
     */
    protected function attributes(
      Collection $fields,
      JsonApiResource $example
    ): array {
        return $fields
          ->filter(fn($field) => ! ($field instanceof ID))
          ->map(function (Field $field) use ($example) {
              $fieldId = $field->name();
              switch (true) {
                case $field instanceof Boolean:
                    $fieldDataType = OASchema::boolean($fieldId);
                    break;
                case $field instanceof Number:
                    $fieldDataType = OASchema::number($fieldId);
                    break;
                case $field instanceof ArrayList:
                    $fieldDataType = OASchema::array($fieldId);
                    break;
                case $field instanceof ArrayHash:
                case $field instanceof Map:
                   $fieldDataType = OASchema::object($fieldId);
                   break;
                default:
                  $fieldDataType = OASchema::string($fieldId);
              }

              $schema = $fieldDataType->title($field->name());

              if (isset($example[$field->name()])) {
                  $schema = $schema->example($example[$field->name()]);
              }
              if ($field->isReadOnly(null)) {
                  $schema = $schema->readOnly(true);
              }
              return $schema;
          })->toArray();
    }

    /**
     * @param  \Illuminate\Support\Collection  $relationships
     * @param  \LaravelJsonApi\Core\Resources\JsonApiResource  $example
     *
     * @return array
     * @todo Fix relation field names
     */
    protected function relationships(
      Collection $relationships,
      JsonApiResource $example
    ): array {
        return $relationships
          ->map(function (Relation $relation) use ($example) {
              return $this->relationship($relation, $example);
          })->toArray();

    }

    /**
     * @param \LaravelJsonApi\Eloquent\Fields\Relations\Relation $relation
     * @param  \LaravelJsonApi\Core\Resources\JsonApiResource  $example
     * @param  bool  $includeData
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    protected function relationship(
      Relation $relation,
      JsonApiResource $example,
      bool $includeData = false
    ): OASchema {
        $fieldId = $relation->name();

        $type = $relation->inverse();

        $linkSchema = $this->relationshipLinks($relation, $example, $type);

        $dataSchema = $this->relationshipData($relation, $example, $type);

        if ($relation instanceof Eloquent\Fields\Relations\ToMany) {
            $dataSchema = OASchema::array('data')
              ->items($dataSchema);
        }
        $schema = OASchema::object($fieldId)
          ->title($relation->name());

        if ($includeData) {
            return $schema->properties($dataSchema);
        } else {
            return $schema->properties($linkSchema);
        }
    }

    /**
     * @param \LaravelJsonApi\Eloquent\Fields\Relations\Relation $relation
     * @param  \LaravelJsonApi\Core\Resources\JsonApiResource  $example
     * @param  string  $type
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    protected function relationshipData(
      Relation $relation,
      JsonApiResource $example,
      string $type
    ): OASchema {
        if ($relation instanceof PolymorphicRelation) {

            // @todo Add examples for each available type
            $dataSchema = OASchema::object('data')
              ->title($relation->name())
              ->required('type', 'id')
              ->properties(
                OASchema::string('type')
                  ->title('type')
                  ->enum(...$relation->inverseTypes()),
                OASchema::string('id')
                  ->title('id')
              );
        } else {
            $dataSchema = OASchema::object('data')
              ->title($relation->name())
              ->required('type', 'id')
              ->properties(
                OASchema::string('type')
                  ->title('type')
                  ->default($type),
                OASchema::string('id')
                  ->title('id')
                  ->example($example->id())
              );
        }


        return $dataSchema;
    }

    /**
     * @param  mixed  $relation
     * @param  \LaravelJsonApi\Core\Resources\JsonApiResource  $example
     * @param  string  $type
     *
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Schema
     */
    public function relationshipLinks(
      $relation,
      JsonApiResource $example,
      string $type
    ): OASchema {
        $name = Str::dasherize(
          Str::plural($relation->relationName())
        );

        /*
         * @todo Create real links
         */
        $relatedLink = $this->generator->server()->url([
          $name,
          $example->id(),
        ]);

        /*
         * @todo Create real links
         */
        $selfLink = $this->generator->server()->url([
          $name,
          $example->id(),
        ]);

        return OASchema::object('links')
          ->readOnly(true)
          ->properties(
            OASchema::string('related')
              ->title('related')
              ->example($relatedLink),
            OASchema::string('self')
              ->title('self')
              ->example($selfLink),
          );
    }

    /**
     * @param  \LaravelJsonApi\OpenApiSpec\Route  $route
     * @param  \LaravelJsonApi\Core\Resources\JsonApiResource  $resource
     *
     * @return array
     */
    protected function links(Route $route, JsonApiResource $resource): array
    {
        $url = $this->generator->server()->url([
          $route->name(),
          $resource->id(),
        ]);
        return [
          OASchema::string('self')
            ->title('self')
            ->example($url),
        ];
    }

    /**
     *
     * @todo Get descriptors from Attributes
     */
    protected function getDescriptor(Eloquent\Contracts\Filter $filter
    ): string {
        foreach ($this->filterDescriptors as $filterClass => $descriptor) {
            if ($filter instanceof $filterClass) {
                return $descriptor;
            }
        }
    }

    /**
     * @param Route $route
     * @param JsonApiResource $resource
     * @return OASchema
     * @throws \GoldSpecDigital\ObjectOrientedOAS\Exceptions\InvalidArgumentException
     */
    protected function getDataSchema(Route $route, JsonApiResource $resource): OASchema
    {
        $inverseRelation = $route->relation() !== null ? $route->relation()->inverse() : null;

        $relation = $route->relation();

        $dataSchema = $this
            ->relationshipData(
                $relation,
                $resource,
                $inverseRelation
            );

        if ($relation instanceof Eloquent\Fields\Relations\ToMany) {
            $dataSchema = OASchema::array('data')
                ->items($dataSchema);
        }
        return $dataSchema;
    }

}
