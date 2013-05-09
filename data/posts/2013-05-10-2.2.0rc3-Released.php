<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.0rc3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-05-10 10:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-05-10 10:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.0rc3! Packages and installation instructions are
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
    and our <a href="/blog/zend-framework-2-2-0rc1-released.html">post for 2.2.0rc2</a>
    for a list of changes. In addition to those changes, the following have been
    made:
</p>

<ul>
    <li>
    <p>
        A late addition of <code>Zend\Stdlib\Hydrator\Aggregate</code> was made. This
        functionality allows the ability to map hydrators to objects via events, and
        generally streamlines the process of having a single hydrator for a hierarchy
        of objects. Read more in the <a href="http://zf2.readthedocs.org/en/latest/modules/zend.stdlib.hydrator.aggregate.html">AggregateHydrator documentation</a>.
    </p>
    </li>

    <li>
    <p>
        Improvements were made to <code>Zend\Di</code> to make it work better with the
        various "Aware" interfaces that have proliferated throughout the framework,
        eliminating issues where the component would attempt to instantiate an interface.
    </p>
    </li>
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
    This is the third release candidate. At this time, we anticipate a stable 
    release sometime mid-week next week.
</p>

<p>
    Over the next few days, we will be expanding on documentation, and fixing 
    any critical issues brought to our attention; we do not anticipate many,
    if any, critical issues at this time, however.
</p>

<p>
    Again, <strong>DO</strong> please test your applications on this RC, as we 
    would like to ensure that it remains backwards compatible, and that the 
    migration path is smooth.
</p>

EOC;
$post->setExtended($extended);

return $post;
