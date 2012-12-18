<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.11.15 and 1.12.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-12-18 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-12-18 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework versions 1.11.15 and 1.12.1! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These releases contain security fixes; please read on for details.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Announcement</h2>

<p>
    These releases are security releases, and contain fixes to both the 
    <code>Zend_Feed_Rss</code> and <code>Zend_Feed_Atom</code>
    classes. If you are using either, and particularly if you are instantiating 
    them directly (instead of via <code>Zend_Feed::import()</code>)we recommend 
    upgrading immediately. For more
    information, please read the <a href="/security/advisory/ZF2012-05">ZF2012-05 advisory details</a>.
    Thanks goes to Yury Dyachenko at Positive Research Center for alerting us 
    of the issues and working with us on appropriate fixes.
</p>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>

EOC;
$post->setExtended($extended);

return $post;
