<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.3.7 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-03-12 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-03-12 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.3.7</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Fixes</h2>

<p>
    Zend Framework 2.3.6 released a change against <kbd>Zend\Mvc\Controller\AbstractRestfulController</kbd>
    that was originally intended for the upcoming 2.4.0 release, and which introduces a slight
    backwards compatibility (BC) break. 2.3.7 reverts the change to keep BC in the 2.3 series.
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
