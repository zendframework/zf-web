<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.10 and 2.3.5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-02-18 15:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-02-18 15:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.2.10</strong></li>
    <li>Zend Framework <strong>2.3.5</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These are security releases.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fix</h2>

<p>
    One new security advisory has been made:
</p>

<ul>
    <li><a href="/security/advisory/ZF2015-02">ZF2015-02</a>, which patches
        <kbd>Zend\Db</kbd>'s PostgreSQL platform to use proper escaping of
        quotes for identifiers and values.
    </li>
</ul>

<p>
    For more information, follow the links above; if you use <kbd>Zend\Db</kbd>'s
    PostgreSQL support, please upgrade immediately.
</p>

<h2>2.3.5</h2>

<p>
    Th 2.3.5 release features over 20 patches, covering a number of components.
    For the full list of changes, visit the changelog:
</p>

<ul>
    <li><a href="/changelog/2.3.5">Changelog</a></li>
</ul>

EOC;
$post->setExtended($extended);

return $post;
