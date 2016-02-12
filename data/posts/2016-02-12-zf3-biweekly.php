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
$post->setId('2016-02-12-zf3-biweekly-update');
$post->setTitle('Zend Framework 3 Update for 2016-02-12');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-02-12 11:40', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-02-12 11:40', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is an installment in an ongoing series of bi-weekly posts on ZF3 development status.

The highlights:

- ~60 pull requests merged, and ~100 issues closed.
- Another v3 release: zend-stdlib!
- 16 components updated to zend-servicemanager/zend-eventmanager/zend-stdlib v3
  changes, and tagged with stable releases.
- 25 component releases.
- Publication of documentation for 13 components to GitHub Pages.
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## New 3.0 versions

We released another component with version 3.0 stability, [zend-stdlib](https://zendframework.github.io/zend-stdlib/).
This release got the major version bump for a number of reasons:

- Per version 2.7.0, the hydrator sub-component was deprecated (it has been
  moved into its own component, [zend-hydrator](https://github.com/zendframework/zend-hydrator)).
  With the new major version, we were able to remove it.
- A number of features existed as polyfills to provide forwards-compatibility
  support from PHP 5.3 or PHP 5.4 to later PHP versions. Since we now support
  only PHP 5.5+, we could remove these.

Unless a component depends specifically on the hydrators, it's essentially
*already* forwards-compatible with the new version 3! As such, we'll be
gradually updating components that depend on zend-stdlib to depend on `^2.7 ||
^3.0`.

## Compatibility Migrations

The past two weeks have been heavily focused on preparing components to be
forwards compatible with the version 3 releases of zend-stdlib,
zend-eventmanager, and zend-servicemanager. We had several breakthroughs that
are enabling these migrations.

First, we can test the different versions via additional Travis-CI jobs. As an
example, consider these PHP 5.5 entries from the zend-cache test matrix:

```yaml
matrix:
  include:
    - php: 5.5
      env:
        - EXECUTE_CS_CHECK=true
        - PECL_INSTALL_APCU='apcu-4.0.8'
    - php: 5.5
      env:
        - SERVICE_MANAGER_VERSION="^2.7.5"
        - EVENT_MANAGER_VERSION="^2.6.2"
        - PECL_INSTALL_APCU='apcu-4.0.8'
```

Note that in the second entry, we specify specific v2 versions of
zend-eventmanager and zend-servicemanager to use.

Later, in our `before_install` section, we do the following:

```yaml
before_install:
  - if [[ $SERVICE_MANAGER_VERSION != '' ]]; then composer require --no-update "zendframework/zend-servicemanager:$SERVICE_MANAGER_VERSION" ; fi
  - if [[ $SERVICE_MANAGER_VERSION == '' ]]; then composer require --no-update "zendframework/zend-servicemanager:^3.0.3" ; fi
  - if [[ $SERVICE_MANAGER_VERSION == '' ]]; then composer remove --dev --no-update zendframework/zend-session ; fi
  - if [[ $EVENT_MANAGER_VERSION != '' ]]; then composer require --no-update "zendframework/zend-eventmanager:$EVENT_MANAGER_VERSION" ; fi
  - if [[ $EVENT_MANAGER_VERSION == '' ]]; then composer require --no-update "zendframework/zend-eventmanager:^3.0" ; fi
```

Essentially, we have two builds. One against the v2 components, and one against
the v3 components; the items above force one or the other for the particular
build. This allows us to verify that the code works against both versions, and
that any later changes require that both versions continue to work.

What about that line to *remove* dependencies, though?

The tricky part of migration has been unravelling dependencies. If a dependency
of a component being migrated *also* depends on one of the updated components,
we have to wait until the dependency is migrated. Or do we?

In many cases, these dependencies are marked as *suggestions*, and as
*development* dependencies only; they are not *hard requirements* of the
component. Realizing this, we discovered the following workflow:

- We can remove dependencies when testing against v3 components **if**:
  - the dependency is not yet migrated
  - the dependency is *optional* (only listed in `require-dev` and/or `suggest`)
- We can update the tests to skip tests that depend on those particular
  components *if classes or interfaces from that component are missing*.

This means that we're testing specifically that the *current* component is
forwards-compatible with the new versions. Later, once those dependencies are
updated, we can re-enable those tests.

Finally, a contributor wrote a trait that we can compose in plugin manager
tests to verify that a plugin manager implementation is both v2 and v3
compatible. By adding these to components, we're able to verify with much more
confidence that the code works on both versions.

With these findings and tools in place, we were able to complete migration of
16 components these past two weeks, tagging each with new stable versions!
These include:

- [zend-math 2.6.0](https://github.com/zendframework/zend-math/releases/tag/release-2.6.0)
  (technically, this one *removes* the dependency on zend-servicemanager, as it
  was an internal detail, and not necessary)
- [zend-serializer 2.6.0](https://github.com/zendframework/zend-serializer/releases/tag/release-2.6.0) /
  [zend-serializer 2.6.1](https://github.com/zendframework/zend-serializer/releases/tag/release-2.6.1)
- [zend-tag 2.6.0](https://github.com/zendframework/zend-tag/releases/tag/release-2.6.0) /
  [zend-tag 2.6.1](https://github.com/zendframework/zend-tag/releases/tag/release-2.6.1)
- [zend-permissions-acl 2.6.0](https://github.com/zendframework/zend-permissions-acl/releases/tag/release-2.6.0)
- [zend-crypt 2.6.0](https://github.com/zendframework/zend-crypt/releases/tag/release-2.6.0)
  (this one replaces zend-servicemanager with container-interop)
- [zend-filter 2.6.0](https://github.com/zendframework/zend-filter/releases/tag/release-2.6.0)
- [zend-http 2.5.4](https://github.com/zendframework/zend-http/releases/tag/release-2.5.4)
- [zend-server 2.6.1](https://github.com/zendframework/zend-server/releases/tag/release-2.6.1)
- [zend-json 2.6.1](https://github.com/zendframework/zend-json/releases/tag/release-2.6.1)
- [zend-config 2.6.0](https://github.com/zendframework/zend-config/releases/tag/release-2.6.0)
- [zend-text 2.6.0](https://github.com/zendframework/zend-text/releases/tag/release-2.6.0)
- [zend-console 2.6.0](https://github.com/zendframework/zend-console/releases/tag/release-2.6.0)
- [zend-log 2.7.0](https://github.com/zendframework/zend-log/releases/tag/release-2.7.0)
- [zend-i18n 2.6.0](https://github.com/zendframework/zend-i18n/releases/tag/release-2.6.0)
- [zend-feed 2.7.0](https://github.com/zendframework/zend-feed/releases/tag/release-2.7.0)
- [zend-cache 2.6.0](https://github.com/zendframework/zend-cache/releases/tag/release-2.6.0) /
  [zend-cache 2.6.1](https://github.com/zendframework/zend-cache/releases/tag/release-2.6.1)

We've made every effort to ensure that these releases continue to work with
existing version 2 functionality; however, occasionally, errors occur. If you
notice such errors, please report them as soon as you can, with as many details
as you can, so we can correct them. Additionally, please be aware that
developers are fellow human beings, and be respectful in your communication.
Nobody is intentionally trying to break your applications, and contributors
desire a smooth migration for you as well.

At this point, we're about half-done with the migrations, and of the remaining
half, around half have patches under review. If you want to assist, please review the
[migrations page](https://github.com/zendframework/maintainers/wiki/ZF3-ServiceManager-component-refactors,-phase-2)
to see which patches are need review, and where you might be able to help.

## Documentation

As noted in [our last update](/blog/2016-01-28-zf3-biweekly-update.html), [Gary
Hockin](http://blog.hock.in/) performed an automated migration of our
documentation from our reStructuredText sources to per-component Markdown a few
weeks ago, and opened issues against each component to guide review of the
documentation before publication. We also mentioned a plan to host
documentation via [GitHub Pages](https://pages.github.com/).

As part of the migration process, we decided to review and ready documentation
for publication for any component getting a new minor release. This has resulted in
new documentation for the following 13 components!

- [zend-servicemanager](https://zendframework.github.io/zend-servicemanager/)
- [zend-stdlib](https://zendframework.github.io/zend-stdlib/)
- [zend-hydrator](https://zendframework.github.io/zend-hydrator/)
- [zend-tag](https://zendframework.github.io/zend-tag/)
- [zend-permissions-acl](https://zendframework.github.io/zend-permissions-acl/)
- [zend-filter](https://zendframework.github.io/zend-filter/)
- [zend-config](https://zendframework.github.io/zend-config/)
- [zend-text](https://zendframework.github.io/zend-text/)
- [zend-console](https://zendframework.github.io/zend-console/)
- [zend-log](https://zendframework.github.io/zend-log/)
- [zend-i18n](https://zendframework.github.io/zend-i18n/)
- [zend-feed](https://zendframework.github.io/zend-feed/)
- [zend-cache](https://zendframework.github.io/zend-cache/)

We're very excited about the new documentation, particularly as it's
mobile-friendly, and has in-site search!

## Pull request and issue activity

Since the last update, we've
[merged around 60 pull requests](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+is%3Amerged+org%3Azendframework+closed%3A%222016-01-28+..+2016-02-12%22+),
[closing over 100 issues](https://github.com/issues?page=2&q=is%3Aissue+org%3Azendframework+closed%3A%222016-01-28+..+2016-02-12%22&utf8=%E2%9C%93).
(links require a GitHub account). Activity has been particularly high on
documentation issues.

## Component Releases

The following is a list of component releases since the last update, minus
those noted in the migration section already.  While not all releases are
related to ZF3 specifically, this list is intended to detail activity within
the organization.

- [zend-expressive-twigrenderer 1.1.1](https://github.com/zendframework/zend-expressive-twigrenderer/releases/tag/1.1.1)
  updates the `TwigExtension` to implement `Twig_Extension_GlobalsInterface`, to
  ensure it is forwards-compatible with Twig v2.
- [zend-servicemanager 2.7.5](https://github.com/zendframework/zend-servicemanager/releases/tag/release-2.7.5)
  fixes the behavior of the `InvokableFactory` for situations when options are
  passed via a plugin manager, and provides tests for validating plugin managers
  are ready for both v2 and v3.
- [zend-servicemanager 3.0.3](https://github.com/zendframework/zend-servicemanager/releases/tag/release-3.0.3)
  provides a number of fixes:
  - cyclical alias detection and reporting.
  - skips alias resolution if no aliases are present.
  - adds tests to verify plugin managers are v3-ready.
  - publishes documentation to GitHub Pages.
- [ZendXml 1.0.2](https://github.com/zendframework/ZendXml/releases/tag/release-1.0.2)
  updates the PHP dependency to `^5.3.3 || ^7.0`, allowing it to work with any
  ZF component, in any supported PHP version. It also expands the test matrix to
  include PHP 7.
- [zend-i18n 2.6.0](https://github.com/zendframework/zend-i18n/releases/tag/release-2.6.0),
  while previously noted, also contained the following changes:
  - adds support for `NumberFormatter` text attributes when using the `NumberFormat` view helper.
  - provides updated postal code verifications for Mauritius and Serbia.
  - allows multiple invocations of the `DateTime` validator with different sets of input.
  - provides null checks on provided message strings.

## Until next time

If you want to help:

- Help complete the [documentation migration](https://github.com/issues?utf8=%E2%9C%93&q=is:open+is:issue+org:zendframework+label:documentation+label:EasyFix)!
- Also, as noted above, you can assist with [refactoring components to support v2 + v3 of zend-servicemanager](https://github.com/zendframework/maintainers/wiki/ZF3-ServiceManager-component-refactors,-phase-2).
- Search for [help wanted](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22help+wanted%22+)
  or [EasyFix](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22EasyFix%22+)
  issues (most of the latter are documentation).

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases these past two weeks!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
