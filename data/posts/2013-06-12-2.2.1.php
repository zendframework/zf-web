<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-06-12 15:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-06-12 15:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.1! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is the first monthly maintenance release in the 2.2 series. 
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changelog</h2>

<p>
    This release features almost 70 changes, ranging from minor typographical 
    issues to changes to allow easier utilisation of new features introduced in 
    2.2 (e.g., you can now actually select the new 
    <code>TranslatorAwareTreeRouteStack</code> as a router via configuration).  
    The full changelog for 2.2.1 is available:
</p>

<ul>
    <li><a href="/changelog/2.2.1">http://framework.zend.com/changelog/2.2.1</a></li>
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
    is tentatively scheduled for the end of August.
</p>

EOC;
$post->setExtended($extended);

return $post;
