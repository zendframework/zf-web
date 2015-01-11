<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-03-07 11:50', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-03-07 11:50', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 1.12.5!
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This release fixes PHP 5.2 support for the 1.12 series. If you use PHP 5.2 
    with Zend Framework 1.12, we encourage you to upgrade immediately.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>5.2 support</h2>

<p>
    <a href="/blog/zend-framework-1-12-4-2-1-6-and-2-2-6-released.html">Yesterday's 1.12.4 release</a>
    provided several security fixes around XML eXternal Entity and XML Entity Expansion attack
    vectors. Unfortunately, we had not reviewed our patch to consider PHP 5.2 support, and the
    code contained PHP closures -- which have only been available since PHP 5.3.
</p>

<p>
    The code in the <code>Zend\Xml</code> component was updated to remove the closures, and
    tests for all affected components were run to ensure they worked across PHP versions from
    5.2 - 5.5.
</p>

<h2>Thank You!</h2>

<p>
    A big thank you to those contributors who spotted the errors and provided the 
    initial fixes, particularly Martin Hujer and Frank Brückner.
</p>

EOC;
$post->setExtended($extended);

return $post;
