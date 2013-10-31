<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-10-31 14:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-10-31 14:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.5! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This release includes a security fix for <code>Zend\Http\PhpEnvironment\RemoteAddress</code>
    and <code>Zend\Session\Validator\RemoteAddr</code>; if you use either of these classes,
    we urge you to upgrade to 2.2.5 immediately.
</p>

<p>
    We've not had a release in a couple of months, due to an exciting development: Zend's
    Zend Framework team has announced an initial preview release of 
    <a href="http://apigility.org">Apigility</a>, an API builder and management tool, built
    on top of Zend Framework 2! If you are building APIs or plan to in the future, we
    encourage you to check out this tool and help drive it toward a stable release!
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security</h2>

<p>
    A developer reported a problem with how we were handling situations when
    <code>Zend\Http\PhpEnvironment\RemoteAddress</code> was configured to
    use proxies, had a list of trusted proxies, 
    <code>$_SERVER['REMOTE_ADDR']</code> was not in that list of trusted 
    proxies. Essentially, we were still consulting the <code>X-Forwarded-For</code>
    header in this situation, but should have been used the provided
    <code>$_SERVER['REMOTE_ADDR']</code>, according to the specification.
</p>

<p>
    2.2.5 fixes this situation. If you use that class, or 
    <code>Zend\Session\Validator\RemoteAddr</code>, you should upgrade immediately.
</p>

<p>
    For more details, visit the <a href="/security/advisory/ZF2013-04">ZF2013-04 security advisory</a>.
</p>

<h2>Changelog</h2>

<p>
    To see the full changelog, visit:
</p>

<ul>
    <li><a href="/changelog/2.2.5">http://framework.zend.com/changelog/2.2.5</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Thank you to everybody who has contributed to this release. With more than 
    70 issues resolved, it's one of the busiest and most robust releases on the 
    2.2 branch to-date.
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen (roughly) monthly on (approximately) the third 
    Wednesday. We will be gearing up for the next minor release, 2.3.0, soon.
</p>

EOC;
$post->setExtended($extended);

return $post;
