<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.4.5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-07-28 11:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-07-28 11:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework <strong>2.4.5</strong>. You can download it from the Zend Framework site:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is a <a href="/long-term-support/">Long Term Support</a> release.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Bugfix</h2>

<p>
    This release contains a single critical bugfix. A <a href="https://github.com/zendframework/zend-inputfilter/pull/7">developer 
    reported an issue against zend-inputfilter</a>, indicating that the combination
    of <em>required</em> and <em>allow_empty</em> was not working as expected.
    When the given input was missing from the submitted data set, the set was
    still considered valid, when it should not be. When the value was present but
    empty, validation worked as expected.
</p>

<p>
    We supplied a patch to ensure behavior is as expected. The patch is also applied
    to zend-inputfilter 2.5.2.
</p>

<p>
    As this scenario affects a common use case for input validation, we deemed 
    the issue critical and backported the fix to the LTS release.
</p>

EOC;
$post->setExtended($extended);

return $post;
