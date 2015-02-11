<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.11 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-02-11 11:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-02-11 11:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.11</strong></li>
</ul>

<p>You can download Zend Framework at:</p>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Fixes</h2>

<p>
    The primary rationale for the release was a problem introduced by a bugfix in 1.12.10
    with regards to the <code>ViewRenderer</code> action helper. The fix was incorrectly
    resolving the controller name, which led to problems primarily when using a custom
    dispatcher with your application. 1.12.11 introduces a proper fix that addresses the
    original issue, as well as the problem it introduced.
</p>

<p>
    For the full list of changes, visit the changelog:
</p>

<ul>
    <li><a href="/changelog/1.12.11">Changelog</a></li>
</ul>
EOC;
$post->setExtended($extended);

return $post;
