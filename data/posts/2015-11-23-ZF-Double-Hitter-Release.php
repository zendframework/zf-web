<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.17 and 2.4.9 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-11-23 14:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-11-23 14:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.17</strong></li>
    <li>Zend Framework <strong>2.4.9</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These releases contain security fixes.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Security Fixes</h2>

<h3>ZF2015-09</h3>

<p>
    <a href="/security/advisory/ZF2015-09">ZF2015-09</a> provides a security hardening
    patch for users of our word-based CAPTCHA adapters, ensuring better randomization
    of the letters generated.
</p>

<p>
    This particular issue touches each of the following projects, and was fixed in the
    versions specified:
</p>

<ul class="ul">
    <li>Zend Framework 1, version 1.12.17</li>
    <li>Zend Framework 2, versions 2.4.9</li>
    <li>zend-captcha, versions 2.4.9 and 2.5.2</li>
</ul>

<h3>ZF2015-10</h3>

<p>
    <a href="/security/advisory/ZF2015-10">ZF2015-10</a> addresses potential information
    disclosure for users of Zend Framework's <code>Zend\Crypt\PublicKey\Rsa</code> support,
    due to an insecure OpenSSL padding default. The issue is patched in Zend
    Framework 2.4.9 and zend-crypt 2.4.9/2.5.2.
</p>

<h2>Changelog</h2>

<p>
    For the full changelog on each version:
</p>

<ul>
    <li><a href="/changelog/1.12.17">http://framework.zend.com/changelog/1.12.17</a></li>
    <li><a href="/changelog/2.4.9">http://framework.zend.com/changelog/2.4.9</a></li>
</ul>

<h2>Long Term Support</h2>

<p>
    As a reminder, the 2.4 series is our current Long Term Support release, and will
    receive security and critical bug fixes until 31 March 2018.
</p>

<p>
    You can opt-in to the LTS version by pinning your <code>zendframework/zendframework</code>
    <a href="https://getcomposer.org">Composer</a> requirement to the version <code>~2.4.0</code>.
</p>

<p>
    <a href="/long-term-support">Visit our Long Term Support information page</a> for more information.
</p>
EOC;
$post->setExtended($extended);

return $post;
