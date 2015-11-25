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
$post->setTitle('Zend Framework 3 Update and Roadmap');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-11-25 15:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-11-25 15:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
In October, while at ZendCon, I presented a talk on Zend Framework 3 entitled
"Components, PSR-7, and Middleware: Zend Framework 3." You can
[view it online](http://weierophinney.github.io/2015-10-22-ZF3/), but this post
discusses current status, details some decisions, and points to the work still
to be done.  It's a long read; grab a warm beverage, maybe some popcorn, and
take your time.
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Prelude

Zend Framework 3 is not just a new release of the framework. It's an *initiative*,
encompassing a number of strategies and projects. Do not get hung up on when the
main Zend Framework repository will be tagged as 3.0; the MVC is just one part
of an overall plan. This post details those parts; many of them are *already
accomplished.* Hopefully, by the end of this post, you'll see what you can
already leverage, and what you *may already be leveraging.*

Mirroring my ZendCon presentation, I've broken this out into four primary parts:
Components, PSR-7, Middleware, and Zend Framework 3.

## Components

Zend Framework was originally envisioned and positioned as a component library
that *also* shipped an MVC framework. Unlike [PEAR](http://pear.php.net), it was
all-or-nothing; if you wanted a single component, you downloaded the entire
framework; if you wanted to use the MVC framework, you downloaded the entire
framework. Over time, the MVC became the predominant feature, and most code was
written or adapted to ensure the various components worked properly when used
with the framework, with very little emphasis on standalone usage.

When we started on ZF2, we essentially continued down this path. However, early
in the ZF2 development, [Composer](https://getcomposer.org) emerged in the PHP
ecosystem, and we decided to leverage that both for distribution of ZF itself,
but also for its components. However, the semi-manual/semi-automated approach we
used made components second-class citizens, requiring them to be versioned
simultaneously, resulting in slower releases.

As a result, a primary goal of the ZF3 initiatives was to split the components
into their own repositories, with their own development cycles; the main Zend
Framework repository then becomes a meta-package, defining the individual
components as dependencies, but shipping no actual code.

The split took quite some time to orchestrate, but
[was accomplished in May](https://mwop.net/blog/2015-05-15-splitting-components-with-git.html),
with the help of [Gianluca Arbezzano](https://github.com/gianarb) and
[Corley](http://www.corley.it/), and released as version 2.5. 

I'll be following up this post with some of the benefits we've gained from the
split, but the overall point is that the separation will help us improve
components more granularly, expand the number of contributors, and accelerate
component development.

Composer has been wildly successful. It simplifies and streamlines the ability
to manage application dependencies, as well as consume them in your code (by
providing a common autoloader for all dependencies). Our observation is that an
increasing number of developers and companies are choosing to piece together
bespoke frameworks targeted at their business needs using commodity components.
Splitting component lifecycles facilitates usage of ZF components in these
paradigms.

## PSR-7

[PSR-7](http://www.php-fig.org/psr/psr-7/) ([meta](http://www.php-fig.org/psr/psr-7/meta/))
defines a set of HTTP message interfaces. PHP Standard Recommendations (PSR) are
a product of the [Framework Interop Group](http://www.php-fig.org/), which
exists to identify existing practices and development approaches, and
standardize them, with the goal of increasing interoperability between
frameworks and libraries. Composer is the fruit of the very first PSR,
[PSR-0](http://www.php-fig.org/psr/psr-0/), which provided a common methodology
around autoloading.

PSR-7 exists because PHP, for all its web centricity, does not actually model
HTTP messages. Most frameworks have provided message abstraction of one form or
another since 2005, but they all differ, which means migrating from one
framework or HTTP client library to another &mdash; or even one **version** of
such a project to another &mdash; requires learning a new system for dealing
with HTTP messages.

Interestingly, other languages, including Python, Ruby, and Node.js, *do*
provide common HTTP message abstractions, and the result is that code written
targeting HTTP messages will typically work regardless of the framework chosen.
This leads to a lot of cross-pollination, and allows developers to pick and
choose libraries based on their strengths and features, not on the framework.

Many of us in the PHP community feel that HTTP message abstractions should be a
*commodity*. 

PSR-7 accomplishes this, and code targeting PSR-7 can thus be re-used by any
framework or project that also consumes PSR-7.

PSR-7 was accepted in mid-May; the same day it was accepted, we released:

- [Diactoros](https://github.com/zendframework/zend-diactoros), a PSR-7
  implementation.
- [Stratigility](https://github.com/zendframework/zend-stratigility), a
  PSR-7 middleware foundation inspired by [Sencha Connect](https://github.com/senchalabs/connect).

We feel that PSR-7 is the future of PHP interoperability when writing
HTTP-centric applications, and these components form a foundation for projects
that choose to target PSR-7.

## Middleware and Expressive

When describing Stratigility in the previous section, I used the term
"middleware." What is middleware?

Middleware is, quite simply, code sitting between an incoming HTTP request, and
the outgoing HTTP response. There are a number of different middleware
signatures floating around (subject for a pending blog post!), but the one we've
implemented in Stratigility is:

```php
function (
    ServerRequestInterface $request
    ResponseInterface $response,
    callable $next
) : ResponseInterface
```

where `$next` can be used to invoke the next middleware in the system, if any.
This same signature is being adopted by a number of emerging PSR-7
centric projects such as [Relay](http://relayphp.com), and [Slim v3](http://www.slimframework.com/2015/02/11/whats-up-with-version-3.html).

[Expressive](http://framework.zend.com/expressive) is a new *microframework* for
building PSR-7 middleware applications.

Built on top of Stratigility, Expressive is meant to provide minimal plumbing
for your applications. A primary goal is to allow *you* to choose the components
you want, and then to provide minimal wiring to get you started. It provides:

- typehinting against [container-interop](https://github.com/container-interop/container-interop)
  allowing *you* to select a service container from which to pull middleware
  once matched.
- a `RouterInterface`, and several implementations, so you can choose a routing
  implementation that best suits your application needs.
- a `TemplateRendererInterface`, and several implementations, so you can choose
  a template engine that suits the needs of the middleware you write that may
  use templating &mdash; and allow you to swap out engines seamlessly.
- an error handling mechanism, and choices for how to handle errors in both
  development and production.

We leverage Composer's installation hooks to prompt you for your choices (thanks
for the contribution, [Geert](https://xtreamwayz.com)!), so that once you
install the Expressive skeleton, you're prepared to start developing
immediately.

Expressive is currently in release candidate status, and we hope to finalize a
stable release soon!

We like middleware because:

- it tends to be very focused and small, and thus readily understood.
- it adapts the [Unix philosphy](https://en.wikipedia.org/wiki/Unix_philosophy)
  (creating complex behavior by piping messages between single-purpose tools)
  to HTTP applications.
- it tends to be quite performant.

Any middleware that targets PSR-7 also gains the ability to interop with any
other system targeting PSR-7. This means that the ecosystem for middleware users
is not a single framework, but any framework that uses PSR-7. We're already
seeing [amazing middleware libraries](http://github.com/oscarotero/psr7-middlewares)
popping up, and these will work *across the PHP ecosystem*. Providing a
middleware microframework via Expressive allows our users to capitalize on this.

## Zend Framework 3

For many, "Zend Framework 3" means the MVC framework; as such, much of the above
may feel like a sideshow, not the main thrust of the new version. This is a
misconception.

We decided early this year that we will *not* be changing the MVC significantly.

When we went from version 1 to version 2, we did a complete rewrite of the MVC.
While the new MVC is architecturally superior, its completely different
structure meant there was zero way to automate migration, which left many ZF1
users stranded. We did not want to repeat this mistake.

Additionally, the primary issues around the current MVC are:

- performance
- interoperability (specifically, ability to use middleware)

These are things we *can* tackle, while retaining most, if not all, backwards
compatibility.

As such, the primary changes we identified were:

- zend-servicemanager performance
- zend-eventmanager performance
- ability to dispatch PSR-7 middleware
- reduction of dependencies

### ServiceManager

We feel zend-servicemanager offers some unique features that are not found in
other containers:

- lazy services
- delegator services
- interface injection (via initializers)
- abstract factories

As such, there are tremendous reasons to choose it over other containers.
However, when you have large object graphs, and if you're heavily using features
such as abstract factories and initializers, the design in v2 can become
tremendously slow.

[Michäel Gallego](http://www.michaelgallego.fr) did some deep analysis of the
service manager, and identified ways the performance could be radically
improved, providing a hefty patch to do so. The main issue was that much of the
code for loading services was checking for state changes in the container; as
such, the main thrust of the patch Michäel provided was to have state changes
&mdash; additions of factories to the container &mdash; reconfigure the
container, so that *pulling* from the container becomes cheap. The result is a
4X performance boost that is *mostly* backwards compatible!

There are a few BC breaks with this change, however, which means any component
that provides factories is requiring updates to be compatible. These are mostly
minor, and we're currently working on ways we may be able to make code forwards
compatible while retaining backwards compatibility.

You can read about the changes in the [migration guide](https://github.com/zendframework/zend-servicemanager/blob/develop/doc/book/migration.md).

### EventManager

Similarly, zend-eventmanager is a unique offering, providing mechanisms for:

- intercepting filters
- subject/observer
- signal slots
- events

In order to accomplish this, however, it has a lot of code around checking for
changes in shared listeners. Additionally, it has leveraged shared solutions
such as the `PriorityQueue` implementation in zend-stdlib, which provide
necessary features, but often at a performance cost.

Michäel Gallego, along with [Enrico Zimuel](http://www.zimuel.it), performed
comprehensive profiling, and provided a refactor of the component that resulted
in 4X performance benefits!

You can read about the changes in the [migration guide](https://github.com/zendframework/zend-eventmanager/tree/develop/doc/book/migration).
Of particular interest is that the 2.7 version provides forwards compatibility
features allowing you to prepare your applications *now* for version 3!

### Dispatching Middleware

As noted in the previous section on PSR-7, we feel that the future of
PHP web applications is in middleware. We want users to benefit from the
middleware ecosystem, but also to migrate to it. To enable this, we decided to
build a `MiddlewareListener` for zend-mvc.

First, though, we had to build [a PSR-7 bridge](https://github.com/zendframework/zend-psr7bridge),
to allow translation of the zend-http request and response messages already
present in zend-mvc to PSR-7, and vice versa. (We chose *not* to use PSR-7
directly in zend-mvc, as doing so would require changes anywhere you were
previously using the request and/or response objects.) This code can be used
now, anywhere you need to do such translations. 

With that out of the way, we developed the `MiddlewareListener`. In v3, this
will be registered by default, at a higher priority than the standard
`DispatchListener`. If it detects a `middleware` key in the route matches, it
will pull that middleware from the container and dispatch it, using the PSR-7
bridge; otherwise, it will return early, allowing the `DispatchListener` to take
over.

The `MiddlewareListener` thus becomes your migration path from the zend-mvc to
Expressive or other middleware stacks, but also allows you to compose middleware
from the greater ecosystem in your zend-mvc applications!

This feature is available currently on the develop branch of zend-mvc, and will
be released with v3 of that component.

### Reducing Dependencies

Currently, the framework repository requires *every* Zend Framework component
(except the new ones such as Diactoros, Stratigility, Expressive, and the PSR-7
bridge). This poses a problem: what if we want to update another component
earlier than others? How will users then opt-in to such new versions?

As an example, we're pushing back plans for refactoring the filter, validator,
input filter, and form components, as the proposed changes will take quite some
time. However, not every application *needs* these facilities, and those that do
*should* be able to selectively upgrade. But if we pin to semantic versions
&mdash; e.g., `~3.0` &mdash; users will not be able to do so until the framework
upgrades, making it an all or nothing approach.

As such, we've decided to change the requirements for zend-mvc, the framework
repository, and the skeleton to the bare minimum needed for an MVC application.
We're still scoping this effort, however, so there's time to get your feedback
considered.

This will, of course, affect existing applications. You will need to add in
dependencies that previously were assumed. Composer, however, makes these
relatively trivial:

```bash
$ composer require zendframework/zend-form
$ composer require zendframework/zend-session
$ composer require zendframework/zend-paginator
```

The more problematic part of this will be registration of abstract factories,
plugin managers, etc. We're still working on a plan for that, and encourage you
[to share any ideas you might have around it](https://github.com/zendframework/zend-mvc/issues/46).

## Documentation

One area where Zend Framework is consistently criticized is its documentation.

- We don't have enough documentation
- Documentation isn't updated to reflect new features.
- Documentation doesn't detail how to consume a component within the MVC
  framework; or
- Documentation doesn't detail how to use the component standalone.

With the split to component repositories, we can tackle some of this more
easily. We are in the process of moving all documentation into the relevant
component repositories, which allows us to block merging of features based on
lack of contributed documentation. This will help us keep the documentation
up-to-date.

However, we need help *writing* documentation. We need *you* to indicate what
documentation you feel is missing &mdash; and, better yet, *contribute* that
documentation, to help others in the same situation. One reflection I've made is
that writing documentation often also points to ways to improve the code; don't
discount writing documentation as a non-coding activity!

The documentation migration is being faciliated by [Gary Hockin](http://blog.hock.in).
He is automating the migration via a series of scripts, and also creating issues
on each repository indicating common updates that need to happen to fully
complete the transition from reStructured Text to Markdown. You can help by
perusing the list available at the link below, and submitting pull requests:

- [Documentation migration issue list](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aopen+is%3Aissue+org%3Azendframework+label%3Adocumentation+label%3AEasyFix)

At the time of writing, he has not yet run the script over all repositories, but
indicates that he should accomplish this feat within the next 10 days; as such,
keep checking that link!

## Roadmap

As noted, we've made significant progress since announcing the ZF3 initiative in
March. We still have a ways to go, however:

- We're still finalizing changes to Expressive prior to a stable release.
- We're only halfway through the
  [list of components needing service manager and/or event manager migrations](https://github.com/zendframework/maintainers/wiki/ZF3-code-refactoring),
  and could use some assistance completing this task. We cannot do a zend-mvc
  beta currently until this is done.
- We're still identifying what components will be considered "core" to the MVC,
  and could [use your feedback](https://github.com/zendframework/zend-mvc/issues/46).
- Related, we're still identifying what components will be considered "core" to
  the framework, if the list is not identical to those in the MVC; again,
  [feedback is welcome](https://github.com/zendframework/zf2/issues/7646).

For a number of considerations, we cannot at this time create a date-based
roadmap; we will do releases when code is ready and meets the project quality
guidelines. The links above, and in the documentation section, provide ways that
you can help; the more help we get, the sooner we can potentially release.

## Closing Notes

First, this post was long, and also long overdue. My plan going forward is to
provide bi-weekly updates on the [Zend Framework blog](http://framework.zend.com/blog/), 
so that you, Zend Framework's users and development community, can keep track
of progress. In those, I will also be listing areas where we can particularly
use contributions. Be aware, however, that with holidays coming up in many countries,
that progress will be slow in the short-term.

We're very excited about the Zend Framework 3 _**initiative**_. It's a change in
direction for the framework, returning to its roots as a component library
first, which happens to also provide a full-stack framework.

We see ZF3 as a movement: an end to framework silos, by providing quality,
commodity code that can be used everwhere and anywhere. An end to saying "I'm a
ZF developer," or "I'm a Laravel developer," and a return to, "I'm a PHP
developer." We hope you'll help us complete that journey!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
