# Extended Api Doc Bundle #
This package improves the Api Doc Bundle - adding the `#[QueryParameters]` attribute allows you to declare parameters in a separate class instead of in a controller, so you can use it for multiple controllers.
## Installation
 
```sh
composer require pbaszak/extended-api-doc-bundle
```

```yaml
# config/routes.yaml
extendend_api_doc:
    resource: '@DocumentationBundle/Resources/routes/*'
```

```yaml
# config/packages/nelmio.yaml
nelmio_api_doc:
    documentation:
        info:
            title: API Service documentation
            description: API Service description
            version: '%env(APP_VERSION)%' # if You add APP_VERSION env to Your docker image in CD process
    areas:
        path_patterns:
            - ^/api(?!/doc(.json|.yaml)?$)
```

if `Symfony Security` is used:
```yaml
# config/packages/security.yaml
security:
    # ...
    access_control:
        # ...
        - { path: ^\/api\/doc(\.yaml|\.json)?$, methods: [GET], roles: PUBLIC_ACCESS }
        # ...
```
