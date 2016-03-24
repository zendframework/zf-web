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
$post->setId('2016-03-24-zf3-update');
$post->setTitle('Zend Framework 3 Update for 2016-03-24');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-03-24 15:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-03-24 15:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is an installment in an ongoing series of posts on ZF3 development status.
It's been more than a month since the last update, and we've been quite
busy with:

- ~160 pull requests merged, and ~125 issues closed.
- ~60 component releases.
- Completion of the zend-servicemanager/zend-eventmanager migrations.
- Completion of the component/module installer.
- Progress on the zend-mvc version 3 changes, including separation of routing
  and console tooling to separate packages.
- Publication of documentation for 5 components to GitHub Pages.

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Compatibility Migrations

During the first week of March, we completed the forwards compatibility
migrations of components. As a reminder, we were working on updating components
that depend on any of:

- zend-eventmanager
- zend-servicemanager
- zend-stdlib

to be forwards compatible with the version 3 releases of each. In particular,
the first two have version 2 releases that allow developers to make their
code forwards compatible with the version 3 releases, and we were doing
precisely that with the various Zend Framework components. As of 2 March 2016,
we completed this task &mdash; a major milestone in the ZF3 initiative!

The following component releases were made since the [last blog update](/blog/2016-02-12-zf3-biweekly-update.html)
and mark the current stable versions that are forwards compatible with the v3
releases:

- [zend-authentication 2.5.3](https://github.com/zendframework/zend-authentication/releases/tag/2.5.3)
- [zend-barcode 2.6.0](https://github.com/zendframework/zend-barcode/releases/tag/2.6.0)
- [zend-captcha 2.5.4](https://github.com/zendframework/zend-captcha/releases/tag/2.5.4)
- [zend-db 2.7.0](https://github.com/zendframework/zend-db/releases/tag/2.7.0)
- [zend-eventmanager 3.0.1](https://github.com/zendframework/zend-eventmanager/releases/tag/3.0.1) (which updates the component to use zend-stdlib v3)
- [zend-file 2.6.1](https://github.com/zendframework/zend-file/releases/tag/2.6.1)
- [zend-form 2.7.0](https://github.com/zendframework/zend-form/releases/tag/2.7.0)
- [zend-hydrator 1.1.0](https://github.com/zendframework/zend-hydrator/releases/tag/1.1.0)
- [zend-hydrator 2.1.0](https://github.com/zendframework/zend-hydrator/releases/tag/2.1.0)
- [zend-inputfilter 2.6.0](https://github.com/zendframework/zend-inputfilter/releases/tag/2.6.0)
- [zend-log 2.7.1](https://github.com/zendframework/zend-log/releases/tag/2.7.1)
- [zend-mail 2.6.1](https://github.com/zendframework/zend-mail/releases/tag/2.6.1)
- [zend-modulemanager 2.7.1](https://github.com/zendframework/zend-modulemanager/releases/tag/2.7.1)
- [zend-mvc 2.7.3](https://github.com/zendframework/zend-mvc/releases/tag/2.7.3)
- [zend-navigation 2.6.1](https://github.com/zendframework/zend-navigation/releases/tag/2.6.1)
- [zend-paginator 2.6.0](https://github.com/zendframework/zend-paginator/releases/tag/2.6.0)
- [zend-progressbar 2.5.2](https://github.com/zendframework/zend-progressbar/releases/tag/2.5.2)
- [zend-session 2.6.2](https://github.com/zendframework/zend-session/releases/tag/2.6.2)
- [zend-test 2.6.1](https://github.com/zendframework/zend-test/releases/tag/2.6.1)
- [zend-uri 2.5.2](https://github.com/zendframework/zend-uri/releases/tag/2.5.2)
- [zend-validator 2.6.0](https://github.com/zendframework/zend-validator/releases/tag/2.6.0)
- [zend-view 2.6.5](https://github.com/zendframework/zend-view/releases/tag/2.6.5)

In addition to the migration changes, we made a number of other updates to zend-mvc, including:

- addition of a new `MiddlewareListener`, allowing routing to PSR-7 middleware in the MVC layer.
- modifications to the `EventManagerAwareInterface` initializers; since shared managers are
  injected in `EventManager` instances via the constructor in zend-eventmanager v3, the initializers
  needed changes in order to work with both v2 and v3.
- `AbstractController::getServiceLocator()` now raises an `E_USER_DEPRECATED`
  notice. zend-servicemanager v3 removes the `ServiceLocatorAwareInterface`, and
  zend-mvc will remove the implementations with version 3. Users should start
  updating their controllers to accept dependencies via constructor injection.

The above are messaged in more detail in the [zend-mvc migration guide](http://zendframework.github.io/zend-mvc/migration/).

## Component / Module Installer

One goal for zend-mvc is to reduce the number of dependencies. Much of the
functionality within zend-mvc is not directly related to execution of the MVC,
but rather integrating components. Typically this is done by either providing and
wiring factories for components, or providing and/or wiring event listeners for
components.

We already have functionality that allows doing these tasks via the
zend-modulemanager component, which means we can expose components as application
modules. However, this creates an installation issue: just like modules, you would need to:

1. Install the package containing the module.
2. Enable the module in your application.

To automate the second task, we developed [zend-component-installer](https://zendframework.github.io/zend-component-installer)
back in December.  As part of the current milestones, we completed that
component, by making the following changes:

- It now acts as a [composer plugin](https://getcomposer.org/doc/articles/plugins.md).
  You install it as a development dependency, and it will then inspect any
  package you install to see if it can handle installation tasks.
  This vastly simplifies the previous iteration, which required downloading
  a self-updating PHAR to install the composer scripts within an application.
- It now prompts you to ask which file to inject the detected component or
  module into, allowing you to choose from:
  - `config/application.config.php` (vanilla zend-mvc application)
  - `config/modules.config.php` (Apigility application)
  - `config/development.config.php` (application using zend-development-mode)
  - `config/config.php` (for Expressive users using the expressive-config-manager)
  - or "do not inject".
- It now prompts you to ask if you want to use the selection made on additional
  packages being installed.

We've become quite excited about the possibilities Composer plugins and
installer scripts offer, and plan to leverage them as much as possible!

## zend-mvc v3 progress

Several weeks ago, we created a [detailed plan for the zend-mvc v3 refactor](https://github.com/zendframework/maintainers/wiki/zend-mvc-v3-refactor:-reduce-components).
The work is primarily around reducing the number of dependencies zend-mvc
requires; the above work on the component installer directly enables these changes,
but much more needs to be done.

Since we posted that, we've also started work on the various milestones it details.
In particular, we've done the following:

- Created the [zend-router](https://zendframework.github.io/zend-router/) component,
  to provide all routing capabilities. This reduces the amount of code in zend-mvc
  tremendously, and also makes it easier to re-use routing in other projects
  (e.g., [zend-expressive-zendrouter](https://github.com/zendframework/zend-expressive-zendrouter/pull/6)).
  We also removed console routing from the component, letting it focus on HTTP
  routing only (more on this).
- Created the [zend-mvc-console](https://zendframework.github.io/zend-mvc-console/)
  component, to provide integration between zend-console, zend-mvc, zend-router,
  and zend-view. Essentially, all console-related functionality from zend-mvc,
  zend-router, and zend-view were pushed into this component.
- Created [zend-mvc-plugin-prg](https://zendframework.github.io/zend-mvc-plugin-prg/),
  which makes a standalone component out of the `prg()` controller plugin. This is the
  first of [several component plugins](https://github.com/zendframework/maintainers/wiki/zend-mvc-v3-refactor:-reduce-components#split-out-some-controller-plugins)
  being developed.

As part of this effort, we are [documenting migration steps](https://github.com/zendframework/zend-mvc/blob/develop/doc/book/migration/to-v3-0.md)
needed by end-users, to ensure that developers will be able to update their
applications once version 3 is tagged.

## Documentation

The documentation effort was put on the back-burner during these past few weeks so that
we can focus on the development efforts. Regardless, we managed to publish 5 components
to GitHub Pages:

- [zend-barcode](https://zendframework.github.io/zend-barcode/)
- [zend-mvc](https://zendframework.github.io/zend-mvc/)
- [zend-mvc-console](https://zendframework.github.io/zend-mvc-console/)
- [zend-mvc-plugin-prg](https://zendframework.github.io/zend-mvc-plugin-prg/)
- [zend-router](https://zendframework.github.io/zend-router/)

Additionally, a number of contributors, and notably [Frank Brückner](http://www.froschdesignstudio.de),
have been providing patches to resolve issues created following the automated
documentation migration.

## Diactoros, Stratigility, and Expressive

A fair number of issues and feature patches have been reported on the [Diactoros (PSR-7)](https://github.com/zendframework/zend-diactoros),
[Stratigility (PSR-7 middleware foundation)](https://github.com/zendframework/zend-stratigility), and
[Expressive](https://zendframework.github.io/zend-expressive/) projects, and we had
a short sprint to resolve these.

- The latest version of Diactoros is [1.3.5](https://github.com/zendframework/zend-diactoros/releases/tag/1.3.5),
  and it incorporates around 20 bugfixes and documentation fixes; among others,
  if fixes detection of HTTP/2 requests in the `ServerRequestFactory`.
- The latest version of Stratigiliy, [1.2.0](https://github.com/zendframework/zend-stratigility/releases/tag/1.2.0),
  makes the behavior of its internal `Response` class less error-prone following
  calls to `end()`. Additionally, it:
  - ensures that exception details are not emitted in production mode, and makes
    production mode the default.
  - adds a `FinalHandler::setOriginalResponse()` method, to allow injection
    after instantiation.
  - adds support for catching `Throwable` errors in PHP 7 applications within
    the dispatcher.
  - provides a more meaningful `InvalidMiddlewareException` that is raised by
    `MiddlewarePipe::pipe()` for non-callable middleware.
- [zend-expressive-skeleton](https://github.com/zendframework/zend-expressive-skeleton/releases/tag/1.0.1)
  provides a number of fixes:
  - It updates the Pimple container script to cache factory instances for re-use.
  - It updates the `composer.json` to allow installing zend-servicemanager v3,
    whoops v2, and ProxyManager v2.
  - It fixes an issue in the installer whereby specified constraints were not
    being passed to Composer prior to dependency resolution, resulting in
    stale dependencies.
  - It removes error/exception display from the shipped default error templates,
    to make them secure by default.
- [zend-expressive-zendviewrenderer 1.1.0](https://github.com/zendframework/zend-expressive-zendviewrenderer/releases/tag/1.1.0)
  updates the component to be forward-compatible with the zend-servicemanager
  and zend-eventmanager v3 releases.
- [zend-expressive-zendrouter 1.1.0](https://github.com/zendframework/zend-expressive-zendrouter/releases/tag/1.1.0)
  updates the component to depend on zend-router instead of zend-mvc.

## Pull request and issue activity

Since the last update, we've
[merged around 160 pull requests](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+is%3Amerged+org%3Azendframework+closed%3A%222016-02-12+..+2016-03-24%22),
and [resolved around 125 issues](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+closed%3A%222016-02-12+..+2016-03-24%22+).
(links require a GitHub account).

Unlike previous posts, we are not detailing every component release this time;
the sheer number of them (around 60!) would result in a very long read!

## Until next time

If you want to help:

- Help complete the [documentation migration](https://github.com/issues?utf8=%E2%9C%93&q=is:open+is:issue+org:zendframework+label:documentation+label:EasyFix)!
- You can help with the [MVC milestone](https://github.com/zendframework/maintainers/wiki/zend-mvc-v3-refactor:-reduce-components);
  the linked page provides plenty of detail on how you can assist.
- Search for [help wanted](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22help+wanted%22+)
  or [EasyFix](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22EasyFix%22+)
  issues (most of the latter are documentation).

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases since the last update!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
