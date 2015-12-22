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
$post->setId('2015-12-22-zf3-biweekly-update');
$post->setTitle('Zend Framework 3 Update for 2015-12-22');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-12-22 17:10', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-12-22 17:10', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is the second in an ongoing series of bi-weekly posts on ZF3 development status.

The highlights:

- 45 pull requests merged
- 14 releases of components
- 5 components updated to zend-servicemanager/zend-eventmanager v3 changes
- 1 release candidates of expressive
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Pull request activity

Activity has continued to be quite high the past couple weeks, though slower
than the previous update, with [45 pull requests merged](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+org%3Azendframework+is%3Amerged+closed%3A%3E%3D2015-12-10)
(link requires a GitHub account). While Expressive still dominates the list, many
of these were related to ZF3 refactors and ongoing component maintenance.

## Component Releases

The following is a list of component releases since the last update.
While not all releases are related to ZF3 specifically, this list is intended
to detail activity within the organization. It omits the Expressive release
candidates, as well as new components, which will be listed later in this post.

- [zend-expressive-fastroute 1.0.1](https://github.com/zendframework/zend-expressive-fastroute/releases/tag/1.0.1)
  released 2015-12-14; fixes an issue with the returned `RouteResult`, ensuring
  it contains the name, not the path, of the route matched.
- [zend-diactoros 1.2.1](https://github.com/zendframework/zend-diactoros/releases/tag/1.2.1)
  was a bugfix release that fixed:
  - issues with how `withHeader()` handled replacing existing headers that used
    a different casing strategy.
  - the `$statusCode` argument of the `Response` to never allow `null` values.
  - constructor header validation on all message types to:
    - allow numeric values (e.g., Content-Length)
    - raise an exception on invalid header names (non-empty strings or non-string values)
    - raise an exception on invalid individual header values (non strings/non-numerics)
- [zend-diactoros 1.3.0](https://github.com/zendframework/zend-diactoros/releases/tag/1.3.0)
  was a feature release adding:
  - `SapiEmitterTrait`, which replaces a number of methods in the `SapiEmitter`
    to provide a re-useable base for emitters; `SapiEmitter` was updated to use
    the trait instead of to directly define the methods.
  - `SapiStreamEmitter` provides functionality for iteratively emitting
    stream-based responses, and includes support for Content-Range headers.
- [zend-diactoros 1.3.1](https://github.com/zendframework/zend-diactoros/releases/tag/1.3.1)
  was a bugfix release that fixed:
  - an issue in the response serializer, whereby the discovered status code
    wasn't being cast to an integer.
  - an issue in the various concrete, text-based response types whereby they
    were not rewinding the message body stream after creation, causing later
    calls to `getContents()` to return an empty string (as it was starting from
    the end of the stream). These now rewind the stream during initialization.
- [zend-diactoros 1.3.2](https://github.com/zendframework/zend-diactoros/releases/tag/1.3.2)
  fixes an issue in the `ServerRequestFactory` whereby we were omitting parsing
  for and injection of the HTTP protocol version.
- [zend-psr7bridge 0.2.1](https://github.com/zendframework/zend-psr7bridge/releases/tag/0.2.1)
  adds support for injecting generated PSR-7 instances with the cookies present
  in the zend-http request instance.
- [zend-math 2.5.2](https://github.com/zendframework/zend-math/releases/tag/2.5.2) fixes
  base conversions for base36 and below.
- [zend-server 2.6.0](https://github.com/zendframework/zend-server/releases/tag/release-2.6.0)
  adds support for unwinding `{@inheritdoc}` annotations, and fixes a misleading
  exception in `reflectFunction`.
- [zf-development-mode 2.1.2](https://github.com/zfcampus/zf-development-mode/releases/tag/release-2.1.2)
  fixes the factory to pull and set the configuration caching rules under the
  correct configuration key.
- [zend-expressive-helpers 1.2.1](https://github.com/zendframework/zend-expressive-helpers/releases/tag/1.2.1)
  adds the protected method `getRouteResult()`, to allow extensions access to
  the route result instance.
- [zend-expressive-helpers 1.3.0](https://github.com/zendframework/zend-expressive-helpers/releases/tag/1.3.0)
  adds a new general-purpose `BodyParamsMiddleware`, for parsing the request
  body and returning a new instance populated with the parsed body parameters.
  The solution uses a strategy pattern, allowing developers to provide
  additional strategies per their application needs.

## ZF3 Refactors

Since the last update, the following components have been refactored to work
with the planned v3 versions of zend-servicemanager and zend-eventmanager.
Please note that no new versions have been released at this time; all work
remains either in pull requests or in the develop branches of each component.

- [zend-session](https://github.com/zendframework/zend-session/pull/8)
- [zend-log](https://github.com/zendframework/zend-log/pull/14)
- [zend-tag](https://github.com/zendframework/zend-tag/pull/3)
- [zend-text](https://github.com/zendframework/zend-text/pull/2)
- [zend-filter](https://github.com/zendframework/zend-filter/pull/15)

## Component Installer

One idea floated for helping the goal of reducing dependencies in both zend-mvc
and the zf2 meta-repository is to have components also act as modules. This would
allow them to provide configuration, factories, and event listeners to the MVC
runtime in a completely opt-in fashion. The one problem with the approach,
however, is automating registration with the application.

To this end, we created a [component installer](https://zendframework.github.io/zend-component-installer).
This package provides composer post un/install scripts that look for metadata
in the package; if the metadata is present, the script adds an entry to the
application's module list. Components are added to the top of the list, and
modules to the bottom.

The new package can be installed as a global composer utility, or downloaded
as a self-updateable PHAR file. We will begin updating components to expose
themselves to this tooling soon, and update the application skeleton with the
scripts as generated by the package, to automate the facilities for greenfield
projects.

## Expressive Release Candidate

Today, we issued a new release candidate of Expressive, RC5.
Be sure to read the [announcement](/blog/expressive-1-0-0rc5-released.html) to
find out what changes have been made! The bulk of the work has gone into expanding
the documentation to cover common use case scenarios.

## Until next time

The winter holidays are upon our team at this time, and we do not expect much progress
in the coming two weeks. Look for updates in January!

Until then, if you want to help:

- There are still [component refactors to complete or review](https://github.com/zendframework/maintainers/wiki/ZF3-code-refactoring).
- Test Expressive, and help us reach a stable release of this new feature!

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
