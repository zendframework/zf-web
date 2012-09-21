<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-09-21 14:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-09-21 14:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.0.2! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This release fixes an issue in the "Router" service that was affecting URL generation,
    among other things.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'

<p>
    Specifically, for the 2.0.1 release, a change was made to allow URL generation
    to continue to work even under a Console paradigm. However, this change apparently
    caused URL generation to break in a number of situations when in an HTTP paradigm.
    A fix was put into place, and integration tests created, to ensure this works
    going forward.
</p>

<h2>Changelog</h2>

<p>
    A handful of other patches were also applied for this release;
    the full list of changes follows:
</p>

<pre>
- 2383: Changed unreserved char definition in Zend\Uri (ZF2-533) and added shell
  escaping to the test runner (https://github.com/zendframework/zf2/pull/2383)
- 2393: Trying to solve issue ZF2-558
  (https://github.com/zendframework/zf2/pull/2393)
- 2398: Segment route: add fix for optional groups within optional groups
  (https://github.com/zendframework/zf2/pull/2398)
- 2400: Use 'Router' in http env and 'HttpRouter' in cli
  (https://github.com/zendframework/zf2/pull/2400)
- 2401: Better precision for userland fmod algorithm
  (https://github.com/zendframework/zf2/pull/2401)
</pre>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release, and particularly to Jurian Sluiman
    for resolving the issue, and Demian Katz for providing extra context and testing! 
</p>

EOC;
$post->setExtended($extended);

return $post;
