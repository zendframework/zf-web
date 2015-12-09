<?php // @codingStandardsIgnoreFile
use League\CommonMark\CommonMarkConverter;
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$markdown = new CommonMarkConverter();

$post = new EntryEntity();
$post->setTitle('Expressive 1.0.0RC4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-12-09 15:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-12-09 15:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability of Expressive 1.0.0rc4!

You can install it using [Composer](https://getcomposer.org), using the `create-project` command:

```bash
$ composer create-project zendframework/zend-expressive-skeleton:1.0.0rc4@rc expressive
```

If you're already using Expressive, read below for how to update your application!
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Changes in RC4

The majority of the changes for RC4 centered around polishing the skeleton and
providing more documentation. One change, however, represented a bugfix, and
will impact existing installations that were making use of the `UrlHelper` from
the zend-expressive-helpers package.

Changes in this release include:

### Updates to UrlHelper registration

Two separate reports indicated that the methodology chosen for registering
`Zend\Expressive\Helper\UrlHelper` as a route result observer with the `Application`
instance was not viable. In one case, a circular dependency issue was discovered;
in the other, the `UrlHelper` instance was retrieved for the first time too late
to be triggered as a route result observer.

To solve this, we chose a path similar to the `ServerUrlHelper`: dedicated middleware
that, on invocation, registers the `UrlHelper` with the current `Application` instance
(or any `Zend\Expressive\Router\RouteResultSubjectInterface` implementation). For
existing users, this will require both upgrading your zend-expressive-helpers version,
as well as some minor changes to your configuration; see the [Upgrading](#upgrading)
section below.

### Updates to the default source structure

Prior to RC4, the `composer.json` mapped the `App` namespace to the `src/` directory,
and the `AppTest` namespace to the `test/` directory. We felt that this provided an
unreasonable limitation on application structure, and decided to change it as follows:

- We created the directory `src/App/`.
- We pushed the `src/Action/` directory to `src/App/Action/`.
- We created the directory `test/AppTest/`.
- We pushed the `test/Action/` directory to `test/AppTest/Action/`.
- We updated the autoloading entries in `composer.json` to map the `App` and
  `AppTest` namespaces to the new subdirectories.

The changes allow you to have multiple top-level namespaces under the `src/` directory,
and will help encourage a [modular structure](https://github.com/zendframework/zend-expressive-skeleton/pull/31)
(similar to ZF2 modules, Symfony bundles, Laravel packages, etc.). 

### Composer "serve" command

To simplify serving your application via the built-in PHP web server, we have added
a Composer script named "serve", which simply executes `php -S 0.0.0.0:8080 -t public/`.
You can invoke it as:

```bash
$ composer serve
```

### Caching simplification

In the skeleton application, we allow you to opt-in to configuration caching. Prior
to RC4, cached configuration was saved as a JSON serialized string; with the update
to RC4, it is now cached as a PHP file, allowing it to be pulled in via `include()`.
This approach is both simpler and more performant. Again, the [Upgrading](#upgrading)
section below will detail how to modify your existing installation to make this
change.

### Twig configuration updates

Version 0.3.0 of zend-expressive-twigrenderer made changes to the configuration format
for Twig users. While the old configuration can still be used, RC4 updates the default
configuration to follow the new recommended structure.

### zend-view configuration updates

Version 0.4.0 of zend-expressive-zendviewrenderer (zend-view integration) added
the ability to consume a configured `Zend\View\HelperPluginManager` service, when
available, and also provides a factory for it. Additionally, that factory allows
using the top-level `view_helpers` key to provide additional plugins (using standard
zend-servicemanager style configuration).

RC4 of the Expressive skeleton updates the default zend-view configuration to
register the `HelperPluginManager`, and to define the top-level `view_helpers`
configuration key.

### Cross-platform documentation

The "usage examples" section of the documentation has a section on "Hello World
Using a Configuration-Driven Container". This documentation detailed using PHP's
`glob()` function to aggregate configuration files. However, `glob()` does not
work identically on all platforms.

The documentation has been updated to use `Zend\Stdlib\Glob::glob()`, which is
a cross-platform shim for `glob()`.

### Cookbook entries

Two new entries have been added to the cookbook, one for configuring zend-view to
use helpers from other components (such as zend-form), and another detailing how
to add and configure custom zend-view view helpers.

## Upgrading

If you're already using Expressive, you'll want to upgrade! To do so, you'll
need to make a few changes to your application.

### Dependency updates

- You'll want to update zendframework/zend-expressive to RC4; this should happen on
  a `composer update`.
- You'll want to update zendframework/zend-expressive-helpers to `^1.2`, if you
  are using them.

### Configuration changes

Configuration changes are only necessary if (a) you are upgrading from a
previous release candidate, (b) using the `UrlHelper`, and/or (c) using Twig or
zend-view.

#### UrlHelper changes

For the `UrlHelper`, you will need to make the following additions to the
`config/autoload/middleware-pipeline.global.php` file:

```php
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'factories' => [
            /* ... */
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
        ],
    ],
    'middleware_pipeline' => [
        'pre_routing' => [
            // This entry was originally just for the ServerUrlMiddleware;
            // make it an array listing both that and the UrlHelperMiddleware,
            // as below:
            [
                'middleware' => [
                    Helper\ServerUrlMiddleware::class,
                    Helper\UrlHelperMiddleware::class,
                ],
            ],
            /* ... */
        ],
        'post_routing' => [
            /* ... */
        ],
    ],
    /* ... */
];
```

#### Twig changes

In the Twig configuration file, `config/autoload/templates.global.php`,
originally the structure was as follows:

```php
return [
    'dependencies' => [ /* ... */ ],
    'templates' => [
        'extension' => 'html.twig',
        'cache_dir' => 'data/cache/twig',
        'assets_url' => '/',
        'assets_version' => null,
        'paths' => [
            'app' => ['templates/app'],
            'layout' => ['templates/layout'],
            'error' => ['templates/error'],
        ],
    ],
];
```

While this will continue to work, we recommend updating to the following structure:

```php
return [
    'dependencies' => [ /* ... */ ],
    'templates' => [
        'extension' => 'html.twig',
        'paths' => [
            'app' => ['templates/app'],
            'layout' => ['templates/layout'],
            'error' => ['templates/error'],
        ],
    ],
    'twig' => [
        'cache_dir' => 'data/cache/twig',
        'assets_url' => '/',
        'assets_version' => null,
        'extensions' => [
            // extension service names or instances
        ],
    ],
];
```

#### zend-view changes

If you are upgrading from a previous release candidate, we recommend making the
following changes to your `config/autoload/templates.global.php` file:

```php
return [
    'dependencies' => [
        'factories' => [
            /* ... */
            Zend\View\HelperPluginManager::class =>
                Zend\Expressive\ZendView\HelperPluginManagerFactory::class,
        ],
    ],

    'templates' => [
        /* ... */
    ],

    // Also, add this key, to provide a place to register view helpers:
    'view_helpers' => [
        'aliases' => [ /* ... */ ],
        'invokables' => [ /* ... */ ],
        'factories' => [ /* ... */ ],
        // add other keys as necessary
    ],
]
```

### Autoloading/structure changes

If you want to bring your application fully up-to-date with the expressive skeleton,
you may want to consider creating a top-level `src/App/` directory, and pushing
your `Action/` and other subdirectories under it, and updating the `App\\` namespace
autoloading entry in `composer.json` to point to the new directory:

```
# source trees become:
src/
    App/
        Action/
test/
    AppTest/
        Action/
```

and the `autoload` and `autoload-dev` sections of `composer.json` become:

```javascript
"autoload": {
    "psr-4": {
        "App\\": "src/App/",
    }
},
"autoload-dev": {
    "psr-4": {
        "AppTest\\": "test/AppTest/",
    }
}
```

The above will allow you to start considering your middleware as discrete units
of functionality, and potentially allow you to port them betweeen applications.

## Future

In just two days, we've had quite a large number of users reporting their
feedback, and we're quite pleased that the majority of the work done for RC4
centered around documentation! The release has a lot of polish at this point,
and we anticipate a stable release in the next couple weeks.

If you are testing Expressive &mdash; whether for the first time, or updating an
existing application &mdash; please help us prepare it for general availability!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
