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
$post->setId('2016-01-28-zf3-biweekly-update');
$post->setTitle('Zend Framework 3 Update for 2016-01-28');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-01-28 10:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-01-28 10:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is an installment in an ongoing series of bi-weekly posts on ZF3 development status.

The highlights:

- &gt; 70 pull requests merged
- 2 components updated to zend-servicemanager/zend-eventmanager v3 changes
- 23 releases of components, **including [Expressive 1.0](/blog/2016-01-28-expressive-1.0-stable.html)**, and new
  maintenance releases of Apigility and the ZF2 package.
- Kickstarting of the documentation migration
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Expressive 1.0!

Following [two](/blog/2016-01-19-expressive-rc6-rc7.html)
[final](/blog/2016-01-21-expressive-rc7-rc8.html) release candidates, and after
three months in release candidate status, we've finally tagged
[Expressive 1.0 stable](/blog/2016-01-28-expressive-1.0-stable.html)!
Among other things, we've created a [dedicated documentation site](https://zendframework.github.io/zend-expressive/),
which will update automatically as features are merged to the project.

We feel Expressive is the true cornerstone of the ZF3 initiative, and we look forward
to seeing the middleware-based projects people develop using it!

## ZF2 and Apigility

We noted that the `zendframework/zendframework` package, which since 2.5.0 has been a metapackage
aggregating the various independent components, was using `~2.5.0` for component constraints.
This is problematic, as many components have 2.6 and even 2.7 versions, and some of those contain
security fixes. To fix this, we released version 2.5.3, which modifies the component constraints
to `^2.5`, allowing them to get the latest 2.X series of any given component.

We also released version 1.3.2 of Apigility, to bring in some changes merged many months ago
to fix things like DB Autodiscovery, as well as to pick up the 2.5.3 version of ZF2.

## Documentation

[Gary Hockin](http://blog.hock.in) generously donated some time and wrote
scripts to automate translation of individual component documentation from the
ZF2 reStructured Text sources to markdown, and submitted pull requests across
all components, which we have now merged. These are incomplete; some syntax
cannot be translated correctly, imports within files could not be automated,
etc. 

If you want to assist, we've
[labeled all documentation tasks](https://github.com/issues?utf8=%E2%9C%93&q=is:open+is:issue+org:zendframework+label:documentation+label:EasyFix)
(link requires github login); feel free to jump in on the effort!

We're also working on a plan to host the documentation via [GitHub Pages](https://pages.github.com), to
allow constant, up-to-date documentation, based on the work we did for the Expressive
documentation. Most of the tooling for this is now [created](https://github.com/zendframework/zf-mkdoc-theme),
and we will be able to start pushing it out to components once their documentation
is ready to publish.

## Pull request activity

Since the last update, we've
[merged over 70 pull requests](https://github.com/issues?utf8=%E2%9C%93&q=is%3Apr+org%3Azendframework+is%3Amerged+closed%3A%222016-01-14+..+2016-01-28%22)
(link requires a GitHub account). Activity has been particularly high on
Expressive and documentation issues.

## Component Releases

The following is a list of component releasessince the last update, minus a
number of Expressive releases leading to the stable release.  While not all
releases are related to ZF3 specifically, this list is intended to detail
activity within the organization.

- [zend-view 2.5.3](https://github.com/zendframework/zend-view/releases/tag/release-2.5.3)
  adds support for the `itemprop` HTML attribute in the `headLink()` view
  helper, and updates `PhpRenderer::render()` to no longer lazy-instantiate a
  `FilterChain` if none is already present.
- [zend-servicemanager 2.7.4](https://github.com/zendframework/zend-servicemanager/releases/tag/release-2.7.4)
  fixed an issue with resolving aliases of aliases due to canonicalization
  changes in previous versions.
- [zend-servicemanager 3.0.1](https://github.com/zendframework/zend-servicemanager/releases/tag/release-3.0.1)
  removes the dependency on zend-stdlib by inlining the `ArrayUtils::merge()`
  routine into the `Config` class.
- [zend-expressive-twigrenderer 1.1.0](https://github.com/zendframework/zend-expressive-twigrenderer/releases/tag/1.1.0)
  adds several new features:
  - `url` and `absolute_url` template functions for generating URL paths and absolute URIs.
  - New "globals" configuration for specifying variables to make available in all templates.
- [zend-servicemanager 3.0.2](https://github.com/zendframework/zend-servicemanager/releases/tag/release-3.0.2)
  fixes an issue whereby the creation context was not being passed correctly to
  abstract factories from plugin managers, and provides a performance boost for
  alias resolution.
- [zend-code 3.0.1](https://github.com/zendframework/zend-code/releases/tag/release-3.0.1)
  moves the phpcs dependency to the require-dev section, and ensures that the
  method name is required when adding a method to the class generator.
- [zend-apigility-admin 1.4.1](https://github.com/zfcampus/zf-apigility-admin/releases/tag/1.4.1)
  fixes an issue in the `RpcServiceModel` to ensure that a correct pattern is
  generated when fetching a service by name.
- [zend-apigility-admin-ui 1.2.2](https://github.com/zfcampus/zf-apigility-admin-ui/releases/tag/1.2.2)
  fixes a number of issues discovered, including:
  - DB Autodiscovery was failing due to inability to properly select the DB
    adapter name.
  - Custom authentication adapters are now displayed.
  - The regex for validating custom content-types was fixed to ensure it only
    allows valid MIME type specifications.
  - Fixes validation for REST and RPC service names, raising a warning on
    invalid input.

## ZF3 Refactors

Our refactoring effort has slowed due to our focus on getting Expressive
stabilized, though we're starting to get a number of community contributions to
aid the effort.

If you wish to assist, please read the
[ZF3 ServiceManager refactoring guide](https://github.com/zendframework/maintainers/wiki/ZF3-ServiceManager-component-refactors,-phase-2);
be sure to edit the wiki to indicate when you're working on a component, as well
as to indicate the relevant pull request.

## Until next time

If you want to help:

- As noted above, help complete the [documentation migration](https://github.com/issues?utf8=%E2%9C%93&q=is:open+is:issue+org:zendframework+label:documentation+label:EasyFix)!
- Also, as noted above, you can assist with [refactoring components to support v2 + v3 of zend-servicemanager](https://github.com/zendframework/maintainers/wiki/ZF3-ServiceManager-component-refactors,-phase-2).

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases these past two weeks!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
