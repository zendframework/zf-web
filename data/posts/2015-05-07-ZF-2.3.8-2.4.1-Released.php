<?php
// @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.3.8 and 2.4.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-05-07 16:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-05-07 16:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.3.8</strong></li>
    <li>Zend Framework <strong>2.4.1</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These are the eighth and first feature releases, respectively, for these minor
    versions. The releases contain an important security fix; 2.4.1 also corrects
    several backwards compatibility (BC) breaks introduced in version 2.4.0.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Security Fix</h2>

<p>
    One new security advisory has been made:
</p>

<ul>
    <li><a href="/security/advisory/ZF2015-04">ZF2015-04</a>, which patches CRLF
        Injection Attacks in the <kbd>Zend\Mail</kbd> and <kbd>Zend\Http</kbd>
        components.</li>
</ul>

<p>
    If you use either component, either standalone or as part of other components
    such as <kbd>Zend\Mvc</kbd>, we recommend that you upgrade immediately to either
    2.3.8 or 2.4.1. Read the linked advisory for full details.
</p>

<h2>Bugfixes</h2>

<p>
    Several important fixes are present in 2.4.1 were made that correct backwards compatibility breaks
    introduced in 2.4.0. These include:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/7422">[#7422]</a> 
        fixes a regression in <kbd>Zend\Db\Sql\Expression</kbd> whereby 
        placeholders were being double percent-encoded.</li>
    <li><a href="https://github.com/zendframework/zf2/pull/7426">[#7426]</a> 
        fixes a regression in <kbd>Zend\Form</kbd> whereby input filters 
        attached to collections were no longer being added to the form, leading 
        to incorrect validation and the inability to bind data to nested 
        fieldsets.</li>
    <li><a href="https://github.com/zendframework/zf2/pull/7446">[#7446]</a> 
        fixes a regression in <kbd>Zend\Form</kbd> with regards to removal of 
        multiple elements at once.</li>
    <li><a href="https://github.com/zendframework/zf2/pull/7474">[#7474]</a> 
        fixes a regression in <kbd>Zend\InputFilter</kbd> which broke the 
        relationship between required inputs that were allowed empty, leading 
        to false identification of invalid inputs.</li>
</ul>

<h3>Changelog</h3>

<p>
    The above are only selected changes and features. For the full changelog on each version:
</p>

<ul>
    <li><a href="/changelog/2.4.1">http://framework.zend.com/changelog/2.4.1</a></li>
    <li><a href="/changelog/2.3.8">http://framework.zend.com/changelog/2.3.8</a></li>
</ul>

<h2>Long Term Support</h2>

<p>
    As a reminder, the 2.4 series is our current Long Term Support release, and will
    receive security and critical bug fixes until 31 March 2018.
</p>

<p>
    You can opt-in to the LTS version by pinning your <kbd>zendframework/zendframework</kbd>
    <a href="https://getcomposer.org">Composer</a> requirement to the version <kbd>~2.4.0</kbd>.
</p>

<p>
    <a href="/long-term-support">Visit our Long Term Support information page</a> for more information.
</p>

<h2>Roadmap</h2>

<p>
    We are currently <a href="/blog/announcing-the-zend-framework-3-roadmap.html">shifting gears
    towards Zend Framework 3</a> development.
</p>

<p>
    Tomorrow, 8 May 2015, we will be starting the process of splitting all components into their own
    repositories, to be developed on their own life cycles. Once that process is complete,
    we'll tag each at 2.5.0, and modify the primary Zend Framework repository to act primarily
    as a metapackage that pulls in each component. We also will be adding some new components
    next week (week of 11 May 2015) that will demonstrate future direction of the framework.
</p>

<h2>Thank You!</h2>

<p>
    We had a number of excellent issue reports and patches submitted for this release.
    In particular, I would like to thank <a href="https://github.com/Maks3w">Maks3w</a>
    for reviewing and tagging issues and patches for the release, as well as his enormous
    assistance with the ZF2015-04 patches.
</p>
EOC;
$post->setExtended($extended);

return $post;
