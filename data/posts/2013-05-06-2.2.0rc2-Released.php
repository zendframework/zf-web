<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.0rc2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-05-06 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-05-06 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.0rc2! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="https://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </li>
</ul>

<p>
    This is a <em>release candidate</em>. It is not the final release, and 
    while stability is generally considered good, there may still be issues
    to resolve between now and the stable release. Use in production with 
    caution.
</p>

<p>
    <strong>DO</strong> please test your applications on this RC, as we would 
    like to ensure that it remains backwards compatible, and that the migration
    path is smooth.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changes in this version</h2>

<p>
    Please see our <a href="/blog/zend-framework-2-2-0rc1-released.html">post for 2.2.0rc1</a>
    for a list of changes. In addition to those changes, the following have been
    made:
</p>

<ul>
    <li>
    <p>
        A late change was made to eliminate and/or make optional several dependencies in
        <code>Zend\Feed</code> and <code>Zend\Validator</code>. While these are generally
        backwards compatible, we need to note that you can no longer directly use
        <code>Zend\I18n\Translator\Translator</code> with validators; instead, you must
        use <code>Zend\Mvc\I18n\Translator</code>. In most cases, this will not present
        an issue, as the translator object is generally injected via the 
        <code>ValidatorPluginManager</code>, which has already been updated to inject
        the correct translator object. 
    </p>

    <p>
        <strong><em>If you were manually injecting your validators with a 
        translator object, please note that you must now use 
        <code>Zend\Mvc\I18n\Translator</code>.</em></strong>
    </p>

    <p>
        The changes have some immediate benefits: you can now use <code>Zend\Feed</code>
        with third-party HTTP clients!
    </p>
</ul>

<h2>Changelog</h2>

<p>
    Almost 200 patches were applied for 2.2.0. We will not release a full
    changelog until we create the stable release. In the meantime, you can
    view a full set of patches applied for 2.2.0 in the 2.2.0 milestone on
    GitHub:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues?milestone=14&state=closed">Zend Framework 2.2.0 milestone</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Please join me in thanking everyone who provided new features and code 
    improvements for this upcoming 2.2.0 release!
</p>

<h2>Roadmap</h2>

<p>
    We plan to release additional RCs every 3-5 days until we feel the 2.2.0
    release is generally stable; we anticipate a stable release sometime next week.
</p>

<p>
    During the RC period, we will be expanding on documentation, and fixing any
    critical issues brought to our attention.
</p>

<p>
    Again, <strong>DO</strong> please test your applications on this RC, as we 
    would like to ensure that it remains backwards compatible, and that the 
    migration path is smooth.
</p>

EOC;
$post->setExtended($extended);

return $post;
