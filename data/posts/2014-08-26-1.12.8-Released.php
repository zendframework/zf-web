<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.8 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-08-26 11:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-09-04 16:10', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 1.12.8!
</p>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

<p>
    This is a maintenance release.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Notable Changes</h2>

<ul>
    <li><a href="https://github.com/zendframework/zf1/pull/418">#418</a> Improved regex for
        SQL group, order, from statement. This is an improvement of the Security Advisory
        <a href="http://framework.zend.com/security/advisory/ZF2014-04">ZF2014-04</a>,
        to prevent potential SQL injection. This PR that can be a potential BC break for
        complex SQL code. See below for more information.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/360">#360</a> updates Zend_Locale
        to use <a href="http://cldr.unicode.org">CLDR</a> version 25.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/98">#98</a> allows editing and
        flattening of text form fields within PDF documents.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/375">#375</a> implements
        <kbd>Zend_Pdf::setJavascript()</kbd>, <kbd>Zend_Pdf::addJavascript()</kbd> and
        <kbd>Zend_Pdf::resetJavaScript()</kbd>.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/414">#414</a> adds the
        <kbd>Microsoft_Console</kbd> component from the Windows Azure SDK for PHP into
        the <kbd>Zend_Service_Console</kbd> component, ensuring that WindowsAzure
        command line functionality included in the framework can now work.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/385">#385</a> adds support for
        DateTime fractional seconds under PHP 5.6+.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/382">#382</a> ensures that
        orphaned metadata cache files are removed when <kbd>Zend_Cache::CLEANING_MODE_ALL</kbd>
        is used.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/410">#410</a> ensures that calls
        to reset the status of the libxml entity loader happen as soon as possible,
        to prevent potential threading issues under php-fpm (since the settings
        are per process, not per-request, in that environment).</li>
</ul>

<p>See <a href="http://framework.zend.com/changelog/1.12.8">the changelog</a> for full details.</p>

<h2>Potential BC break</h2>

<p>The PR <a href="https://github.com/zendframework/zf1/pull/418">#418</a> can introduces
potential BC break in presence of complex SQL statements (for instance using SQL sub-functions).</p>
<p>To fix this you can use <strong>Zend_Db_Expr()</strong> in the group(), order() or from()
functions, if your SQL code doesn't work after the upgrade to ZF 1.12.8.</p>


<h2>Thank You!</h2>

<p>
    As always, I'd like to thank the many contributors who made this
    release possible!
</p>

<h4>Updates</h4>

<ul>
    <li>2014-09-04: Added section on potential BC break.</li>
</ul>
EOC;
$post->setExtended($extended);

return $post;
