<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.5.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-06-03 14:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-06-03 14:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>Today we've released Zend Framework 2.5.0!</p>

<p>
    I'm not going to provide a link to the downloads page, as this release 
    marks a radical change for Zend Framework 2: as of this release, all 
    components are now developed in their own separate repositories, and Zend 
    Framework is itself essentially only a metapackage that aggregates the 
    components via <a href="https://getcomposer.org">Composer</a>!
</p>

<p>
    The impact on users is transparent; a <code>composer update</code> will 
    pick up the new version, which will in turn install the individual 
    components.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h3>Impact</h3>

<p>There are a few potential issues for users upgrading to 2.5.0.</p>

<p>
    First, for those users who were incorporating the repository via git 
    submodules, that approach will no longer work, as the ZF2 repository no 
    longer contains code! If you are doing this, you will either need to start 
    using Composer, or add every component as a git submodule, which will 
    likely also require setting up custom autoloading.
</p>

<p>
    Second, for users who were using the download packages we previously had 
    available via our <a href="/downloads/latest">Downloads page</a>, we are 
    not currently providing a package. You can safely stay at version 2.4 
    (which is our <a href="/long-term-support">long-term support</a> version) 
    for now. We are evaluating re-instating full download packages in the 
    future, but need to gauge interest and how much effort will be needed 
    to automate such packaging.
</p>

<p>
    Third, for users of our translation resources, the translation files are no 
    longer in the <code>resources/languages/</code> directory. This is because 
    they now have their own dedicated package, 
    <a href="https://github.com/zendframework/zend-i18n-resources">zendframework/zend-i18n-resources</a>! 
    As such, you cannot just point your code at that directory in your ZF2 
    install; you will need to do a little bit of work to integrate. The 
    repository has full information on how you can accomplish that.
</p>

<p>
    Finally, we have a significant bump in our minimum supported PHP version 
    starting with 2.5.0: we now require PHP 5.5+. PHP 5.3 is no longer 
    receiving even security updates at this time, and <a 
    href="http://php.net/supported-versions.php">PHP 5.4 support ends on 14 
    September 2015</a> — just over 3 months from now. We encourage our users on 
    versions prior to 5.5 to migrate immediately; the migration from 5.4 to 5.5 
    or 5.6 is trivial, and you'll get substantial performance benefits in 
    addition to new features.
</p>

<h3>Changes</h3>

<p>
    Other than the major architectural change and the PHP version bump, the 
    code in 2.5.0 is exactly the same as you have in 2.4.2.
</p>

<h3>Components</h3>

<p>
    The following is a listing of the various components that make up Zend 
    Framework, linked to their GitHub repository. Each component contains full 
    history of changes for that component since the 2.0.0 release.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zend-authentication">Zend\Authentication (zend-authentication)</a> </li>
    <li><a href="https://github.com/zendframework/zend-barcode">Zend\Barcode (zend-barcode)</a> </li>
    <li><a href="https://github.com/zendframework/zend-cache">Zend\Cache (zend-cache)</a> </li>
    <li><a href="https://github.com/zendframework/zend-captcha">Zend\Captcha (zend-captcha)</a> </li>
    <li><a href="https://github.com/zendframework/zend-code">Zend\Code (zend-code)</a> </li>
    <li><a href="https://github.com/zendframework/zend-config">Zend\Config (zend-config)</a> </li>
    <li><a href="https://github.com/zendframework/zend-console">Zend\Console (zend-console)</a> </li>
    <li><a href="https://github.com/zendframework/zend-crypt">Zend\Crypt (zend-crypt)</a> </li>
    <li><a href="https://github.com/zendframework/zend-db">Zend\Db (zend-db)</a> </li>
    <li><a href="https://github.com/zendframework/zend-debug">Zend\Debug (zend-debug)</a> </li>
    <li><a href="https://github.com/zendframework/zend-di">Zend\Di (zend-di)</a> </li>
    <li><a href="https://github.com/zendframework/zend-dom">Zend\Dom (zend-dom)</a> </li>
    <li><a href="https://github.com/zendframework/zend-escaper">Zend\Escaper (zend-escaper)</a> </li>
    <li><a href="https://github.com/zendframework/zend-eventmanager">Zend\EventManager (zend-eventmanager)</a> </li>
    <li><a href="https://github.com/zendframework/zend-feed">Zend\Feed (zend-feed)</a> </li>
    <li><a href="https://github.com/zendframework/zend-file">Zend\File (zend-file)</a> </li>
    <li><a href="https://github.com/zendframework/zend-filter">Zend\Filter (zend-filter)</a> </li>
    <li><a href="https://github.com/zendframework/zend-form">Zend\Form (zend-form)</a> </li>
    <li><a href="https://github.com/zendframework/zend-http">Zend\Http (zend-http)</a> </li>
    <li><a href="https://github.com/zendframework/zend-i18n">Zend\I18n (zend-i18n)</a> </li>
    <li><a href="https://github.com/zendframework/zend-inputfilter">Zend\InputFilter (zend-inputfilter)</a> </li>
    <li><a href="https://github.com/zendframework/zend-json">Zend\Json (zend-json)</a> </li>
    <li><a href="https://github.com/zendframework/zend-ldap">Zend\Ldap (zend-ldap)</a> </li>
    <li><a href="https://github.com/zendframework/zend-loader">Zend\Loader (zend-loader)</a> </li>
    <li><a href="https://github.com/zendframework/zend-log">Zend\Log (zend-log)</a> </li>
    <li><a href="https://github.com/zendframework/zend-mail">Zend\Mail (zend-mail)</a> </li>
    <li><a href="https://github.com/zendframework/zend-math">Zend\Math (zend-math)</a> </li>
    <li><a href="https://github.com/zendframework/zend-memory">Zend\Memory (zend-memory)</a> </li>
    <li><a href="https://github.com/zendframework/zend-mime">Zend\Mime (zend-mime)</a> </li>
    <li><a href="https://github.com/zendframework/zend-modulemanager">Zend\ModuleManager (zend-modulemanager)</a> </li>
    <li><a href="https://github.com/zendframework/zend-mvc">Zend\Mvc (zend-mvc)</a> </li>
    <li><a href="https://github.com/zendframework/zend-navigation">Zend\Navigation (zend-navigation)</a> </li>
    <li><a href="https://github.com/zendframework/zend-paginator">Zend\Paginator (zend-paginator)</a> </li>
    <li><a href="https://github.com/zendframework/zend-permissions-acl">Zend\Permissions\Acl (zend-permissions-acl)</a> </li>
    <li><a href="https://github.com/zendframework/zend-permissions-rbac">Zend\Permissions\Rbac (zend-permissions-rbac)</a> </li>
    <li><a href="https://github.com/zendframework/zend-progressbar">Zend\ProgressBar (zend-progressbar)</a> </li>
    <li><a href="https://github.com/zendframework/zend-serializer">Zend\Serializer (zend-serializer)</a> </li>
    <li><a href="https://github.com/zendframework/zend-server">Zend\Server (zend-server)</a> </li>
    <li><a href="https://github.com/zendframework/zend-servicemanager">Zend\ServiceManager (zend-servicemanager)</a> </li>
    <li><a href="https://github.com/zendframework/zend-session">Zend\Session (zend-session)</a> </li>
    <li><a href="https://github.com/zendframework/zend-soap">Zend\Soap (zend-soap)</a> </li>
    <li><a href="https://github.com/zendframework/zend-stdlib">Zend\Stdlib (zend-stdlib)</a> </li>
    <li><a href="https://github.com/zendframework/zend-tag">Zend\Tag (zend-tag)</a> </li>
    <li><a href="https://github.com/zendframework/zend-test">Zend\Test (zend-test)</a> </li>
    <li><a href="https://github.com/zendframework/zend-text">Zend\Text (zend-text)</a> </li>
    <li><a href="https://github.com/zendframework/zend-uri">Zend\Uri (zend-uri)</a> </li>
    <li><a href="https://github.com/zendframework/zend-validator">Zend\Validator (zend-validator)</a> </li>
    <li><a href="https://github.com/zendframework/zend-version">Zend\Version (zend-version)</a> </li>
    <li><a href="https://github.com/zendframework/zend-view">Zend\View (zend-view)</a> </li>
    <li><a href="https://github.com/zendframework/zend-xmlrpc">Zend\XmlRpc (zend-xmlrpc)</a> </li>
    <li><a href="https://github.com/zendframework/ZendXml">ZendXml</a></li>
</ul>

<h3>New Components</h3>

<p>We also have two new components in the Zend Framework family:</p>

<ul>
    <li><a 
        href="https://github.com/zendframework/zend-diactoros">Diactoros</a>, a <a 
        href="http://www.php-fig.org/psr/psr-7">PSR-7</a> implementation, providing 
        HTTP message, URI, uploaded file, and stream implementations. </li>
    <li><a 
        href="https://github.com/zendframework/zend-stratigility">Stratigility</a>, a 
        PSR-7-compatible middleware implementation based on <a 
        href="https://github.com/senchalabs/connect">Sencha Connect</a>.</li>
</ul>

<p>
    These are in their early 1.0 versions, though fully stable. We'll be 
    discussing them in more detail in later blog posts. If you want information 
    now, Matthew recently delivered a <a 
    href="http://info.zend.com/Lq80CX0040Ue0LMxR0A0j0r">webinar on PSR-7 and 
    Stratigility</a>.
</p>

<h3>The Future!</h3>

<p>
    This release marks the completion of the first major milestone towards Zend 
    Framework 3! With the various components now versioned on their own 
    timelines and in their own repositories, we can start cherry-picking which 
    components need refactoring or new features. Along the way, we will be 
    creating teams of interested contributors to maintain specific components, 
    which should help accelerate development velocity.
</p>

<p>
    In the near future, we will be working on introducing PSR-7 and middleware 
    support in the ZF2 MVC layer, as well as creating a workflow for wrapping 
    the ZF2 MVC inside of PSR-7-based middleware to allow execution alongside 
    other frameworks and code bases.
</p>

<h3>Thank Yous</h3>

<p>
    Splitting the components into their own repositories and setting up the 
    metapackage approach for the ZF2 repository was not easy. Fortunately, we 
    had some very motivated contributors to assist along the way. In 
    particular:
</p>

<ul>
    <li><a href="http://gianarb.it/">Gianluca Arebezzano</a> and <a 
        href="http://www.corley.it/">Corley</a> (an Italian AWS partner). Gianluca 
        suggested we perform the component splits in parallel via AWS, and 
        convinced Corley, where he works, to help make it happen. Because of this 
        generous support, we were able to do what would have taken weeks in a 
        matter of a single day. </li>
    <li><a href="https://github.com/maks3w">Maks3w</a>, one of our community 
        review team, who did a ton of work around our testing and continuous 
        integration structure to ensure that the rewritten component repositories 
        could be tested seamlessly.</li>
</ul>

<p>
    Version 2.5.0 is only the beginning; you'll be seeing some exciting changes 
    in the Zend Framework project soon!
</p>
EOC;
$post->setExtended($extended);

return $post;
