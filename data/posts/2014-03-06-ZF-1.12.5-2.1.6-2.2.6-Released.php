<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.4, 2.1.6, and 2.2.6 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-03-06 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-03-06 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.4</strong></li>
    <li>Zend Framework <strong>2.1.6</strong></li>
    <li>Zend Framework <strong>2.2.6</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    While these are scheduled maintenance releases, they also contain important security
    fixes; we strongly encourage users to upgrade.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fixes</h2>

<p>
    Two new security advisories have been made:
</p>

<ul>
    <li><a href="/security/advisory/ZF2014-01">ZF2014-01</a>, which mitigates
        XML eXternal Entity and XML Entity Expansion vectors in a variety of
        components. While we had taken measures two years ago to mitigate
        these issues, a researcher discovered several components that remained
        vulnerable.
    </li>

    <li><a href="/security/advisory/ZF2014-02">ZF2014-02</a>, which mitigates
        an issue in our OpenID consumers whereby a malicious Identity Provider
        could be used to spoof the identity of other providers.
    </li>
</ul>

<p>
    For more information, follow the links above; if you use any of the components
    affected, please upgrade as soon as possible.
</p>

<h2>1.12.4</h2>

<p>
    This is the first maintenance release in almost a year on the 1.12 series, and
    contains fixes too numerous to list. Among some of the more important ones,
    however:
</p>

<ul>
    <li>The testing infrastructure has been upgraded to PHPUnit 3.7, making it far simpler for contributors to test changes.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/221">#221</a> removes the TinySrc view helper, as the TinySrc service no longer exists.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/222">#222</a> removes the InfoCard component, as the CardSpace service no longer exists.</li>
    <li><a href="https://github.com/zendframework/zf1/pull/271">#271</a> removes the Nirvanix component, as the Nirvanix service shut down in October 2013.</li>
</ul>

<p>
    Many thanks to all the contributors who helped polish ZF1, including both Frank 
    Brückner and Adam Lundrigan, who provided a ton of patches and feedback, and
    to Rob Allen, our release manager, for shepherding in contributions!
</p>

<h2>2.1.6</h2>

<p>
    <strong>2.1.6</strong> is a security release only, and issued to provide
    fixes for <a href="/security/advisory/ZF2014-01">ZF2014-01</a>.
</p>

<h2>2.2.6</h2>

<p>
    <strong>2.2.6</strong> is both a security and maintenance release. It
    addresses specifically <a href="/security/advisory/ZF2014-01">ZF2014-01</a>.
    Additionally, more than 100 patches were contributed to this release.
</p>

<p>
    For the complete list of changes, <a href="/changelog/2.2.6">read the changelog</a>.
</p>

<h2>ZendXml</h2>

<p>
    We have released a new component, <a href="https://github.com/zendframework/ZendXml">ZendXml</a>,
    to help PHP developers mitigate XXE and XEE vectors in their own code. We highly
    recommend using it if you ware working with XML. It is available via Composer, as well
    as via <a href="https://packages.zendframework.com/">our packages site</a>.
</p>

<h2>Component Releases</h2>

<p>The following components were updated, to the versions specified, to mitigate security issues.</p>

<ul>
    <li>ZendOpenId, v2.0.2</li>
    <li>ZendRest, v2.0.2</li>
    <li>ZendService_Amazon, v2.0.3</li>
    <li>ZendService_Api, v1.0.0</li>
    <li>ZendService_Audioscrobbler, v2.0.2</li>
    <li>ZendService_Nirvanix, v2.0.2</li>
    <li>ZendService_SlideShare, v2.0.2</li>
    <li>ZendService_Technorati, v2.0.2</li>
    <li>ZendService_WindowsAzure, v2.0.2</li>
</ul>

<h2>Thank You!</h2>

<p>
    As always, I'd like to thank the many contributors who made these
    releases possible! The project is gaining in consistency and capabilities
    daily as a result of your efforts.
</p>

<h2>Roadmap</h2>

<p>
    We plan to ship version 2.3.0 sometime next week (week of 10 March 2014).
    We will likely adopt a semi-monthly maintenance release schedule
    thereafter.
</p>

EOC;
$post->setExtended($extended);

return $post;
