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
$post->setId('2016-01-19-expressive-rc6-rc7');
$post->setTitle('Expressive 1.0.0RC6/RC7 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-01-19 13:10', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-01-19 13:10', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability
of Expressive 1.0.0rc6 and 1.0.0rc7!

You can install the latest versions using [Composer](https://getcomposer.org),
via the `create-project` command:

```bash
$ composer create-project -s rc zendframework/zend-expressive-skeleton expressive
```

You can update your existing applications using:

```bash
$ composer update
```

Unfortunately, zend-expressive RC6 introduces some breaking changes. Several
issues were raised that could not be handled in a fully backwards compatible
fashion, and we felt they were important enough to introduce before a stable
release is made. We have ensured that code honoring previous configuration
continues to work; however, deprecation notices will be raised, and this code
will be removed for the 1.1 release.

We also released zend-expressive-skeleton RC7 quick on the heels of RC6 in order
to correct an issue with installation whereby the development dependencies at
time of invocation were installed, rather than the rewritten ones. This affected
only the skeleton, which is why we're announcing RC6 *and* RC7 releases.

See below for full details on what has changed.
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Changes in zend-expressive RC6

Like RC5, the bulk of the changes merged for RC6 were documentation, including:

- a cookbook recipe detailing how to set the base path on a `UrlHelper` instance.
- two cookbook recipe detailing mechanisms for enabling localized routes.
- a cookbook recipe detailing how to add several popular debug toolbars to your applications.
- a cookbook recipe detailing how to write classes for handling multiple routes.
  (similar to controllers in MVC-oriented systems).
- a flow diagram for the "Features" chapter.

Unlike RC5, however, we introduced a significant code change, prompted by user
feedback. In particular, we saw each of the following reported multiple times:

- confusion over the `pre_routing` and `post_routing` middleware pipeline keys,
  and how they relate to routed middleware.
- requests to split the routing middleware into two distinct responsibilities:
  routing middleware, and dispatch middleware.
- requests to allow dispatching middleware when triggering route result observers.

On analysis, and in discussions with users, we decided to make the following changes.

### Splitting the routing middleware

We split the routing middleware into two discrete methods: routing and dispatch.
This solved multiple problems, and enables a number of interesting workflows.

In particular, it allows you to define middleware that can act on the routing
results in order to satisify pre-conditions in an automated way.

As an example, let's say you have a workflow where you want to:

- Authenticate a user
- Authorize the user
- Perform content negotiation
- Validate incoming body parameters

However, you don't want to perform these actions for *every* request, only
specific routes.

Previously, you would need to define an array of middleware for each route that needs
this set of responsibilities:

```php
[
    'routes' => [
        'api.ping' => [
            'path' => '/api/ping',
            'middleware' => [
                AuthenticationMiddleware::class,
                AuthorizationMiddleware::class,
                ContentNegotiationMiddleware::class,
                BodyValidationMiddleware::class,
                PingMiddleware::class,
            ],
            'allowed_methods' => ['GET'],
        ],
        'api.books' => [
            'path' => '/api/books[/{id:[a-f0-9]{8}}]',
            'middleware' => [
                AuthenticationMiddleware::class,
                AuthorizationMiddleware::class,
                ContentNegotiationMiddleware::class,
                BodyValidationMiddleware::class,
                BooksMiddleware::class,
            ],
        ],
        /* etc. */
    ],
]
```

This is repetitive, and prone to error: any change in the workflow requires propagation
to *every route*.

Splitting the routing and dispatch middleware allows you to pipe middleware
*between* the two actions, allowing you to register such workflows *once*. The middleware
could then introspect the route results to determine if they have work to do.

This means you can now write middleware like this:

```php
use Zend\Expressive\Router\RouteResult;

$authenticationMiddleware = function ($request, $response, $next) use ($map, $authenticate) {
    $routeResult = $request->getAttribute(RouteResult::class, false);
    if (! $routeResult instanceof RouteResult) {
        return $next($request, $response);
    }

    if (! in_array($routeResult->getMatchedRouteName(), $map)) {
        return $next($request, $response);
    }

    $authenticationResult = $authenticate($request);
    if (! $authenticationResult->isSuccess()) {
        // ERROR!
        return new AuthenticationErrorResponse();
    }

    return $next(
        $request->withAttribute($authenticationResult->getIdentity()),
        $response
    );
}
```

You would then sandwich it between the routing and dispatch middleware.
Programmatically, that looks like:

```php
$app->pipeRoutingMiddleware();
$app->pipe($authenticationMiddleware);
$app->pipeDispatchMiddleware();
```

We'll look at configuration later, as it changes more dramatically.

### No more auto-registration of the routing middleware

Prior to RC6, the routing middleware was auto-registered when:

- any call to `route()` was made, including those via the methods that proxy to
  it (`get()`, `post()`, `any()`, etc.).
- as soon as the `Application` instance was invoked as middleware (i.e., by
  calling `$app($request, $response)` or calling `$app->run()`).

You could also always register it manually when creating your application
pipeline using the `pipeRoutingMiddleware()` method.

Because routing was split into two distinct actions, and one primary purpose for
doing so was to allow registering middleware between those actions, we felt that
auto-registration was not only no longer useful, but a liability.

As such, when creating your application programmatically, there is now *exactly
one workflow* to use to enable the routing and dispatch middleware: each must
be piped explicitly into the pipeline:

```php
$app->pipe(ServerUrlMiddleware::class);
$app->pipe(BaseParamsMiddleware::class);
$app->pipeRoutingMiddleware();
$app->pipe(UrlHelperMiddleware::class);
$app->pipeDispatchMiddleware();
```

**If you are building your application programmatically, you *must* update it to
pipe the routing and dispatch middleware in order for it to continue to
work.**

We'll look at configuration for the `ApplicationFactory` later, as it changes as
well.

### No more route result observers

Another consequence of splitting the routing middleware in two was a pleasant discovery:
there was no longer any need for the route result observer system!

The route result observer system was added in RC3 to allow the application to notify
interested observers of the results of routing, as there was no other way to trigger
functionality between the act of routing and when the matched middleware was dispatched
(if any was actually matched!).

Several developers complained that they couldn't return a response from these
observers when they detected an error condition, nor could they introspect the request
in such situations.

With the routing middleware split, there's an answer to those questions, and the
observer system is no longer needed; just place middleware between the routing
and dispatch middleware, and have it act on the `RouteResult` instance (which the
routing middleware injects as a request attribute). In fact, we've already demonstrated
this above!

For RC6, we removed the `RouteResultSubjectInterface` implementation from the
`Application` instance, while keeping the original methods defined in that interface;
these methods now trigger deprecation notices. If you were using observers
previously, and keep your existing RC5 configuration, we also inject a special
"route result observer middleware" between the routing and dispatch middleware that
will notify the observers. The deprecation messages will prompt you to update
your code, and provide a link to the migration guide to help you.

A new *minor* version of zend-expressive-router was released, v1.2.0, marking each of
the `RouteResultSubjectInterface` and `RouteResultObserverInterface` as deprecated.

A new *major* version of zend-expressive-helpers was released, v2.0.0, that removes
the `RouteResultObserverInterface` implementation from the `UrlHelper`, and updates
its related middleware to act between the routing and dispatch middleware.

### Simplified configuration

We've alluded to configuration changes several times; it's now time to detail those.

One common confusion that arose was around the `pre_routing` and `post_routing` names.
Many assumed that `pre_routing` meant that the middleware listed only operated before
routing &mdash; and did not realize that such middleware could also post-process
responses. Similarly, many assumed that `post_routing` middleware was executed after
routed middleware, even when the routed middleware returned a response (it was only
executed if the routed middleware called `$next()` or if an error occurred).

We wanted to clarify how the middleware pipeline worked, and with the switch to split
the routing and dispatch middleware, and a desire to allow injecting middleware between
routing and dispatch, we had an opportunity to positiveily change the configuration to
make it more clear.

[Enrico](http://www.zimuel.it) suggested that instead of segregating into pre/post, we
have a single pipeline. This would require defining entries for the routing and
dispatch middleware as part of the pipeline, but you would then be able to see the
exact workflow.

One counter-argument, however, is when merging configuration, which is done by default
in the skeleton, and which is a recommended practice to keep configuration for related
functionality in discrete places. How would order be preserved?

We decided to introduce a `priority` key into our middleware configuration specifications.
This works with `SplPriorityQueue`: higher values are piped earlier and execute
earlier, while lower/negative values are piped later. This provides the ability to
define the pipeline across multiple files, merge it, and get a predictable order.

Additionally, we realized we could lever another existing feature: middleware
specifications used by the pipeline configuration allow you to specify *lists*
of middleware to execute, not just individual middleware. This means that you can
group middleware under the same priority, in the order you want it to execute. This
is a great technique for segregating configuration.

What we came up with ends up looking like this when you start out with the new
skeleton:

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
        ],
    ],
];
```

For existing users:

- Existing RC5 and earlier configuration is still honored, but will emit
  deprecation notices, prompting you to update; these notices include
  links to the migration guide.
- To update, you'll need to:
  - update your zend-expressive-helpers version constraint to `^2.0`.
  - update your configuration, using the above as a guide.

We're excited about this change, as we feel it simplifies the configuration, adds
flexibility, and provides predictability in the system. While it is a large change
for a release candidate, we also felt it was important enough to warrant introducing
before the stable release.

## Full migration details

The above narrative is use-case-centered. We have, however, published a [full migration
guide](http://zend-expressive.readthedocs.org/en/latest/migration/rc-to-v1/) as part of
the release to give exact details on changes you will need to make.

## Future

At this point, we feel that the code has stabilized significantly, and that the improvements
in these latest releases have provided important simplicity and flexibility to make
the system robust. We'll be waiting a week or two to see how you, our users, respond,
and hopefully be able to tag a stable release shortly!

If you are testing Expressive &mdash; whether for the first time, or updating an
existing application &mdash; please help us prepare it for general availability!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
