<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.1.5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-04-17 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-04-17 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.1.5! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is a monthly maintenance release.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Notable changes</h2>

<p>
    2.1.5 is a monthly maintenance release, and the bulk of issues resolved
    were primarily centered around code maintainability - docblocks typos were
    corrected, internal variables renamed more semantically, etc. However, a
    few changes are notable:
</p>

<ul>
    <li>A long-standing issue <a href="https://github.com/zendframework/zf2/pull/4226">regarding 
        setting global permissions in <code>Zend\Permissions\Acl</code></a> was resolved.
    </li>

    <li><a href="https://github.com/zendframework/zf2/pull/4241"><code>Zend\Db\Metadata</code> 
        was updated to no longer use the untrusted <code>quoteValue()</code> 
        API</a>; this means it will no longer create notices!
    </li>

    <li><a href="https://github.com/zendframework/zf2/pull/4148">Filter 
        priority settings are no longer lost when merging filter chains.</a>
    </li>

    <li><a href="https://github.com/zendframework/zf2/pull/4147">The stop-propagation flag is
        now reset when triggering an event.</a> This solves a problem that occurred in the MVC
        when triggering multiple different events in succession.
    </li>

    <li>A fix was applied to prevent <a href="https://github.com/zendframework/zf2/pull/4168"><code>Zend\Console</code>
        from bleeding color to the next line</a>.
    </li>

    <li><code>Zend\Navigation</code> now <a href="https://github.com/zendframework/zf2/pull/4084">supports 
        query parameters properly</a>.
    </li>

    <li><a href="https://github.com/zendframework/zf2/issues/3373">Forms now allow empty
        collections</a></li>

    <li><a href="https://github.com/zendframework/zf2/issues/2898"><code>Zend\Navigation</code>
        now properly injects pages with dependencies</a>.
    </li>
</ul>

<h2>Manual improvements</h2>

<p>
    Last month, we held our first <a href="http://framework.zend.com/blog//2013-03-28-help-us-improve-the-documentation.html">documentation hunt</a>, 
    resulting in a lot of documentation improvements.
</p>

<p>
    Additionally, we began an effort to provide Zend Framework 1 -&gt; Zend 
    Framework 2 migration information. <a href="http://zf2.readthedocs.org/en/latest/migration/overview.html">A 
    preview is available on readthedocs.org</a>.
</p>

<h2>Changelog</h2>

<p>
    Almost 100 patches were applied to the ZF2 codebase, and dozens to the documentation.
    The full changelog for 2.1.5 is available:
</p>

<ul>
    <li><a href="/changelog/2.1.5">http://framework.zend.com/changelog/2.1.5</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    I'd like to thank everyone who provided issue reports, typo fixes, maintenance
    improvements, bugfixes, and documentation improvements; your efforts make the
    framework increasingly better!
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday. Version 2.2.0 
    will release in the first half of May, with the first release candidate dropping
    during the week of 29 April - 3 May 2013.
</p>

EOC;
$post->setExtended($extended);

return $post;
