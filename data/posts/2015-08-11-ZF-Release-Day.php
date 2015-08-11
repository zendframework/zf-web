<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.15 and 2.4.7 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-08-11 12:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-08-11 12:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.15</strong></li>
    <li>Zend Framework <strong>2.4.7</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Zend Framework 1.12.15</h2>

<p>
    Zend Framework 1.12.15 contains several fixes to ensure backwards compatibility
    with previous releases as well as supported PHP versions:
</p>

<ul>
    <li><a href="https:/github.com/zendframework/zf1/pull/591">#591</a> ensures that
        thet patch introduced to fix <a href="/security/advisory/ZF2015-06">ZF2015-06</a>
        works for PHP 5.2 users.
    </li>

    <li><a href="https://github.com/zendframework/zf1/pull/587">#587</a> fixes a
        regular expression in <code>Zend_Http_Response::extractHeaders()</code> to ensure
        it will work with any valid header name, as well as empty header values.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/597">#597</a> updates
        <code>Zend_Http_Client_Adapter_Curl</code> to ensure it properly distinguishes
        between the <code>timeout</code> and <code>request_timeout</code> options,
        using them to set <code>CURLOPT_CONNECTTIMEOUT</code> and <code>CURLOPT_TIMEOUT</code>,
        respectively.  </li>
</ul>

<p>
    For a full list of changes, see:
</p>

<ul>
    <li><a href="/changelog/1.12.15">http://framework.zend.com/changelog/1.12.15</a></li>
</ul>

<h2>Zend Framework 2.4.7</h2>

<p>
    Zend Framework 2.4.7 has a single change:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zend-inputfilter/pull/15">zend-inputfilter #15</a>
        ensures that input filters can validate not just arrays, but objects implementing
        <code>ArrayAccess</code>, a scenario that broke with fixes introduced for 2.4.5.
    </li>
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
