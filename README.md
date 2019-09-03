# JsonApi Playground

Online version: <http://jsonapiplayground.reyesoft.com/> 😁

## Notice

This example uses a private library (`laravel-json-api`). Library purchase is required.

## Resources

- authors
- books
- series
- chapters
- stores
- photos

### Entry points

```
[GET] localhost/v2/{resource}
[GET] localhost/v2/{resource}/{resource_id}
[GET] localhost/v2/{resource}/{resource_id}/related
[PATCH] localhost/v1/{resource}/{resource_id}
[DELETE] localhost/v1/{resource}/{resource_id}
```

## Development

### Tests

```
phpunit
```

## Code fixer

```
sh autofix.sh
```
