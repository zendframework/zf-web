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
$post->setId('2016-01-21-expressive-rc7-rc8');
$post->setTitle('Expressive 1.0.0RC7/RC8 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-01-21 10:35', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-01-21 10:35', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability
of Expressive 1.0.0rc7 and the Expressive Skeleton and Installer 1.0.0rc8!

You can install the latest versions using [Composer](https://getcomposer.org),
via the `create-project` command:

```bash
$ composer create-project -s rc zendframework/zend-expressive-skeleton expressive
```

You can update your existing applications using:

```bash
$ composer update
```

This release candidate contains bug fixes for dispatching error middleware
pipelines. Additionally, we've released a new version of our Twig integration,
and detail those changes below.

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Changes in zend-expressive RC7

RC6 updated the configuration for the middleware pipeline to make it a single pipeline.
We recommended that developers make use of our middleware grouping feature, however,
which allows you to specify not just a single, named middleware service, but an
*array* of named middleware services. This feature is great for grouping middleware
based on when it should execute, and makes ordering related middleware simpler.

Per our suggested, default configuration:

```php
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper;

return [
    'dependencies' => [
        'factories' => [
            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
        ],
    ],
    // This can be used to seed pre- and/or post-routing middleware
    'middleware_pipeline' => [
        // An array of middleware to register. Each item is of the following
        // specification:
        //
        // [
        //  Required:
        //     'middleware' => 'Name or array of names of middleware services and/or callables',
        //  Optional:
        //     'path'     => '/path/to/match', // string; literal path prefix to match
        //                                     // middleware will not execute
        //                                     // if path does not match!
        //     'error'    => true, // boolean; true for error middleware
        //     'priority' => 1, // int; higher values == register early;
        //                      // lower/negative == register last;
        //                      // default is 1, if none is provided.
        // ],
        //
        // While the ApplicationFactory ignores the keys associated with
        // specifications, they can be used to allow merging related values
        // defined in multiple configuration files/locations. This file defines
        // some conventional keys for middleware to execute early, routing
        // middleware, and error middleware.
        'always' => [
            'middleware' => [
                // Add more middleware here that you want to execute on
                // every request:
                // - bootstrapping
                // - pre-conditions
                // - modifications to outgoing responses
                Helper\ServerUrlMiddleware::class,
            ],
            'priority' => 10000,
        ],

        'routing' => [
            'middleware' => [
                ApplicationFactory::ROUTING_MIDDLEWARE,
                Helper\UrlHelperMiddleware::class,
                // Add more middleware here that needs to introspect the routing
                // results; this might include:
                // - route-based authentication
                // - route-based validation
                // - etc.
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ],
            'priority' => 1,
        ],

        'error' => [
            'middleware' => [
                // Add error middleware here.
            ],
            'priority' => -10000,
            'error' => true,
        ],
    ],
];
```

Unfortunately, for *error middleware*, this was not working correctly.

Internally, when we encounter an array of middleware, we create a
`Zend\Stratigility\MiddlewarePipe` instance, and pipe each middleware
service to it in order. The problem is that `MiddlewarePipe` does not
implement the error middleware signature &mdash; which meant that
error middleware pipelines were completely skipped!

To make this work, we introduced a proxy class, `Zend\Expressive\ErrorMiddlewarePipe`,
which wraps a `MiddlewarePipe`, and exposes the error middleware signature.
This is now used internally whenever an error middleware pipeline needs
to be created.

## Changes in zend-expressive-skeleton RC8

When we created the new default middleware pipeline configuration for RC6/RC7, we
forgot one important detail: the `error` middleware group was missing its
`error` key, meaning it wasn't attempting to create error middleware at all!
We've fixed this in RC8.

If you upgraded to RC6/RC7 earlier this week, make sure you add that `error`
key, as detailed in the above example.

## Twig integration updates

Today we released [version 1.0.1 of our Twig integration](https://github.com/zendframework/zend-expressive-twigrenderer/releases/tag/1.1.0).
This includes a few new features:

- It adds a dependency on zend-expressive-helpers and, if the `UrlHelper` and `ServerUrlHelper`
  services are registered, makes new `url` and `absolute_url` template functions available.
- It adds a new "globals" configuration sub-section for registering variables to pass
  to all templates.

You can read more in the [Twig integration documentation](http://zend-expressive.readthedocs.org/en/latest/template/twig/).

Many thanks to [Geert Eltink](https://xtreamwayz.com) for these new features!

## Future

Code is stabilizing, and we're seeing fewer issues hitting our issue tracker.
We hope that in a week or two we can release a stable version.

If you are testing Expressive &mdash; whether for the first time, or updating an
existing application &mdash; please help us prepare it for general availability!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
