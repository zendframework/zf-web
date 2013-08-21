<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-08-21 16:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-08-21 16:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.3! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is the third monthly maintenance release in the 2.2 series. 
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changelog</h2>

<p>
    This release features over 25 changes. Some notable changes include:
</p>

<ul>
    <li>An update that ensures the filter and validator plugin managers are injected 
        into the input filter factory when using the form factory. (<a href="https://github.com/zendframework/zf2/issues/4851">#4851</a>)</li>
    <li>Fixes to code generation to ensure <code>use</code> statements are unique, and that
        non-namespaced class generation is possible. 
        (<a href="https://github.com/zendframework/zf2/issues/4988">#4988</a> and 
        <a href="https://github.com/zendframework/zf2/issues/4990">#4990</a>)</li>
    <li>A fix to input filters and forms to ensure overwriting of inputs and input filters
        happens correctly. (<a href="https://github.com/zendframework/zf2/issues/4996">#4996</a>)</li>
</ul>

<p>
    To see the full changelog, visit:
</p>

<ul>
    <li><a href="/changelog/2.2.3">http://framework.zend.com/changelog/2.2.3</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    I'd like to thank everyone who provided issue reports, typo fixes, maintenance
    improvements, bugfixes, and documentation improvements; your efforts make the
    framework increasingly better!
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday.
</p>

EOC;
$post->setExtended($extended);

return $post;

