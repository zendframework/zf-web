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
$post->setTitle('Expressive 1.0.0RC5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-12-22 16:15', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-12-22 16:15', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability of Expressive 1.0.0rc5!

You can install it using [Composer](https://getcomposer.org), using the `create-project` command:

```bash
$ composer create-project zendframework/zend-expressive-skeleton:1.0.0rc5@rc expressive
```

You can update your existing applications using:

```bash
$ composer update
```

Depending on what features you're already using, you may have nothing to do, or
a few changes you may need to make; see below for more information.
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Changes in RC5

The majority of the changes for RC5 were documentation additions, including chapters on:

- how to serve Expressive from a subdirectory of the web root.
- how to create modular Expressive applications.
- how to parse body parameters using new middleware from zend-expressive-helpers.

Two larger changes were made, however:

- As noted above, zend-expressive-helpers now provides middleware for parsing
  the request body into parameters.
- The skeleton and installer now use a new [container-interop](https://github.com/container-interop/container-interop)
  version of Pimple to ensure users can use Pimple v3, versus our previous
  support for only v1.

### Body parameter parsing

[PSR-7](http://www.php-fig.org/psr/psr-7/) provides facilities for retrieving
the parsed body parameters. Most implementations will populate this with the
contents of `$_POST` by default, but for cases where non-form submissions or
non-POST submissions are present, you need to parse and populate the body
parameters manually.

[zend-expressive-helpers](https://github.com/zendframework/zend-expressive-helpers)
now provides middleware for doing this, `Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware`.
This middleware can be optionally added to your application, and supports the addition
of custom strategies to allow parsing arbitrary content types.

This functionality is completely opt-in, and will be available following a
composer update within your application.

[Read more about it in the documentation](http://zend-expressive.readthedocs.org/en/latest/helpers/body-parse/).

### Pimple upgrade

As noted, our previous Pimple support was for v1, which is unsupported at this time.
We decided to upgrade our support to the latest stable version, v3, prior to the
stable release of Expressive.

For those of you who were using Pimple previously, if you wish to update your application,
you will need to do the following:

- First, remove the previous pimple support: `composer remove mouf/pimple-interop pimple/pimple`.
- Second, add the new v3 interop support: `composer require xtreamwayz/pimple-container-interop`.
- Third, replace the contents of `config/container.php` with the contents of the
  [linked skeleton file](https://github.com/zendframework/zend-expressive-skeleton/blob/master/src/ExpressiveInstaller/Resources/config/container-pimple.php).

After taking those steps, you should see everything working just as it did before.

#### zend-view changes

One trivial change was made to the layout template for zend-view users: instead of using `headScript()`,
`inlineScript()` is now used, and emitted at the end of the `<body>` section of the layout.
This is a slight front-end performance enhancement; if you are using the default layout, we recommend
updating accordingly.

## Future

At this point, we do not anticipate any more code changes before the stable release.
We are waiting on at least one pull request for an additional cookbook recipe, and
highly recommend users dive into the documentation and help us polish it for the
final release. Suggestions already include flow and architecture diagrams; if anybody
wants to create these, we'll happily take them!

If you are testing Expressive &mdash; whether for the first time, or updating an
existing application &mdash; please help us prepare it for general availability!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
