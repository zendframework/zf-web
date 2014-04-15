<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.6 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-04-15 15:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-04-15 15:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 1.12.6!
</p>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

<p>
    This is a maintenance release, and corrects a backwards compatibility break 
    introduced in 1.12.4.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Locale Updates</h2>

<p>
    Zend Framework 1.12.4 included an update to the <a href="//cldr.unicode.org/">CLDR</a>
    version shipped, bumping to version 24. Our previous CLDR version, however, was version 
    2.0 or newer -- a version over 3 years old at this point.
</p>

<p>
    The problem that arose is that <a href="https://github.com/akrabat/zf1/blob/0282f49112688f124373bcf915abb6227d050454/library/Zend/Locale.php#L38-L67">more 
    than two dozen locales have been renamed</a> in the official CLDR sources since then,
    and Zend Framework 1.12.4 shipped exactly what CLDR ships. As a result, users
    of those old locales suddenly found their applications no longer working, due to
    newly invalid locales.
</p>

<p>
    We have created some functionality in Zend Framework 1.12.6 to alias old locales to
    the equivalent new locale string, thus restoring backwards compatibility with versions
    prior to 1.12.4.
</p>

<h2>Tag Updates</h2>

<p>
    Prior to 1.12.4, we used Subversion for maintaining Zend Framework 1, and thus for tagging
    releases. Tags in Subversion, however, are branches, not snapshots, and our 
    build process at the time took advantage of that fact, for better or for worse: we would
    build the documentation, and then replace the documentation sources with the built artifacts;
    we would inject the ZF1 Extras repository; and we would inject the Dojo repository. As a
    result, the tag was not a 1:1 snapshot of the trunk at the time, but rather the result
    of a build process.
</p>

<p>
    This meant that if a user was using <kbd>svn:externals</kbd> and pinned to a tag, they would
    have the equivalent of our distribution packages -- in other words, access to the ZF1 Extras,
    Dojo, and documentation.
</p>

<p>
    With the <a href="http://framework.zend.com/blog/2013-03-27-zf1-git-migration.html">migration 
    to Git a year ago</a>, our build processes needed to change. Git does true tags: a tag is
    a snapshot of the branch at the revision when it was tagged. The result is that tags no
    longer contain the ZF1 Extras or documentation. Several users contacted us indicating
    this broke apps in which they were using <kbd>svn:externals</kbd>.
</p>

<p>
    We have decided we will <em>not</em> be returning to the previous tagging 
    methodology, as we much prefer keeping a separation between tags and the build artifacts.
    For those users who want to retain the same semantic structure of having the ZF1 Extras
    imported via <kbd>svn:externals</kbd> within the ZF1 library, you can still do that,
    by adding an additional line to your <kbd>svn:externals</kbd> property:
</p>

<pre><code>
vendor/ZendFramework https://github.com/zendframework/zf1/tags/{VERSION}
vendor/ZendFramework/extras https://github.com/zendframework/zf1-extras/tags/{VERSION}
</code></pre>

<p>
    (Modify the above to reflect your own project structure, and to inject the appropriate version
    string.)
</p>

<h2>Thank You!</h2>

<p>
    As always, I'd like to thank the many contributors who made this
    release possible! In particular, Rob Allen identified the various locales that
    needed updating, and submitted the locale aliasing solution.
</p>
EOC;
$post->setExtended($extended);

return $post;
