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
$post->setTitle('Expressive 1.0.0RC3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-12-07 12:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-12-07 12:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability of Expressive 1.0.0rc3!

You can install it using [Composer](https://getcomposer.org), using the `create-project` command:

```bash
$ composer create-project zendframework/zend-expressive-skeleton:1.0.0rc3@rc expressive
```

If you're already using Expressive, read below for how to update your application!
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Changes since RC3

RC3 shows a number of improvements, including a number of *new components* created
in order to improve interoperability with other [PSR-7](http://www.php-fig.org/psr/psr-7/)
middleware solutions.

### New Components

First, we split our routing and templating subcomponents into their own
repositories and packages:

- [zendframework/zend-expressive-router](https://github.com/zendframework/zend-expressive-router)
- [zendframework/zend-expressive-template](https://github.com/zendframework/zend-expressive-template)

These contain the code that was originally in `Zend\Expressive\Router` and
`Zend\Expressive\Template` (with some additions; see below), and the subcomponents
were removed from the Expressive tree entirely. Expressive now *depends* on
these packages. *This separation allows users of other PSR-7 middleware stacks
to use the routing and templating interfaces, as well as their implementations,
within their chosen stack.*

Next, we created a new package, [zendframework/zend-expressive-helpers](https://github.com/zendframework/zend-expressive-helpers).
This package contains utility classes and middleware useful to Expressive, but
which could be useful to other PSR-7 frameworks as well:

- `Zend\Expressive\Helper\ServerUrlHelper` provides a class for generating fully-qualified
  URIs based on the current request. When you provide a path to the helper, that path
  will be resolved based on the current request scheme and target. This helper depends
  on dedicated middleware to seed it with the current request, which is also provided
  in the package.
- `Zend\Expressive\Helper\UrlHelper` provides a class for generating URI paths
  based on the current `RouterInterface` instance present, delegating to its
  `generateUri()` method. It also has awareness of the matched `RouteResult` (more
  on that later), allowing you to generate "self" URIs, as well as URIs with
  partial parameters based on the currently matched route.

You can read about the new helpers [in the documentation](http://zend-expressive.readthedocs.org/en/latest/helpers/intro/).

## Fixes and Improvements

### RouteResult observers

In order to provide the functionality in `Zend\Expressive\Helper\UrlHelper`, we needed
a way to inform classes of the routing results. To accomplish this, we added the following
to the zend-expressive-router package:

- `Zend\Expressive\Router\RouteResultSubjectInterface`, which defines a class that will
  obtain a `RouteResult` and notify observers; and
- `Zend\Expressive\Router\RouteResultObserverInterface`, which defines a class that will
  be updated with a `RouteResult`.

`Zend\Expressive\Application` now implements the `RouteResultSubjectInterface`, and `UrlHelper`
is an example of an observer.

The documentation [now contains information on route result observers](http://zend-expressive.readthedocs.org/en/latest/router/result-observers/),
should you want more details on the feature.

A small number of fixes and improvements were also made during the RC3 lifecycle.

### Create Middleware Pipelines

[Michael Moussa](https://github.com/michaelmoussa) provided a feature to allow specifying
not just concrete middleware, but *arrays* of middleware both when creating routed middleware
as well as when adding middleware to the pre/post_routing middleware pipelines.

To illustrate:

```php
// Manually, for pipeline middleware:
$app->pipe('/api', [
    'Authentication',
    'Authorization',
    'ContentNegotiation',
    'Validation',
    'Resource',
]);

// Manually, for routed middleware:
$app->get('/api/resource[/{id:\d+}]', [
    'Authentication',
    'Authorization',
    'ContentNegotiation',
    'Validation',
    'Resource',
]);

// Via configuration, for pipeline middleware:
return [
    'middleware_pipeline' => [
        'pre_routing' => [
            [
                'path' => '/api',
                'middleware' => [
                    'Authentication',
                    'Authorization',
                    'ContentNegotiation',
                    'Validation',
                    'Resource',
                ],
            ],
        ],
    ],
];

// Via configuration, for routed middleware:
return [
    'routes' => [
        [
            'name' => 'api',
            'path' => '/api',
            'middleware' => [
                'Authentication',
                'Authorization',
                'ContentNegotiation',
                'Validation',
                'Resource',
            ],
            'allowed_method' => ['GET'],
        ],
    ],
];
```

In each case, any individual middleware in the list may be a callable
middleware, or the name of a service that resolves as middleware.

This feature should allow creating unique, complex middleware pipelines based
on specific routes a snap!

### Casting view models to arrays

One feature users of zend-view wanted was the ability to pass `ViewModel` instances
to a renderer. Prior to RC3, if you provided a view model, normalization would destroy
any variables stored in the view model due to improper casting. This has now been resolved.

### Get the Full RouteResult

The full `RouteResult` is now injected into the request as the attribute `Zend\Expressive\Router\RouteResult`.

### Fewer Silent Failures

A contributor provided [a patch](https://github.com/zendframework/zend-expressive/pull/197) that
improves the `ApplicationFactory` by raising exceptions when key services are missing, instead of
silently ignoring them. While this is a small backwards compatibility break, it provides important
information that previously led to hard-to-debug issues.

### Twig improvements

The [zendframework/zend-expressive-twigrenderer](https://github.com/zendframework/zend-expressive-twigrenderer)
package now allows you to register custom extensions. See the [0.3.0 changelog for details](https://github.com/zendframework/zend-expressive-twigrenderer/releases/tag/0.3.0)
(which is the first version introducing this capability).

### zend-view improvements

The [zendframework/zend-expressive-zendviewrenderer](https://github.com/zendframework/zend-expressive-zendviewrenderer)
package had a number of usability updates:

- It now adds the zendframework/zend-i18n package as a dependency, as it's a requirement of the PhpRenderer.
- It now provides concrete `ServerUrlHelper` and `UrlHelper` helper classes, as wrappers around the
  zendframework/zend-expressive-helpers equivalents. This change allowed reducing dependencies, and
  now allows the package to be used without Expressive.
- It now provides a factory for the `HelperPluginManager`, allowing you to provide your own
  instance, and thus custom helpers.

### Document Creating Custom 404 Handlers

[Abdul Malik Ikhsan](https://github.com/samsonasik) provided documentation covering how
to create a custom 404 handler for your application for logic such as logging.  You
[can read it in the cookbook](http://zend-expressive.readthedocs.org/en/latest/cookbook/#how-can-i-set-custom-404-page-handling).

## Upgrading

If you're already using Expressive, you'll want to upgrade! To do so, you'll
need to make a few changes to your application.

### Dependency updates

- Update `zendframework/zend-expressive` to `~1.0.0@rc || ^1.0`. This will also make
  it easier to upgrade to the stable version when it comes out.
- Update any `zendframework/zend-expressive-*` components to `^1.0`. These include
  your chosen router and template system (if any).
- Potentially add `zendframework/zend-expressive-helpers` (at `^1.1`), if you plan
  to use the `UrlHelper` or `ServerUrlHelper`. (If you're using the zend-view renderer,
  you'll already be getting this dependency.)

### Configuration changes

The only configuration changes necessary are if you want to use the new helpers. If you
won't be, and you're not using zend-view, you can skip this section.

First, add service entries for each to `config/autoload/dependencies.global.php`:

```php
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'invokables' => [
            Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
            /* ... */
        ],
        'factories' => [
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
            /* ... */
        ],
    ],
];
```

Next, you'll need to add the `ServerUrlMiddleware` to the midddleware pipeline. Edit
`config/autoload/middleware-pipeline.global.php` as follows:

```php
use Zend\Expressive\Helper;

return [
    // This section will likely be new:
    'dependencies' => [
        'factories' => [
            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
        ],
    ],
    // This section existed, but needs edits:
    'middleware_pipeline' => [
        'pre_routing' => [
            // Add the following:
            [ 'middleware' => ServerUrlMiddleware::class ],
            /* ... */
        ],
        'post_routing' => [
            /* ... */
        ],
    ],
];
```

Once these changes are made, your application should now be ready to use the helpers.

## Future

We're very excited about this release! Thanks to a large number of testers and users,
we've been able to refine the offering, and improve our ability to interoperate in the
PSR-7 ecosystem. We feel this version provides a nice sweet spot for the initial
stable features, and anticipate a stable release in the next couple weeks.

If you are testing Expressive &mdash; whether for the first time, or updating an
existing application &mdash; please help us polish the release and get it ready
for general availability!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
