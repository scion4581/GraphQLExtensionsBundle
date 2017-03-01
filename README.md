# GraphQLExtensionsBundle
GraphQL Extensions for Symfony Framework

### Installation

`composer require yosuhido/graphql-extensions-bundle`

#### Add routing to your routing.yml:

```
graphql_extensions:
    resource: "@GraphQLExtensionsBundle/Controller/"
    type:     annotation
```