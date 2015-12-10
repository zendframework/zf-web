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
$post->setId('2015-12-10-zf3-biweekly-update');
$post->setTitle('Zend Framework 3 Update for 2015-12-10');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-12-10 10:50', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-12-10 10:50', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
Per our [previous ZF3 update](/blog/zend-framework-3-update-and-roadmap.html), this is the
first in an ongoing series of bi-weekly posts on ZF3 development status.

The highlights:

- &gt;80 pull requests merged
- 28 releases of components
- 9 components updated to zend-servicemanager/zend-eventmanager v3 changes
- 2 release candidates of expressive
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Pull request activity

Marco Pivetta noted yesterday on twitter:

> Receiving around 15 mails/hour from zendframework repositories: work is going on at full speed :O #zf2 #zf3
> 
> &mdash; [@Ocramius](https://twitter.com/Ocramius) [(link)](https://twitter.com/Ocramius/status/674635264371859457)

Activity has been quite high the past couple weeks, with [more than 80 pull requests merged](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+org%3Azendframework+is%3Amerged+closed%3A%3E%3D2015-11-24+)
(link requires a GitHub account). Many of these were related to the Expressive
release candidates (more on those later), but more than half were on ZF
components, and ranged from bugfixes to new features to ZF3-specific refactors.

## Component Releases

We released the following components and versions since the last update.
While not all releases are related to ZF3 specifically, this list is intended
to detail activity within the organization. One goal of splitting the various
components was to increase release velocity; we're definitely seeing that happen!

- [zend-diactoros 1.2.0](https://github.com/zendframework/zend-diactoros/releases/tag/1.2.0),
  which adds a `TextResponse` and `CallbackStream`, updates the `SapiEmitter` to emit a
  `Content-Length` header by default, and ensures the default charset of an
  `HtmlResposne` is utf-8.
- [zend-code 2.6.1](https://github.com/zendframework/zend-code/releases/tag/release-2.6.1),
  which replaces the doctrine/common dependency with the more specific doctrine/annotations.
- [zend-expressive-template](https://github.com/zendframework/zend-expressive-template), a new
  component containing the `TemplateRendererInterface` and related value objects used by
  Expressive; this allows developers to use the interface in non-Expressive applications.
- [zend-expressive-router](https://github.com/zendframework/zend-expressive-router), a new
  component containing the `RouterInterface` and related value objects used by Expressive;
  this allows developers to use the interface in non-Expressive applications.
- [zend-expressive-aurarouter 1.0.0](https://github.com/zendframework/zend-expressive-aurarouter/releases/tag/1.0.0)
  (0.3.0 was also released); the component now depends on
  zend-expressive-router instead of Expressive, and is considered stable.
- [zend-expressive-fastroute 1.0.0](https://github.com/zendframework/zend-expressive-fastroute/releases/tag/1.0.0)
  (0.3.0 was also released); the component now depends on
  zend-expressive-router instead of Expressive, and is considered stable.
- [zend-expressive-zendrouter 1.0.0](https://github.com/zendframework/zend-expressive-zendrouter/releases/tag/1.0.0)
  (0.3.0 was also released); the component now depends on
  zend-expressive-router instead of Expressive, and is considered stable.
- [zend-expressive-platesrenderer 1.0.0](https://github.com/zendframework/zend-expressive-platesrenderer/releases/tag/1.0.0)
  (0.3.0 was also released); the component now depends on
  zend-expressive-template instead of Expressive, and is considered stable.
- [zend-expressive-twigrenderer 1.0.0](https://github.com/zendframework/zend-expressive-twigrenderer/releases/tag/1.0.0)
  (0.3.0 and 0.3.1 were also released); the component now depends on
  zend-expressive-template instead of Expressive, and is considered stable.
  Additionally, the component now has a new configuration structure.
- [zend-expressive-zendviewrenderer 1.0.0](https://github.com/zendframework/zend-expressive-zendviewrenderer/releases/tag/1.0.0)
  (0.3.0, 0.3.1, 0.4.0, and 0.4.1 were also released); the component now depends on
  zend-expressive-template instead of Expressive, and is considered stable.
  Additionally, the component now has custom `url` and `serverUrl` helpers
  that work with zend-expressive-router and PSR-7, respectively.
- [zend-feed 2.6.0](https://github.com/zendframework/zend-feed/releases/tag/release-2.6.0) provides
  improvements that reduce dependencies, and allow better interoperability with other HTTP clients;
  including PSR-7-based clients.
- [zend-expressive-helpers](https://github.com/zendframework/zend-expressive-helpers), a new
  component that provides helpers for generating URI paths from configured routes, and fully-qualified
  URIs based on the current request URI.
- [zend-test 2.5.2](https://github.com/zendframework/zend-test/releases/tag/release-2.5.2) adds
  support for writing tests to use PHPUnit 5.
- [zend-eventmanager 2.6.1](https://github.com/zendframework/zend-eventmanager/releases/tag/release-2.6.1)
  updates the dependencies to make Athletic a development-only dependency.
- [zend-db 2.6.2](https://github.com/zendframework/zend-db/releases/tag/release-2.6.2) provides
  a number of bugfixes
- ZendService_Apple_Apns [1.1.2](https://github.com/zendframework/ZendService_Apple_Apns/releases/tag/release-1.1.2)
  and [1.2.0](https://github.com/zendframework/ZendService_Apple_Apns/releases/tag/release-1.2.0),
  providing a bugfix and Safari push support, respectively.

## ZF3 Refactors

Since the last update, the following components have been refactored to work
with the planned v3 versions of zend-servicemanager and zend-eventmanager.
Please note that no new versions have been released at this time; all work
remains either in pull requests or in the develop branches of each component.

- [zend-db](https://github.com/zendframework/zend-db/pull/53)
- [zend-di](https://github.com/zendframework/zend-di/pull/5)
- [zend-feed](https://github.com/zendframework/zend-feed/pull/17)
- [zend-filter](https://github.com/zendframework/zend-filter/pull/15)
- [zend-log](https://github.com/zendframework/zend-log/pull/14)
- [zend-mail](https://github.com/zendframework/zend-mail/pull/47)
- [zend-permissions-acl](https://github.com/zendframework/zend-permissions-acl/pull/7)
- [zend-tag](https://github.com/zendframework/zend-tag/pull/3)
- [zend-text](https://github.com/zendframework/zend-text/pull/2)

## Expressive Release Candidates

This week, we've issued two release candidates of Expressive, with RC4 being the latest.
Be sure to read the [RC3](/blog/expressive-1-0-0rc3-released.html) and [RC4](/blog/expressive-1-0-0rc4-released.html)
announcements to find out what changes have been made; a lot of work has occurred in the
past few weeks!

## Until next time

We'll be providing another update on either 22 or 23 December 2015 (based on holiday
vacation schedules).

Until then, if you want to help:

- There are still [component refactors to complete or review](https://github.com/zendframework/maintainers/wiki/ZF3-code-refactoring).
- We're still looking for feedback on [reducing zend-mvc dependencies](https://github.com/zendframework/zend-mvc/issues/46)
  and [identifying core components for the zf2 repository](https://github.com/zendframework/zf2/issues/7646).
- Test Expressive, and help us reach a stable release of this new feature!

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
