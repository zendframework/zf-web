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
$post->setId('2016-01-28-expressive-1.0-stable');
$post->setTitle('Expressive 1.0.0 STABLE Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-01-28 10:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-01-28 10:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability
of [Expressive](https://zendframework.github.io/zend-expressive/) 1.0.0 STABLE!

You can install it using [Composer](https://getcomposer.org),
via the `create-project` command:

```bash
$ composer create-project zendframework/zend-expressive-skeleton expressive
```

If you were using a release candidate, you can update your existing applications using:

```bash
$ composer require "zendframework/zend-expressive:^1.0"
```

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## What's new in the stable version?

Nothing!

Well, not "nothing". Since last week, we merged a few documentation fixes, but,
more importantly, finalized our documentation. This included a few changes:

- Some re-organization, to better categorize the documentation hierarchy.
- Switching from [bookdown](http://bookdown.io) to [MkDocs](http://www.mkdocs.org)
  as our build engine of choice. We'd already been using MkDocs to publish on
  [ReadTheDocs](http://rtfd.org), so this wasn't a huge change. The choice was made
  based on stability, maturity, and ecosystem; MkDocs has been around for quite some
  time, and enabled us to accomplish a number of ideas quite quickly.
- Automated publishing to [GitHub Pages](https://pages.github.com), via
  Travis-CI. Any time we push to our master branch, the documentation will be
  updated.

We're quite proud of [the results](https://zendframework.github.io/zend-expressive/),
and hope that the new documentation serves our users well.

## What's to look forward to?

Shipping a stable version means that you can depend on the API going forward.
As such, we're messaging that it's production ready; start building and shipping
your applications on it today!

For the next feature version, we already have a few things scheduled:

- Removal of the deprecated `pre_routing`/`post_routing` configuration support, as
  messaged in the [migration documentation](http://zendframework.github.io/zend-expressive/reference/migration/rc-to-v1/#timeline-for-migration).
- Providing [modular functionality](https://github.com/zendframework/zend-expressive-skeleton/pull/31)
  by default (with opt-out). This will likely include also providing a solution
  similar to the [component installer](https://github.com/zendframework/zend-component-installer)
  to aid with auto-registering installed packages.

## Kudos

[We](https://github.com/zendframework/zend-expressive-router/graphs/contributors)
[wish](https://github.com/zendframework/zend-expressive-aurarouter/graphs/contributors)
[to](https://github.com/zendframework/zend-expressive-fastroute/graphs/contributors)
[thank](https://github.com/zendframework/zend-expressive-zendrouter/graphs/contributors)
[everyone](https://github.com/zendframework/zend-expressive-template/graphs/contributors)
[who](https://github.com/zendframework/zend-expressive-platesrenderer/graphs/contributors)
[contributed](https://github.com/zendframework/zend-expressive-twigrenderer/graphs/contributors)
[to](https://github.com/zendframework/zend-expressive-zendviewrenderer/graphs/contributors)
[the](https://github.com/zendframework/zend-expressive-helpers/graphs/contributors)
[Expressive](https://github.com/zendframework/zend-expressive/graphs/contributors)
[project](https://github.com/zendframework/zend-expressive-skeleton/graphs/contributors)!
(That previous sentence is a link for every one of our 11 Expressive repositories!)

Additionally, we thank everyone who has provided us feedback &mdash; whether in
the form of questions, bug reports, or suggestions &mdash; these past few
months; without the critical feedback, the project would not be where it is
today.

A few folks stand out:

- [Enrico Zimuel](http://www.zimuel.it), who started it all!
- [Geert Eltink](https://xtreamwayz.com), who did the hard work of making the installer work!
- [Hari K T](http://harikt.com), who nudged us to split the repository into discrete, single-purpose projects!
- [Michael Moussa](https://github.com/michaelmoussa), who suggested the idea that middleware specifications
  could be pipelines themselves &mdash; and then implemented the solution!

## Write your next project Expressively!

Write your PSR-7 middleware today! Consult
[the documentation](https://zendframework.github.io/zend-expressive/) to get started!

EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
