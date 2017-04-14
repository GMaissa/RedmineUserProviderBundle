# RedmineUserProviderBundle

 master | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/443c6ca0-a4ba-4add-a1e6-41dd63a1f14e/mini.png)](https://insight.sensiolabs.com/projects/443c6ca0-a4ba-4add-a1e6-41dd63a1f14e) | [![Scrutinizer](https://img.shields.io/scrutinizer/g/GMaissa/RedmineUserProviderBundle/master.svg)](https://scrutinizer-ci.com/g/GMaissa/RedmineUserProviderBundle/?branch=master) | [![Build Status](https://travis-ci.org/GMaissa/RedmineUserProviderBundle.svg?branch=master)](https://travis-ci.org/GMaissa/RedmineUserProviderBundle) | [![Code Coverage](https://scrutinizer-ci.com/g/GMaissa/RedmineUserProviderBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GMaissa/RedmineUserProviderBundle/?branch=master) | [![Packagist](https://img.shields.io/packagist/l/gmaissa/redmine-user-provider-bundle.svg)](https://packagist.org/packages/gmaissa/redmine-user-provider-bundle)
--------|---------|-------------|--------|----------|-----------

## About

A bundle to use Redmine as a user provider.

## Installation

The recommended way to install this bundle is through [Composer](http://getcomposer.org/). Just run :

```bash
composer require gmaissa/redmine-user-provider-bundle
```

Register the bundle in the kernel of your application :

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

Use the Redmine user provider in your security.yml file :

```yaml
# app/config/security.yml
security:
    ...
    providers:
        app:
            id: gm_redmine_user_provider.provider
    ...
```

## Configuration reference

```yaml
# app/config/config.yml
gm_redmine_user_provider:
    redmine:
        url:                  ~ # Required
        allowed_domains:      []
    user_class:           GMaissa\RedmineUserProviderBundle\Model\RedmineUser
    persistence_driver:   ~ # One of "orm"
    oauthserver_bridge:   false
```

## Persist your User

### User entity class

Implement your own User Entity class, extending `GMaissa\RedmineUserProviderBundle\Entity\User` and declare it in the bundle
configuration :

```yaml
# app/config/config.yml
gm_redmine_user_provider:
    ...
    user_class: AppBundle\Entity\User
```

### Using a provided user repository

Enable the provided persistence driver you want to use (for now only Doctrine ORM is provided) :

```yaml
# app/config/config.yml
gm_redmine_user_provider:
    ...
    user_class: AppBundle\Entity\User
    persistence_driver: orm
```

### Using a custom user repository

Implements the `GMaissa\RedmineUserProviderBundle\Repository\UserRepositoryInterface` interface for your repository
service`and tag is as a `gm_redmine_user_provider.user_repository :

```yaml
# services.yml
services:
    app.user_repository:
        class: AppBundle\Repository\UserReposioty
        tags:
            -  {name: gm_redmine_user_provider.user_repository}
```

## Using with FOSOAuthServerBundle

Enable the OAuth Server Bridge :

```yaml
# app/config/config.yml
gm_redmine_user_provider:
    ...
    oauthserver_bridge: true
```

You can now use the OAuth Storage service `gm_redmine_user_provider.bridge.oauth.storage` :

```yaml
# app/config/config.yml
fos_oauth_server:
    ...
    service:
        storage: gm_redmine_user_provider.bridge.oauth.storage
```

## Implementing your own User Factory

If you want to use a custom User Factory, implement the `GMaissa\RedmineUserProviderBundle\Factory\UserFactoryInterface`
interface, register your service and alias it as `gm_redmine_user_provider.factory.user`.

```yaml
# services.yml
services:
    app.redmine_user_provider.user_factory:
        class: AppBundle\Factory\CustomUserFactory
        calls:
            - [setUserClass, ["%gm_redmine_user_provider.user_class%"]]
        alias: gm_redmine_user_provider.factory.user
```

## Using your own Redmine Api Client

Like the custom User Factory, implement the `GMaissa\RedmineUserProviderBundle\ApiClient\RedmineApiClientInterface`
interface, register your service and alias it as `gm_redmine_user_provider.api.client`.

```yaml
# services.yml
services:
    app.redmine_user_factory.api_client:
        class: AppBundle\ApiClient\CustomApiClient
        arguments:
            - "%gm_redmine_user_provider.redmine.url%"
        alias: gm_redmine_user_provider.api.client
```

## Running tests

Install the dev dependencies :

composer install --dev

Run PHPUnit test suite :

```bash
php vendor/bin/phpunit
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

## License

This bundle is released under the MIT license. See the complete license in the bundle:

```bash
src/Resources/meta/LICENSE
```
