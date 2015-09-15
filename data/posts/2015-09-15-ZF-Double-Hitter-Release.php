<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.16 and 2.4.8 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-09-15 14:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-09-15 14:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.16</strong></li>
    <li>Zend Framework <strong>2.4.8</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These releases contain a security fixes.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Security Fixes</h2>

<h3>ZF2015-07</h3>

<p>
    <a href="/security/advisory/ZF2015-07">ZF2015-07</a> addresses attack vectors that
    arise due to incorrect permissions masks when creating directories and files
    within library code.
</p>

<p>
    This particular issue touches each of the following projects, and was fixed in the
    versions specified:
</p>

<ul class="ul">
    <li>Zend Framework 1, version 1.12.16</li>
    <li>Zend Framework 2, versions 2.4.8</li>
    <li>zf-apigility-doctrine, version 1.0.3</li>
    <li>zend-cache, versions 2.4.8 and 2.5.3</li>
</ul>

<h3>ZF2015-08</h3>

<p>
    <a href="/security/advisory/ZF2015-08">ZF2015-08</a> addresses potential null byte
    injection of SQL statements issued using Zend Framework's pdo_dblib
    (FreeTDS) and pdo_sqlite adapters. The issue is patched in Zend Framework 1.12.16.
</p>

<h2>Changelog</h2>

<p>
    For the full changelog on each version:
</p>

<ul>
    <li><a href="/changelog/1.12.16">http://framework.zend.com/changelog/1.12.16</a></li>
    <li><a href="/changelog/2.4.8">http://framework.zend.com/changelog/2.4.8</a></li>
</ul>

<p>
    In particular, the 2.4.8 release has numerous fixes in the InputFilter, Validator, and Form
    components introduced to increase stability and reinstate behavior prior to version 2.4.0.
    At this time, forms and input filters created using code from pre-2.4 should work identically.
</p>

<p>
    We have, however, <em>deprecated</em> the <code>allow_empty</code> and <code>continue_if_empty</code>
    flags, and provided notes in the changelog that describe alternatives to their usage. We have found 
    that these flags, particularly in combination with the <code>required</code> flag and validators,
    can lead to unexpected or unintended behavior, often contradictory. Deprecating them will
    allow us to introduce cleaner solutions in future releases.
</p>

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
