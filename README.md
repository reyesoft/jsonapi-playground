# JsonApi Playground

Online version: <http://jsonapiplayground.reyesoft.com/> 😁

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

## PhpCsFixer autofix

```
sh autofix.sh
```
