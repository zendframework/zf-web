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
$post->setId('2016-01-14-zf3-biweekly-update');
$post->setTitle('Zend Framework 3 Update for 2016-01-14');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-01-14 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-01-14 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is an installment in an ongoing series of bi-weekly posts on ZF3 development status.

Following a roughly two week hiatus at the end of the year, we've picked up
where we left off and continued the momentum towards the ZF3 initiatives.

The highlights:

- 48 pull requests merged
- 11 releases of components, **including 3 3.0 releases in 3 days!**
- 6 components updated to zend-servicemanager/zend-eventmanager v3 changes
- Major updates for an upcoming Expressive RC6
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## 3.0 Releases!

A number of components reached stability in the past few weeks, and this week we
did a spree of three 3.0 releases in three days:

- [zend-servicemanager 3.0.0](https://github.com/zendframework/zend-servicemanager/releases/tag/release-3.0.0)
  is the first 3.0 release of any ZF components, and features a complete rewrite
  of the internals to provide up to 4x faster performance! We have been careful
  to retain as much backwards compatibility as possible, and the v2.7.0 release
  provides features that make migration between versions seamless for users.
  [A migration guide is available](http://zend-servicemanager.rtfd.org/en/latest/book/migration/).
- [zend-eventmanager 3.0.0](https://github.com/zendframework/zend-eventmanager/releases/tag/release-3.0.0)
  is our second 3.0 release of a ZF component, and features a complete rewrite
  of the internals of the EventManager implementation to provide up to 4X faster
  performance. By following the [migration guide](http://zend-eventmanager.readthedocs.org/en/latest/migration/intro/),
  you can update your v2 code to work on both v2 and v3.
- [zend-code 3.0.0](https://github.com/zendframework/zend-code/releases/tag/release-3.0.0)
  is our third 3.0 release of a ZF component, and features updates to allow
  usage with PHP 5.5, 5.6, and PHP 7, and, specifically, scalar typehints,
  return typehints, generators, and variadics.

Be aware that you cannot make use of these new 3.0 versions within existing ZF2
applications quite yet; we are still in the process of updating components to
work with these releases. However, they can be used standalone, or within projects
based on Expressive!

## Pull request activity

Since the last update, we've
[merged 48 pull requests](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+org%3Azendframework+is%3Amerged+closed%3A%3E%3D2015-12-22)
(link requires a GitHub account). Activity has been particularly high on
Expressive, zend-servicemanager, and components refactoring to the latest
zend-servicemanager and zend-eventmanager updates.

## Component Releases

The following is a list of component releases (other than the 3.0 releases
listed above) since the last update.  While not all releases are related to ZF3
specifically, this list is intended to detail activity within the organization.

- [zend-expressive-helpers 1.4.0](https://github.com/zendframework/zend-expressive-helpers/releases/tag/1.4.0)
  adds base path support to the `UrlHelper`.
- [Diactoros 1.3.3](https://github.com/zendframework/zend-diactoros/releases/tag/1.3.3)
  fixes an issue in `ServerRequestFactory::marshalHeaders()` whereby we were
  explicitly omitting cookie headers; they are now aggregated.
- [zend-expressive-zendrouter 1.0.1](https://github.com/zendframework/zend-expressive-zendrouter/releases/tag/1.0.1)
  fixes an issue whereby appending a trailing slash when requesting a route that
  did not define one resulted in a 405 instead of a 404 error.
- [zend-servicemanager 2.7.0](https://github.com/zendframework/zend-servicemanager/releases/tag/release-2.7.0),
  [zend-servicemanager 2.7.1](https://github.com/zendframework/zend-servicemanager/releases/tag/release-2.7.1),
  [zend-servicemanager 2.7.2](https://github.com/zendframework/zend-servicemanager/releases/tag/release-2.7.2), and
  [zend-servicemanager 2.7.3](https://github.com/zendframework/zend-servicemanager/releases/tag/release-2.7.3)
  are forwards-compatibility releases, providing several features that allow
  users to update their code to work with both the v2 and v3 series of the
  service manager.
- [zend-eventmanager 2.6.2](https://github.com/zendframework/zend-eventmanager/releases/tag/release-2.6.2)
  introduces a trait, `EventListenerIntrospectionTrait`, for use with PHPUnit
  test cases. It provides a consistent API for introspecting what events and
  listeners are attached to an EventManager instance, and provides a custom
  assertion for validating that a given listener is registered at a given
  priority on a given event. This trait can be used to write assertions for
  validating listener attachment in a way that will be forwards compatible with
  version 3.

## ZF3 Refactors

Since the last update, the following components have been refactored to work
with the planned v3 versions of zend-servicemanager and zend-eventmanager.
Please note that no new versions have been released at this time; all work
remains either in pull requests or in the develop branches of each component.

- [zend-mail](https://github.com/zendframework/zend-mail/pull/47)
- [zend-validator](https://github.com/zendframework/zend-validator/pull/49)

Additionally, we have created pull requests for several components to work with
the forwards compatibility releases of zend-servicemanager and
zend-eventmanager. These will allow us to release 2.x versions of these
components that can be used by code consuming the v3 versions of those two
components.

- [zend-barcode](https://github.com/zendframework/zend-barcode/pull/16)
- [zend-cache](https://github.com/zendframework/zend-cache/pull/64)
- [zend-log](https://github.com/zendframework/zend-log/pull/17)
- [zend-i18n](https://github.com/zendframework/zend-i18n/pull/22)
- [zend-validator](https://github.com/zendframework/zend-validator/pull/51)

## Expressive

We've had quite a number of people testing Expressive heavily, and pointing out
both its strengths and weaknesses. This has resulted in a ton of additional tests,
bringing coverage to 100% in some cases, as well as copious amounts of new
documentation.

After several issue threads and IRC conversations, we've decided to release an
additional RC, RC6, to accomplish the following:

- Simplification of the middleware pipeline; we will be doing away with the
  `pre_routing` and `post_routing` keys, and allowing a single pipeline
  representing the entire application lifecycle.
- Splitting of the routing middleware into separate routing and dispatch
  middleware. This allows developers to tie into the application lifecycle using
  middleware between routing and dispatch, facilitating such things as route-based
  authentication, validation, etc.
- Removal of auto-registration of the routing middleware; this is done to allow
  substituting alternative routing middleware and/or dispatch middleware.
- Deprecation of the route result observer system. The same functionality
  can now be accomplished with middleware that acts between routing and dispatch.

We've attempted to preserve backwards compatibility for existing applications, but
have marked deprecated features for removal with 1.1. A migration guide will assist
our early adopters in updating their applications.

## Until next time

If you want to help:

- There are new [component refactors to complete or review](https://github.com/zendframework/maintainers/wiki/ZF3-ServiceManager-component-refactors,-phase-2).
- Test Expressive, and help us reach a stable release of this new feature!

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases! In particular, I want to call out:

- [Marco Pivetta](https://github.com/Ocramius) for his work on updating
  zend-code to work with PHP 7 (and PHP 5.5, and PHP 5.6) features.
- [Michaël Gallego](https://github.com/bakura10) for his work on the
  zend-servicemanager and zend-eventmanager refactors, and his relentless pursuit
  of performance increases.
- [Enrico Zimuel](https://github.com/ezimuel) for his work on the
  zend-eventmanager refactor, and taking on the drudgery of updating components
  to the new zend-eventmanager and zend-servicemanager changes.
- [Ralf Eggert](https://github.com/RalfEggert) and [Daniel Gimenes](https://github.com/danizord)
  for the constant stream of questions and suggestions for Expressive; their
  feedback is changing it for the better!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
