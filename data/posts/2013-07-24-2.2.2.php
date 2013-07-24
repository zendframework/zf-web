<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-07-24 12:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-07-24 12:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.2! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is the second monthly maintenance release in the 2.2 series. 
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changelog</h2>

<p>
    This release features over 60 changes. Some notable changes include:
</p>

<ul>
    <li>The cURL adapter for <code>Zend\Http</code> will no longer double-decode
    gzip-encoded bodies. (<a href="https://github.com/zendframework/zf2/issues/4555">#4555</a>)</li>
    <li>A <code>headLink()</code> method was added to the HeadLink view helper
    so that its usage matches the documentation. (<a href="https://github.com/zendframework/zf2/issues/4105">#4105</a>)</li>
    <li>The validator plugin manager was updated to include the new 
    "PhoneNumber" validator. (<a href="https://github.com/zendframework/zf2/issues/4644">#4644</a>)</li>
    <li>Abstract methods in the <code>AbstractRestfulController</code> were made
    non-abstract, and modified to set a 405 ("Method Not Allowed") status. (<a href="https://github.com/zendframework/zf2/issues/4808">#4808</a>)</li>
</ul>

<p>
    To see the full changelog, visit:
</p>

<ul>
    <li><a href="/changelog/2.2.2">http://framework.zend.com/changelog/2.2.2</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    I'd like to thank everyone who provided issue reports, typo fixes, maintenance
    improvements, bugfixes, and documentation improvements; your efforts make the
    framework increasingly better!
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday. Version 2.3.0 
    is tentatively scheduled for September.
</p>

EOC;
$post->setExtended($extended);

return $post;
