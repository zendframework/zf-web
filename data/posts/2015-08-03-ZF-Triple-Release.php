<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.14, 2.4.6 and 2.5.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-08-03 14:15', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-08-03 14:15', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.14</strong></li>
    <li>Zend Framework <strong>2.4.6</strong></li>
    <li>Zend Framework <strong>2.5.2</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These releases contain a critical security fix.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Security Fix</h2>

<p>
    Zend Framework versions 1.12.14, and 2.4.6, and 2.5.2 introduced fixes for
    <a href="/security/advisory/ZF2015-06">ZF2015-06</a>, a serious vulnerability 
    in <code>ZendXml</code> when used under PHP-FPM to process multibyte XML
    documents. The advisory provides full details; if you process XML in your
    application and will be deploying or already deploy using PHP-FPM, we recommend
    upgrading immediately.
</ul>

<h2>Other changes</h2>

<p>
    Zend Framework 1.12.14 has two other changes that may impact users:
</p>

<ul>
    <li><code>Zend_Service_DeveloperGarden</code> was removed, as the service closed its API on 30
        June 2015.</li>
    <li><code>Zend_Service_Technorati</code> was removed, as the API has been unavailable for an
        indeterminate amount of time.</li>
</ul>

<p>
    Both Zend Framework 2.4.6 and 2.5.2 also incorporate a change in <code>Zend\InputFilter</code>;
    fixes done in the 2.4/2.5 series removed support for fallback values when performing validation;
    that support has been reinstated with the latest releases.
</p>

<h3>Changelog</h3>

<p>
    For the full changelog on each version:
</p>

<ul>
    <li><a href="/changelog/1.12.14">http://framework.zend.com/changelog/1.12.14</a></li>
    <li><a href="/changelog/2.4.6">http://framework.zend.com/changelog/2.4.6</a></li>
    <li><a href="/changelog/2.5.2">http://framework.zend.com/changelog/2.5.2</a></li>
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
