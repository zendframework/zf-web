<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.9 and 2.3.4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-01-14 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-01-14 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.2.9</strong></li>
    <li>Zend Framework <strong>2.3.4</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These are security releases; we strongly encourage users to upgrade.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fix</h2>

<p>
    One new security advisory has been made:
</p>

<ul>
    <li><a href="/security/advisory/ZF2015-01">ZF2015-01</a>, which patches
        <kbd>Zend\Session</kbd>'s handling of session validators to ensure
        that any metadata they store in the session for validation of subsequent
        requests is properly persisted.
    </li>
</ul>

<p>
    For more information, follow the links above; if you use <kbd>Zend\Session</kbd>
    validators, please upgrade immediately.
</p>

<h2>2.3.4</h2>

<p>
    Th 2.3.4 release features over 200 patches, ranging from fixes in coding 
    standards issues to the security patch listed above. For the full list
    of changes, visit the changelog:
</p>

<ul>
    <li><a href="/changelog/2.3.4">Changelog</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    As usual, thanks go out to all contributors to these versions; Zend Framework's
    continued improvement is based on your efforts. I also want to thank
    <a href="https://github.com/ocramius">Marco Pivetta</a> in particular, for the tireless
    effort he has made in triaging and merging pull requests for the 2.3.4 release; his
    efforts have been invaluable.
</p>

EOC;
$post->setExtended($extended);

return $post;
