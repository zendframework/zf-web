<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-09-20 16:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-09-20 16:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.0.1! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This release also includes a minor security fix; please read on for more details.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security</h2>

<p>
    Robert Basic reported that a number of components had not been refactored 
    to use the new <code>Zend\Escaper</code> API internally. While they had been
    escaping output, the methodology did not always follow that of the Escaper
    component, and we decided to update them accordingly. You can <a href="/security/advisory/ZF2012-03">
    read more about the issue on the ZF2012-03 advisory</a>.
</p>

<h2>Changelog</h2>

<p>
    We've applied almost 60 patches in the last two weeks, ranging from simple docblock
    corrections to the aforementioned security patch. The full list of changes follows:
</p>

<pre>
- 2285: Seed RouteMatch params as long as params is set. This permits setting an
  empty array. (https://github.com/zendframework/zf2/pull/2285)
- 2286: prepareNotFoundViewModel listner -  eventResult as ViewModel if set
  (https://github.com/zendframework/zf2/pull/2286)
- 2290: <span>$label</span> only when filled
  (https://github.com/zendframework/zf2/pull/2290)
- 2292: Allow (int)0 in coomments count in entry feed
  (https://github.com/zendframework/zf2/pull/2292)
- 2295: force to check className parameters
  (https://github.com/zendframework/zf2/pull/2295)
- 2296: mini-fix in controller plugin manager
  (https://github.com/zendframework/zf2/pull/2296)
- 2297: fixed phpdoc in Zend\Mvc\ApplicationInterface
  (https://github.com/zendframework/zf2/pull/2297)
- 2298: Update to Date element use statements to make it clearer which DateTime
  (https://github.com/zendframework/zf2/pull/2298)
- 2300: FormRow translate label fix (#ZF2-516)
  (https://github.com/zendframework/zf2/pull/2300)
- 2302: Notifications now to #zftalk.dev
  (https://github.com/zendframework/zf2/pull/2302)
- 2306: Fix several cs (https://github.com/zendframework/zf2/pull/2306)
- 2307: Removed comment about non existent Zend_Tool
  (https://github.com/zendframework/zf2/pull/2307)
- 2308: Fix pluginmanager get method error
  (https://github.com/zendframework/zf2/pull/2308)
- 2309: Add consistency with event name
  (https://github.com/zendframework/zf2/pull/2309)
- 2310: Update library/Zend/Db/Sql/Select.php
  (https://github.com/zendframework/zf2/pull/2310)
- 2311: Version update (https://github.com/zendframework/zf2/pull/2311)
- 2312: Validator Translations (https://github.com/zendframework/zf2/pull/2312)
- 2313: ZF2-336: Zend\Form adds enctype attribute as multipart/form-data
  (https://github.com/zendframework/zf2/pull/2313)
- 2317: Make Fieldset constructor consistent with parent Element class
  (https://github.com/zendframework/zf2/pull/2317)
- 2321: ZF2-534 Zend\Log\Writer\Syslog prevents setting application name
  (https://github.com/zendframework/zf2/pull/2321)
- 2322: Jump to cache-storing instead of returning
  (https://github.com/zendframework/zf2/pull/2322)
- 2323: Conditional statements improved(minor changes).
  (https://github.com/zendframework/zf2/pull/2323)
- 2324: Fix for ZF2-517: Zend\Mail\Header\GenericHeader fails to parse empty
  header (https://github.com/zendframework/zf2/pull/2324)
- 2328: Wrong \__clone method (https://github.com/zendframework/zf2/pull/2328)
- 2331: added validation support for optgroups
  (https://github.com/zendframework/zf2/pull/2331)
- 2332: README-GIT update with optional pre-commit hook
  (https://github.com/zendframework/zf2/pull/2332)
- 2334: Mail\Message::getSubject() should return value the way it was set
  (https://github.com/zendframework/zf2/pull/2334)
- 2335: ZF2-511 Updated refactored names and other fixes
  (https://github.com/zendframework/zf2/pull/2335)
- 2336: ZF-546 Remove duplicate check for time
  (https://github.com/zendframework/zf2/pull/2336)
- 2337: ZF2-539 Input type of image should not have attribute value
  (https://github.com/zendframework/zf2/pull/2337)
- 2338: ZF2-543: removed linked but not implemented cache adapters
  (https://github.com/zendframework/zf2/pull/2338)
- 2341: Updated Zend_Validate.php pt_BR translation to 25.Jul.2011 EN Revision
  (https://github.com/zendframework/zf2/pull/2341)
- 2342: ZF2-549 Zend\Log\Formatter\ErrorHandler does not handle complex events
  (https://github.com/zendframework/zf2/pull/2342)
- 2346: updated Page\Mvc::isActive to check if the controller param was
  tinkered (https://github.com/zendframework/zf2/pull/2346)
- 2349: Zend\Feed Added unittests for more code coverage
  (https://github.com/zendframework/zf2/pull/2349)
- 2350: Bug in Zend\ModuleManager\Listener\LocatorRegistrationListener
  (https://github.com/zendframework/zf2/pull/2350)
- 2351: ModuleManagerInterface is never used
  (https://github.com/zendframework/zf2/pull/2351)
- 2352: Hotfix for AbstractDb and Csrf Validators
  (https://github.com/zendframework/zf2/pull/2352)
- 2354: Update library/Zend/Feed/Writer/AbstractFeed.php
  (https://github.com/zendframework/zf2/pull/2354)
- 2355: Allow setting CsrfValidatorOptions in constructor
  (https://github.com/zendframework/zf2/pull/2355)
- 2356: Update library/Zend/Http/Cookies.php
  (https://github.com/zendframework/zf2/pull/2356)
- 2357: Update library/Zend/Barcode/Object/AbstractObject.php
  (https://github.com/zendframework/zf2/pull/2357)
- 2358: Update library/Zend/ServiceManager/AbstractPluginManager.php
  (https://github.com/zendframework/zf2/pull/2358)
- 2359: Update library/Zend/Server/Method/Parameter.php
  (https://github.com/zendframework/zf2/pull/2359)
- 2361: Zend\Form Added extra unit tests and some code improvements
  (https://github.com/zendframework/zf2/pull/2361)
- 2364: Remove unused use statements
  (https://github.com/zendframework/zf2/pull/2364)
- 2365: Resolve undefined classes and constants
  (https://github.com/zendframework/zf2/pull/2365)
- 2366: fixed typo in Zend\View\HelperPluginManager
  (https://github.com/zendframework/zf2/pull/2366)
- 2370: Error handling in AbstractWriter::write using Zend\Stdlib\ErrorHandler
  (https://github.com/zendframework/zf2/pull/2370)
- 2372: Update library/Zend/ServiceManager/Config.php
  (https://github.com/zendframework/zf2/pull/2372)
- 2375: zend-inputfilter already requires
  (https://github.com/zendframework/zf2/pull/2375)
- 2376: Activate the new GitHub feature: Contributing Guidelines
  (https://github.com/zendframework/zf2/pull/2376)
- 2377: Update library/Zend/Mvc/Controller/AbstractController.php
  (https://github.com/zendframework/zf2/pull/2377)
- 2379: Typo in property name in Zend/Db/Metadata/Object/AbstractTableObject.php
  (https://github.com/zendframework/zf2/pull/2379)
- 2382: PHPDoc params in AbstractTableGateway.php
  (https://github.com/zendframework/zf2/pull/2382)
- 2384: Replace Router with Http router in url view helper
  (https://github.com/zendframework/zf2/pull/2384)
- 2387: Replace PHP internal fmod function because it gives false negatives
  (https://github.com/zendframework/zf2/pull/2387)
- 2388: Proposed fix for ZF2-569 validating float with trailing 0's (10.0,
  10.10) (https://github.com/zendframework/zf2/pull/2388)
- 2391: clone in Filter\FilterChain
  (https://github.com/zendframework/zf2/pull/2391)
- Security fix: a number of classes were not using the Escaper component in
  order to perform URL, HTML, and/or HTML attribute escaping. Please see
  http://framework.zend.com/security/advisory/ZF2012-03 for more details.
</pre>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! Every change helps make 
    the framework better.
</p>

<h2>Going Forward</h2>

<p>
    During an IRC meeting today, we voted to have monthly maintenance releases, 
    falling on the third Wednesday of each month. Minor releases will be released
    every 8 - 12 weeks, incrementally providing new features to the framework. We'll
    keep you posted on the blog as new releases are made!
</p>

EOC;
$post->setExtended($extended);

return $post;

