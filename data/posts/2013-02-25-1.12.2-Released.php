<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-02-25 15:12', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-02-25 15:12', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 1.12.2! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Changelog</h2>

<p>
    This release includes almost 50 bugfixes and stability fixes. In particular,
    a fix was released for <code>Zend_Service_Twitter</code>, ensuring that it
    will continue to work with the Twitter API going forward. <em>If you are using the
    <code>Zend_Service_Twitter</code> component, please upgrade immediately, or
    your code will not work!</em>
</p>

<p>
    To see the complete set of issues resolved for 1.12.2, please visit the changelog:
</p>

<ul>
    <li>
        <a href="/changelog/1.12.2">http://framework.zend.com/changelog/1.12.2</a>
    </li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>
EOC;
$post->setExtended($extended);

return $post;
