# JsonApi Playground

Free data service for JSON:API clients testing: <http://jsonapiplayground.reyesoft.com/> 😁

## Notice

This example uses a private library (`laravel-json-api`) and purchase is required if you need generate your own data server.

## Resources

- authors
- books
- series
- chapters
- stores
- photos

## Development

### Defining a schema for Model

```php
class AuthorSchema extends SchemaProvider
{
    protected $resourceType = 'authors';
    public static $policy = AuthorPolicy::class;
    public static $model = Author::class;
    protected static $attributes = [];
    protected static $relationships = [];

    public static function boot(): void {
        self::addAttribute('name')
            ->setFilter(StringFilter::class)
            ->sortable();
        self::addAttribute('birthplace')
            ->setCru('r');
        self::addAttribute('date_of_birth');
        self::addAttribute('date_of_death');

        self::addRelationship(PhotoSchema::class, 'photos')
            ->setHasMany();
        self::addRelationship(BookSchema::class, 'books')
            ->setHasMany();
    }
}
```

#### Explanation

`$policy` define policies for your calls. For example block by user permissions or if user is the owner of resource.

`setFilter(StringFilter::class)` add the possibility to do calls like (more options available)
 
```
GET /authors?filter[name]=Ray
GET /authors?filter[name][eq]=Ray
GET /authors?filter[name][contains]=Ray
```

`sortable()` add the possibility to do calls like
```
GET /authors?sort=name
GET /authors?sort=-name
```

`setCru('r')` you can only read value, you cannot set con Create (c) or Update (u). Default value for CRU parameter is `cru`. 
