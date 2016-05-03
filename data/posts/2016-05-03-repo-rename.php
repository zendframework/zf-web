<?php // @codingStandardsIgnoreFile
use League\CommonMark\CommonMarkConverter;
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('https://mwop.net/');

$markdown = new CommonMarkConverter();

$post = new EntryEntity();
$post->setId('2016-05-03-zf-repo-rename');
$post->setTitle('Announcement: ZF repository renamed!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-05-03 11:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-05-03 11:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
As [announced last week](/blog/2016-04-27-zf2-repo-rename.html), today, we have
*renamed the "zf2" repository on GitHub to "zendframework"*.

Per the [GitHub documentation on renames](https://help.github.com/articles/renaming-a-repository/),
existing links will be automatically redirected, and will persist as long as we
do not create a new repository with the name "zf2". Redirects occur for:

- issues
- wikis
- stars
- followers
- git operations

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Update your remotes

While git operations pushing and pulling from the original repository URLs will
continue to work, GitHub recommends you update your git remotes to point to the
new location. You can do this with the following in the CLI:

```bash
$ git remote set-url origin https://github.com/zendframework/zendframework.git
```

Note the following:

- Replace `origin` with the name of the remote you use locally; `upstream` is
  also commonly used. Run `git remote -v` to see what you're actually using.
- Valid remote URLs now include:
  - `https://github.com/zendframework/zendframework.git`
  - `git://github.com/zendframework/zendframework.git`
  - `git@github.com:zendframework/zendframework.git`

## Composer

Because [Packagist](https://packagist.org/) points to GitHub, it will
seamlessly redirect. Additionally, all sha1s for all commit remain identical.
As such, there should be no impact to end-users for the change for existing
installs.

We have updated Packagist to point to the new URL as well, so that as
users update, their `composer.lock` files will start pointing to the new
URL.
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
