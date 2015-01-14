<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.10 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-01-14 14:25', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-01-14 14:25', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.10</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Incremental improvements</h2>

<p>
    Zend Framework 1.12 is in maintenance mode, but that has not slowed 
    activity on the repository; this release features almost 40 bugfixes!
    Among other changes, contributors have also provided improvements for
    our build process, including the removal of tests and documentation
    when adding ZF1 to your project via Composer.
</p>

<p>
    For the full list of changes, visit the changelog:
</p>

<ul>
    <li><a href="/changelog/1.12.10">Changelog</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    As usual, thanks go out to all contributors to this version; Zend Framework 1's
    stability and robustness is due to your efforts. I also want to thank 
    <a href="http://akrabat.com">Rob Allen</a> and
    <a href="https://github.com/froschdesign">Frank Brückner</a> for shepherding
    along contributions and acting as release managers!
</p>

EOC;
$post->setExtended($extended);

return $post;
