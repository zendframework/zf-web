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
$post->setId('2016-04-21-zf3-update');
$post->setTitle('Zend Framework 3 Update for 2016-04-21');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-04-21 16:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-04-21 16:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is an installment in an ongoing series of posts on ZF3 development status.
In the last three weeks, we've done a lot:

- ~160 pull requests merged, and ~110 issues closed.
- Over 60 component releases.
- Completion of the zend-mvc version 3 refactors.
- All components are now forwards-compatible with existing v3 releases, including those that depend on zend-stdlib.
- Progress on the documentation initiatives, including 11 new components documented.
- Announcement of issue closures.

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## MVC Refactor

In the [previous update](/blog/2016-03-24-zf3-update.html), we provided a
[roadmap for the zend-mvc v3 refactor](https://github.com/zendframework/maintainers/wiki/zend-mvc-v3-refactor:-reduce-components);
at the time, we'd just begun the initiative, but still had the bulk of the work remaining.

As of last week, however, we have completed all tasks related to the refactor! These include:

- a component installer Composer plugin, which will automatically inject
  installed components into application configuration as modules. (It is also
  forwards-compatible with upcoming Expressive releases!)
- console functionality as a separate component ([zend-mvc-console](https://zendframework.github.io/zend-mvc-console)).
- separation of controller plugins with additional dependencies into their own
  packages, including:
  - [PostRedirectGet](https://zendframework.github.io/zend-mvc-plugin-prg)
  - [FilePostRedirectGet](https://zendframework.github.io/zend-mvc-plugin-fileprg)
  - [FlashMessenger](https://zendframework.github.io/zend-mvc-plugin-flashmessenger)
  - [Identity](https://zendframework.github.io/zend-mvc-plugin-identity)
- separation of i18n integration into a separate component ([zend-mvc-i18n](https://zendframework.github.io/zend-mvc-i18n)).
- separation of the code for wiring zend-di into zend-servicemanager into a
  dedicated component ([zend-servicemanager-di](https://zendframework.github.io/zend-servicemanager-di)).
- removal of all factories and integrations with components that fall outside
  the core dependencies.

This latter required that we move the various factories, service integrations,
and event listener wiring code into the components themselves. This affected
eight components, though we ended up addressing another five components that were
already defining factories for zend-servicemanager as well:

- zend-filter
- zend-form
- zend-hydrator
- zend-inputfilter
- zend-log
- zend-navigation
- zend-serializer
- zend-validator
- zend-cache
- zend-db
- zend-mail
- zend-paginator
- zend-session

For each of these, we created two new classes in their defined namespaces,
`ConfigProvider` and `Module`. The first is an invokable class returning
configuration, which might include service configuration, plugin configuration,
etc. `Module` is a class specific to the Zend Framework ecosystem, and returns
configuration, but, in several cases, also wired other code into the zend-mvc
workflow. All of the above components received new minor releases once these
were in place, and zend-mvc was updated to remove dependencies on them.

The core dependencies in zend-mvc are now:

- zend-eventmanager
- zend-http
- zend-modulemanager
- zend-router
- zend-servicemanager
- zend-stdlib
- zend-view

Once we were done, the lines of code had dropped to approximately 25% of the size
in the version 2 releases!

## Skeleton application

With the zend-mvc refactor complete, we decided to work on the skeleton application.

Feedback we've had includes:

- While having i18n support is interesting in terms of seeing how it's done,
  it's mostly worthless in the skeleton application. The provided translations are
  only valid for the home page shipped with the skeleton, which is replaced essentially
  100% of the time with custom content. Additionally, it poses maintenance overhead
  with regards to reviewing and accepting new translations. Finally, with the split
  of zend-mvc-i18n, keeping it in meant adding additional dependencies which might
  never be used.
- Related, we've had a lot of folks indicate that they'd like an option for a minimal
  skeleton. Many developers don't want the i18n, console, forms, cache, logging, and
  other facilities, or want to pick and choose which ones they configure.
- As PSR-0 is deprecated, our skeleton should reflect PSR-4 for the default
  `Application` module.
- Related, we want to encourage using composer for all autoloading.

To get the ball rolling, I created a [pull request proposing a streamlined skeleton](https://github.com/zendframework/ZendSkeletonApplication/pull/329).
This has already generated a fair bit of discussion, and we have a number of new
ideas we're going to investigate, including setting up Expressive-like installation
questions to allow bringing in common features during the first install.

## JSON Refactor

We also did some refactoring of the zend-json component. Several users have
complained that it includes too much: the JSON-RPC functionality is not
generally useful for those who only want JSON de/serialization, and the
XML2JSON implementation is only needed by a subset of users.

As such, we split it into three:

- [zend-json](https://zendframework.github.io/zend-json/) contains the JSON
  de/serialization logic *only*, starting with its v3 release.
- [zend-json-server](https://zendframework.github.io/zend-json-server) contains
  the JSON-RPC server implementation.
- [zend-xml2json](https://zendframework.github.io/zend-xml2json) contains
  the XML2JSON implementation.

We'd like to thank [Ali Bahman](https://github.com/webit4me) for his assistance
with these changes!

## Forwards compatibility

This week, we discovered half-a-dozen components that declare a dependency on
zend-stdlib, but which had not been updated to allow usage with zend-stdlib's
v3 releases. As such, we quickly updated each to do so, releasing new
maintenance releases when ready. These include:

- zend-code
- zend-expressive-skeleton 
- zend-ldap
- zend-mime
- zend-soap
- zend-xmlrpc

## Documentation

With the MVC initiatives complete, we have begun working on the documentation
in earnest again.

The first thing we did was recognize that while it's nice to be publishing the
documentation, we really need mechanisms for navigating to other components. As such,
we created a topnav button that, when clicked, fetches a list of components with
documentation, and slides the list in from the top of the page.

We've also been either documenting components as we create them (see the MVC
plugins and JSON changes, above), or publishing documentation as we create new
releases on components we update. Since the last update, we've published documentation
for the following components:

- [zend-json](https://zendframework.github.io/zend-json/)
- [zend-json-server](https://zendframework.github.io/zend-json-server/)
- [zend-mime](https://zendframework.github.io/zend-mime/)
- [zend-mvc-plugin-fileprg](https://zendframework.github.io/zend-mvc-plugin-fileprg/)
- [zend-mvc-plugin-flashmessenger](https://zendframework.github.io/zend-mvc-plugin-flashmessenger/)
- [zend-mvc-plugin-identity](https://zendframework.github.io/zend-mvc-plugin-identity/)
- [zend-mvc-plugin-prg](https://zendframework.github.io/zend-mvc-plugin-prg/)
- [zend-servicemanager-di](https://zendframework.github.io/zend-servicemanager-di/)
- [zend-soap](https://zendframework.github.io/zend-soap/)
- [zend-xml2json](https://zendframework.github.io/zend-xml2json/)
- [zend-xmlrpc](https://zendframework.github.io/zend-xmlrpc/)

Many thanks to [Frank Brückner](http://www.froschdesignstudio.de) for the copious
documentation updates he's provided!

There's plenty left to do, however (32 components left at the time of writing).
We've [created a list of components and tasks to perform](https://github.com/zendframework/maintainers/wiki/Documentation-TODO)
if you are interested in helping!

## Issue closures

Last week, [Gary Hockin](https://blog.hock.in) posted to the ZF blog about a
[plan to perform housekeeping of issues posted against the main zendframework repository](/blog/2016-04-11-issue-closures.html).
The basic summary is: issues against the main ZF repository have been tagged as
["To Be Closed"](https://github.com/zendframework/zf2/issues?q=is:open+is:issue+label:%22To+Be+Closed%22),
and will be closed in early May *unless you comment on an issue and tag user @GeeH before 3rd May 2016*.

## Pull request and issue activity

Since the last update, we've
[merged around 160 pull requests](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+is%3Amerged+org%3Azendframework+closed%3A%222016-03-24+..+2016-04-21%22+),
and [resolved around 110 issues](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+closed%3A%222016-03-24+..+2016-04-21%22+).
(links require a GitHub account).

## Notable releases

As noted at the beginning of this post, we've done over 60 component releases
since the last update (approximately four weeks ago). Notable amongst them:

- [Zend Framework 1.12.18](http://framework.zend.com/blog/zend-framework-1-12-18-released.html)
- [zend-json 3.0.0](https://github.com/zendframework/zend-json/releases/tag/3.0.0), which removes
  the JSON-RPC and XML2JSON functionality (as those are now in separate components)
- [zend-inputfilter 2.6.1](https://github.com/zendframework/zend-inputfilter/releases/tag/release-2.6.1),
  which fixes a long-standing issue with localization of `NotEmpty` validation
  messages generated for required inputs.
- [zend-math 2.7.0](https://github.com/zendframework/zend-math/releases/tag/release-2.7.0)
  provides a security hardening patch for `Zend\Math\Rand`, forcing usage of PHP 7's
  `random_bytes()` and `random_int()` when available, and requiring ircmaxell/RandomLib
  for earlier PHP versions.
- [zend-session 2.7.0](https://github.com/zendframework/zend-session/releases/tag/release-2.7.0)
  updates the component to use ext/mongodb + the MongoDB PHP client library instead of ext/mongo,
  and adds session identifier validation by default.
- [zend-db 2.7.1](https://github.com/zendframework/zend-db/releases/tag/release-2.7.1)
  updates the `Pgsql` adapter to accept the `charset` option; fixes `Zend\Db\Sql\Insert` to properly
  manage arrays of column names when generating SQL INSERT statements; fixes an issue with how row
  counts were reported in `Oci8` result sets; and updates the `IbmDb2` adapter to allow `#` characters
  in identifiers.
- [zend-cache 2.7.0](https://github.com/zendframework/zend-cache/releases/tag/release-2.7.0)
  offers a ton of new features, including a new APCu adapter, upgraded support for XCache and Redis,
  and numerous bugfixes.
- [zend-stdlib 2.7.7](https://github.com/zendframework/zend-stdlib/releases/tag/release-2.7.7) and
  [zend-stdlib 3.0.1](https://github.com/zendframework/zend-stdlib/releases/tag/release-3.0.1)
  fix declaration of `Zend\Json\Json::GLOB_BRACE` when on systems based on
  non-gcc versions of glob.

## Until next time

If you want to help:

- Help complete the [documentation migration](https://github.com/zendframework/maintainers/wiki/Documentation-TODO)!
- Test the [new skeleton](https://github.com/zendframework/ZendSkeletonApplication/pull/329)
  and provide feedback.
- Review [issues to be closed](https://github.com/zendframework/zf2/issues?q=is:open+is:issue+label:%22To+Be+Closed%22)
- Search for [help wanted](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22help+wanted%22+)
  or [EasyFix](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22EasyFix%22+)
  issues (most of the latter are documentation).

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases since the last update!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
