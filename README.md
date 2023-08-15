# Extended Api Doc Bundle #

```yaml
# config/packages/nelmio.yaml
nelmio_api_doc:
    documentation:
        info:
            title: API Service documentation
            description: API Service description
            version: '%env(APP_VERSION)%'
    areas:
        path_patterns:
            - ^/api(?!/doc(.json|.yaml)?$)
```
