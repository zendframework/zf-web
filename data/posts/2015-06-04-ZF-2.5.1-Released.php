<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.5.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-06-04 10:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-06-04 10:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>Today we've released Zend Framework 2.5.1!</p>

<p>
    To update, use <a href="https://getcomposer.org">Composer</a>:
</p>

<pre class="highlight"><code>
$ composer update zendframework/zendframework
</code></pre>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h3>Changes</h3>

<p>
    The only issue reported against 2.5.0 was a blocker for many: with the shift
    to ZF becoming a metapackage, one component, <a href="https://github.com/zendframework/zend-ldap">zend-ldap</a>,
    had a hard requirement on a specific PHP extension (ext/ldap), meaning that 
    the extension then became a requirement for the entire framework.
</p>

<p>
    ZF 2.5.1 addresses this by making zend/ldap a <a href="https://getcomposer.org/doc/04-schema.md#suggest">
    suggested</a> component. <em>This means that the zend-ldap component is no longer installed
    by default.</em> If you rely on zend-ldap for your application, you will need to
    perform the following after upgrading to 2.5.1:
</p>

<pre class="highlight"><code>
$ composer require zendframework/zend-ldap
</code></pre>

<p>
    The above will add zend-ldap as a requirement to your project, ensuring it
    is present going forward.
</p>

<p>
    We also audited all other components for hard dependencies on extensions; no
    other components did so.
</p>
EOC;
$post->setExtended($extended);

return $post;
