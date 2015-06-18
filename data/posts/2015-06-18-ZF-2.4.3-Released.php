<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.4.3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-06-18 09:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-06-18 09:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework <strong>2.4.3</strong>. You can download it from the Zend Framework site:
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
    This release contains a single critical bugfix. A <a 
    href="https://github.com/zendframework/zend-view/pull/4">developer reported an issue against zend-view</a>
    indicating that when using port forwarding, and particularly when combined with non-standard 
    ports, the <code>ServerUrl</code> view helper was incorrectly generating URIs containing both 
    the local port and the public port.  As an example, a server running on port 10081, but accessed 
    via port 10088 was reporting a URI in the form "localhost:10088:10081". For purposes of public 
    links, the public port 10088 <em>only</em> should be present in the generated URI.
</p>

<p>
    As this scenario is common when using Vagrant, we deemed the issue critical and backported the fix
    to the LTS release.
</p>

EOC;
$post->setExtended($extended);

return $post;
