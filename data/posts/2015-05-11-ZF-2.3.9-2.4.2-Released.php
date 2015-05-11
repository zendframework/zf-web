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
$post->setTitle('Zend Framework 2.3.9 and 2.4.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-05-11 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-05-11 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.3.9</strong></li>
    <li>Zend Framework <strong>2.4.2</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These are the ninth and second feature releases, respectively, for these minor versions. The releases contain fixes for BC breaks introduced in 2.3.8 and 2.4.1.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Backwards Compatibility Fixes</h2>

<p>
    Zend Framework versions 2.3.8 and 2.4.2 introduced fixes for
    <a href="/security/advisory/ZF2015-04">ZF2015-04</a>, a serious vulnerability 
    in the <code>Zend\Mail</code> and <code>Zend\Http</code> components.</li>
</ul>

<p>
    Unfortunately, in fixing the security vulnerabilities, several use cases 
    were broken, due to lack of tests covering the specific cases. These include:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/7514">Mail messages with multipart bodies were providing an incorrect header continuation.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/7506">Mail messages containing UTF-8 addresses were not being improperly tagged as invalid.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/7507">Cookies with array values were not being serialized and urlencoded, and thus were improperly tagged as invalid.</a></li>
</ul>

<p>
    The new releases fix these issues, ensuring that applications will be both protected from ZF2015-04, as well as continue to work under common use cases. Regression tests were added to ensure the functionality continues to work in the future.
</p>

<h3>Changelog</h3>

<p>
    For the full changelog on each version:
</p>

<ul>
    <li><a href="/changelog/2.4.2">http://framework.zend.com/changelog/2.4.2</a></li>
    <li><a href="/changelog/2.3.9">http://framework.zend.com/changelog/2.3.9</a></li>
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

<h2>Roadmap</h2>

<p>
    We are currently <a href="/blog/announcing-the-zend-framework-3-roadmap.html">shifting gears
    towards Zend Framework 3</a> development.
</p>

<h2>Thank You!</h2>

<p>
    I would like to thank <a href="https://github.com/Maks3w">Maks3w</a>
    for assisting with triage and patching of these issues.
</p>
EOC;
$post->setExtended($extended);

return $post;
