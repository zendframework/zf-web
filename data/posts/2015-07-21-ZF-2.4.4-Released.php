<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.4.4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-07-21 11:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-07-21 11:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework <strong>2.4.4</strong>. You can download it from the Zend Framework site:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is a <a href="/long-term-support/">Long Term Support</a> release.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Bugfix</h2>

<p>
    This release contains a single critical bugfix. A <a href="https://github.com/zendframework/zend-stdlib/pull/9">developer 
    reported an issue against zend-stdlib</a>
    indicating that our count increment in 
    <code>Zend\Stdlib\PriorityList</code> was incrementing incorrectly,
    and failing to take into account whether or not the item already was 
    present.
</p>

<p>
    As this scenario affects usage of PriorityList with duplicate data, one of 
    its specific use cases, we deemed the issue critical and backported the fix
    to the LTS release.
</p>

EOC;
$post->setExtended($extended);

return $post;
