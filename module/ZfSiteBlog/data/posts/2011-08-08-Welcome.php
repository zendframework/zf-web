<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Welcome!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-08-09 13:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-08-09 13:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    Welcome to the new Zend Framework development blog!
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
    This blog is a new effort by the Zend Framework team to keep the ZF 
    community abreast of development as we work towards version 2.0 and beyond.
</p>

<p>
    In the coming days and weeks, we will be posting updates, including:
</p>

<ul class="ul">
    <li>
        Weekly status of Zend's ZF team as they work on ZF2.
    </li>

    <li>
        Links to new MVC (and other) prototypes for developers to examine, test,
        and ultimately provide feedback on.
    </li>

    <li>
        Announcements of new developer snapshot releases.
    </li>

    <li>
        Documentation and examples for newly written or refactored components.
    </li>

    <li>
        Links and summaries of blog posts covering ZF2 development and usage.
    </li>
</ul>

<p>
    Most communication is currently taking place on the <a href="http://zend-framework-community.634137.n4.nabble.com/ZF-Contributor-f680267.html">zf-contributors 
    mailing list</a>, and we encourage you to subscribe to that list in order
    to follow discussions; a link for subscribing is provided in the sidebar.
</p>

<p>
    You can follow the blog using our <a href="/zf2/blog/feed">RSS2 feed</a>.
</p>
EOC;
$post->setExtended($extended);

return $post;
