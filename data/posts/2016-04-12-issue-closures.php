<?php // @codingStandardsIgnoreFile
use League\CommonMark\CommonMarkConverter;
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('geeh');
$author->setName("Gary Hockin");
$author->setEmail('gary@hock.in');
$author->setUrl('https://blog.hock.in/');

$markdown = new CommonMarkConverter();

$post = new EntryEntity();
$post->setId('2016-04-11-issue-closures');
$post->setTitle('Issues, Tags, and Closures (oh my)');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-04-12 12:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-04-12 12:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
Hello Zend Frameworkians. 

I want to make you aware of some upcoming changes to the issues that are
currently logged in GitHub. We currently have 426 open issues that are logged
against the (now) meta [zf2 repository](https://github.com/zendframework/zf2).
The vast majority of these are now in the wrong place, as we've split our once
monolithic single repository into the many single component repositories. These
issues should be moved from the zf2 repository to the correct component that
the issue relates to. 

In preparation for this, we've been doing a little housekeeping and have
already closed a few minor issues that have been open since before we even used
GitHub for issue tracking. Matthew, Enrico and I also had a long discussion at
Midwest PHP on the best way to handle these issues, and we came up with a plan
of attack that hopefully will allow us to close off a bunch of historical
issues that are no longer relevant, and then move issues that need to be moved
to the correct place.
EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
You may have noticed that over the last few weeks, a number of issues have been
tagged with the new label of "To Be Closed". These are issues that have been
open for a number of years and still haven't been fixed. Its probable that a
number of these issues _should not be closed_ as they are still relevant all
this time later (after all, just because an issue has been open for many years
does not mean that it will never be addressed). However, at some point these
issues need to be triaged so that issues that are old and not relevant can be
closed, and this is the convenient time.

So, in early may, all the issues that are tagged with "To Be Closed" will be
closed. **If you feel an issue needs to remain open for any reason, then please
comment on the issue with a mention of my username (GeeH)**. I will then remove
the "To Be Closed" tag. Next, no sooner than the 3rd May 2016, we will run a script
that will automatically close every tagged issue, commenting on it to advise
the original author to re-open an issue on the correct repository if they feel
that issue is still valid.

Once this has been done, we will be left with around 100-150 issues by my
estimation. Most of these have already been labelled with the correct
repository to move them to (thanks to the sterling work of the community). The
next step will be to run a script that opens a new issue with the same body
text on the correct repository, adding a link to the original issue on the
central zf2 repo. A comment will be added referencing the new issue on the
original, and the original will be closed. 

Hopefully, once this process is complete, we will be left with a few issues
that are either in the right place (after all, some issues will relate to the
meta package on the zf2 repository), or can be moved by hand.

> ## TLDR

> You need to comment on an issue mentioning _GeeH_ BEFORE the 3rd
> May 2016 to ensure it remains open after that date (if it's currently tagged
> ["To Be Closed"](https://github.com/zendframework/zf2/issues?q=is%3Aopen+is%3Aissue+label%3A%22To+Be+Closed%22)).
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
