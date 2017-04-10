# RedmineUserProviderBundle

A bundle to use Redmine as a user provider.

## Installation

The recommended way to install this bundle is through [Composer](http://getcomposer.org/). Just run:

```bash
composer require gmaissa/redmine-user-provider-bundle
```

Register the bundle in the kernel of your application:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new GMaissa\RedmineUserProviderBundle\GmRedmineUserProviderBundle(),
    );

    return $bundles;
}
```

Use the Redmine user provider in your security.yml file:

```yaml
security:
    ...
    providers:
        app:
            id: redmine_user_provider.provider
    ...
```

## Configuration reference

```yaml
gm_redmine_user_provider:
    redmine:
        url:                  ~ # Required
        allowed_domains:      []
    user_class:           \GMaissa\RedmineUserProviderBundle\Model\RedmineUser
    user_repository_service: ~
```

# Using a repository to store User data

If you want to store the redmine user data locally, either :
* implements the `GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface` interface for your repository service
* use the provided Doctrine repository `redmine_user_provider.repository.user.db`

```yaml
gm_redmine_user_provider:
    ...
    user_repository_service: redmine_user_provider.repository.user.db
```



## Implementing your own User Factory

If you want to use a custom User Factory, implement the `GMaissa\RedmineUserProviderBundle\Factory\UserFactoryInterface`
interface, register your service and alias it as `redmine_user_provider.factory.user`.

```yaml
services:
    app.redmine_user_provider.user_factory:
        class: AppBundle\Factory\CustomUserFactory
        calls:
            - [setUserClass, ["%gm_redmine_user_provider.user_class%"]]
        alias: redmine_user_provider.factory.user
```

## Using your own Redmine Api Client

Like the custom User Factory, implement the `GMaissa\RedmineUserProviderBundle\ApiClient\RedmineApiClientInterface`
interface, register your service and alias it as `redmine_user_provider.api.client`.

```yaml
services:
    app.redmine_user_factory.api_client:
        class: AppBundle\ApiClient\CustomApiClient
        arguments:
            - "%gm_redmine_user_provider.redmine.url%"
        alias: redmine_user_provider.api.client
```

## Contributing

In order to be accepted, your contribution needs to pass a few controls : 

* PHP files should be valid
* PHP files should follow the [PSR-2](http://www.php-fig.org/psr/psr-2/) standard
* PHP files should be [phpmd](https://phpmd.org) and [phpcpd](https://github.com/sebastianbergmann/phpcpd)
warning/error free

To ease the validation process, install the [pre-commit framework](http://pre-commit.com)
and install the repository pre-commit hook :

    pre-commit install

Finally, in order to homogenize commit messages across contributors (and to ease generation of the CHANGELOG),
please apply this [git commit message hook](https://gist.github.com/GMaissa/f008b2ffca417c09c7b8)
onto your local repository. 
