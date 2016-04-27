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
$post->setId('2016-04-27-zf2-repo-rename');
$post->setTitle('Announcement: ZF repository rename 2016-05-03');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-04-27 09:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-04-27 09:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
In contrast to Zend Framework 2, which was a complete rewrite and break with
the architecture of Zend Framework 1, the Zend Framework 3 initiative is more
of an evolutionary change. We are laser-focused on keeping as much backwards
compatibility as possible, and providing reasonable migration steps for our
users. Instead of moving development to a new repository, we have split code
into multiple component repositories, and made the main Zend Framework repository
a "meta" repository, containing dependency information only.

Another way of putting it: changes to the main repository are happening
incrementally, and version 3 will just be a new major version update within
the existing repository.

However, such evolutionary change poses a slight logistical problem: the
repository is currently named "zf2". 
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
As such, we've decided to rename the repository to remove the version identifier.
It will become simply "zendframework".

This naming is already reflected in our Composer package, which is named
"zendframework/zendframework". Additionally, GitHub will provide long-lived
redirects for all links to the repository, including those to issues, comments,
pull requests, etc.; those redirects also work at the git level for each of
HTTPS, SSH, and git protocol access. Because no sha1s change, this means that
Composer installs will not suffer any issues, either.

We've also verified with GitHub that references of the form
`zendframework/zf2#...` within commits, comments, etc. will continue to work,
and redirect to the new location.

With all our concerns satifisied, **we'll be making the change on 3 May 2016**,
and will post to the blog with details on how to update your git remotes to
point to the renamed repository at that time.
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
