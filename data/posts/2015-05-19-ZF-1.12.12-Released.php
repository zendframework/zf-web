<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.12 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-05-19 14:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-05-19 14:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.12</strong></li>
</ul>

<p>You can download Zend Framework at:</p>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fix</h2>

<p>
    This release includes a security fix for <a href="/security/advisory/ZF2015-04">ZF2015-04</a>,
    which details message splitting vulnerabilities in <code>Zend_Mail</code> and <code>Zend_Http</code>.
    The patch for Zend Framework 1.12 ensures that headers cannot contain incorrect sequences that could
    lead to the vulnerability. Additionally, several new classes were introduced:
</p>

<ul>
    <li><code>Zend_Mail_Header_HeaderName</code></li>
    <li><code>Zend_Mail_Header_HeaderValue</code></li>
    <li><code>Zend_Http_Header_HeaderValue</code></li>
</ul>

<p>
    Each class contains the following methods:
</p>

<ul>
    <li><code>filter($value)</code>, which will perform a lossy filter on the value, ensuring
        any invalid characters are stripped.</li>
    <li><code>isValid($value)</code>, which will tell you whether or not the value is valid
        per the specification it targets.</li>
    <li><code>assertValid($value)</code>, which will raise an exception for invalid values.</li>
</ul>

<p>
    Internally, classes that work with headers utilize <code>assertValid()</code> to ensure
    your messages are safe.
</p>

<p>
    We strongly recommend that consumers of the <code>Zend_Http</code> and <code>Zend_Mail</code>
    components upgrade immediately. If you cannot, you can download the patch separately and
    apply it to your ZF install:
</p>

<ul>
    <li><a href="https://packages.zendframework.com/releases/ZendFramework-1.12.12/0001-ZF2015-04-Fix-CRLF-injections-in-HTTP-and-Mail.patch">ZF2015-04 patch for ZF1</a></li>
</ul>

<h2>Fixes</h2>

<p>
    Besides the security patch, two primary features were added with this release:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf1/pull/537">#537</a> fixed a problem
        introduced with 1.12.11 with regards to view template resolution when the 
        matched module, controller, or action contains special characters.</li>
    <li>Zend Framework 1 was audited to pass unit tests on PHP 7; this means that
        you should be able to run ZF1 on PHP 7 nightly builds!</li>
</ul>

<p>
    For the full list of changes, visit the changelog:
</p>

<ul>
    <li><a href="/changelog/1.12.12">Changelog</a></li>
</ul>
EOC;
$post->setExtended($extended);

return $post;
