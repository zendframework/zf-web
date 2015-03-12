<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.3.6 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-03-12 10:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-03-12 10:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.3.6</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is a security release.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fix</h2>

<p>
    One new security advisory has been made:
</p>

<ul>
    <li><a href="/security/advisory/ZF2015-03">ZF2015-03</a>, which patches
        <kbd>Zend\Validator\Csrf</kbd> to ensure proper identification of
        null and malformed token identifiers.
    </li>
</ul>

<p>
    For more information, follow the links above; if you use <kbd>Zend\Validator\Csrf</kbd>,
    either standalone or with <kbd>Zend\InputFilter</kbd> or <kbd>Zend\Form\Element\Csrf</kbd>,
    with the 2.3 series of Zend Framework, please upgrade immediately.
</p>

<h2>Support Zend Framework!</h2>

<p>
    Sitepoint is currently running a <a href="http://www.sitepoint.com/best-php-framework-2015-survey/">
    "Best PHP Framework 2015 Survey"</a>; we kindly ask that you help represent 
    the Zend Framework community in the survey, and show your support!
</p>

<h2>Milestones</h2>

<p>
    We are currently actively finishing the final features for Zend Framework 2.4, and plan a release
    candidate for next week (week of 17 March 2015). Once 2.4 stable is released, we turn towards
    Zend Framework 3 tasks, as outlined in <a href="/blog/announcing-the-zend-framework-3-roadmap.html">
    the Zend Framework 3 roadmap</a>.
</p>

EOC;
$post->setExtended($extended);

return $post;
