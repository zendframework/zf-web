<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.1.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-02-20 15:17', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-02-20 15:17', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.1.2! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    We had a number of minor breakages with the 2.1 release, primarily around changes
    to the session and form components and cross-version compatibility. These should
    all be fixed at this point.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changelog</h2>

<p>
    This release includes almost 60 patches, tidying up a number of issues
    both small and large. Additionally, we finally have documentation for the
    Session component, thanks to the efforts of <a 
    href="https://twitter.com/mwillbanks">Mike Willbanks</a>.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/3085">3085: create controller via Zend\Mvc\Controller\ControllerManager</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3469">3469: ConnectionInterface docblock is wrong or implementation is wrong..</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3506">3506: [WIP] [#3113] Fix spelling in error validation messages</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3636">3636: If route has child routes and in URL has arbitrary query like "?lang=de" it does not work</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3652">3652: Query parameter ?action=somevalue will get 404 error</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3683">3683: Fix to make sure NotEmpty validator is not already set</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3691">3691: Fix for GitHub issue 3469</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3698">3698: Openssl error string</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3699">3699: Certain servers may not set a whitespace after a colon (Set-Cookie: header)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3701">3701: Synced pt_BR\Zend_Validate.php with en\Zend_Validate.php</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3702">3702: added new file: resources\languages\pt_BR\Zend_Captcha.php</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3703">3703: [WIP] Adding parallel testing ANT build configuration and related files</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3705">3705: Recent composer.json update of stdlib package</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3706">3706: clear joins and create without columns</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3707">3707: quoteIdentifier problem in sequence</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3708">3708: Filter\File\RenameUpload: wrap move_uploaded_file to be easly mocked</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3712">3712: Fix for URIs with a query string not matching</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3713">3713: Session Container Mismatch & Version Compare fixes for 5.3.3</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3715">3715: [#3705] Fix autoload.files setting in composer.json</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3716">3716: Added the Zend\Form decepence in composer.json for Zend\Mvc</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3721">3721: Created README.md files for each component</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3722">3722: [Form] [DateTimeSelect] Filter, manager, and view helper fixes</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3725">3725: Use built-in php constants</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3729">3729: Zend\Barcode (Fixes #2862)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3732">3732: Fix for #2531 - Multiplie navigation don't work</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3733">3733: Fix/select where</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3735">3735: [Form] [FormElementManager] don't overwrite form factory if already set</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3742">3742: Object+hydrator element annotation fix</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3743">3743: [#3739 & #3740] Using version-compare in accept header handler params.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3746">3746: Fix bugs for some locales!</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3757">3757: Fixed a bug where mail messages were malformed when using the Sendmail</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3764">3764: Validator File MimeType (IsImage & IsCompressed)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3771">3771: Zend\File\Transfer\Adapter\Http on receive : error "File was not found"  in ZF 2.1</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3778">3778: [#3711] Fix regression in query string matching</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3782">3782: [WIP] Zend\Di\Di::get() with call parameters ignored shared instances.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3783">3783: Provide branch-alias entries for each component composer.json</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3785">3785: Zend\Db\Sql\Literal Fix when % is used in string</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3786">3786: Inject shared event manager in initializer</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3789">3789: Update library/Zend/Mail/Header/AbstractAddressList.php</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3793">3793: Resolved Issue: #3748 - offsetGet and __get should do a direct proxy to $_SESSION</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3794">3794: Implement query and fragment assembling into the HTTP router itself</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3797">3797: remove @category, @package, and @subpackage docblocks</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3798">3798: Remove extra semicolons</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3803">3803: Fix identical validator</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3806">3806: Remove obsolete catch statement</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3807">3807: Resolve undefined classes in phpDoc</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3808">3808: Add missing @return annotations</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3813">3813: Bug fix for GlobIterator extending service</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3817">3817: Add failing tests for Simple console route</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3819">3819: Allow form element filter to convert a string to array</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3828">3828: Cannot validate form when keys of collection in data are non consecutive</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3831">3831: Non-matching argument type for ArrayObject</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3832">3832: Zend\Db\Sql\Predicate\Predicate->literal() does not work with integer 0 as $expressionParameters</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3836">3836: Zend\Db\Sql\Predicate\Predicate Fix for literal() usage</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3837">3837: Fix for legacy Transfer usage of File Validators</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3838">3838: Stdlib\ArrayObject & Zend\Session\Container Compatibility with ArrayObject</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3839">3839: Fixes #2477 - Implemented optional subdomains using regex</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday; expect version 2.1.3
    to drop 20 March 2013. We're also gearing up for version 2.2.0, which we are 
    targetting at the end of April 2013.
</p>

EOC;
$post->setExtended($extended);

return $post;
