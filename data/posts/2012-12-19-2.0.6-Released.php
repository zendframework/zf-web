<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.6 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-12-19 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-12-19 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.0.6! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changelog</h2>

<p>
    This release includes almost
    60 patches, ranging from minor docblock improvements to complete bugfixes.  
    The full list is as follows:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/2885">2885: Zend\Db\TableGateway\AbstractTableGateway won't work with Sqlsrv db adapter</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2922">2922: Fix #2902</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2961">2961: Revert PR #2943 for 5.3.3 fix</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2962">2962: Allow Accept-Encoding header to be set explicitly by http request</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3033">3033: Fix error checking on Zend\Http\Client\Adapter\Socket->write().</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3040">3040: remove unused 'use DOMXPath' and property $count and $xpath</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3043">3043: improve conditional : reduce file size </a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3044">3044: Extending Zend\Mvc\Router\Http\Segment causes error</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3047">3047: Fix Zend\Console\Getopt::getUsageMessage()</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3049">3049: Hotfix/issue #3033</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3050">3050: Fix : The annotation @\Zend\Form\Annotation\AllowEmpty declared on  does not accept any values</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3052">3052: Fixed #3051</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3061">3061: changed it back 'consist' => the 'must' should be applied to all parts of the sentence</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3063">3063: hotfix: change sha382 to sha384 in Zend\Crypt\Key\Derivation\SaltedS2k</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3070">3070: Fix default value unavailable exception for in-build php classes</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3074">3074: Hotfix/issue #2451</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3091">3091: console exception strategy displays previous exception message</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3114">3114: Fixed Client to allow also empty passwords in HTTP Authentication.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3125">3125: #2607 - Fixing how headers are accessed</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3126">3126: Fix for GitHub issue 2605</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3127">3127: fix cs: add space after casting</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3130">3130: Obey PSR-2</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3144">3144: Zend\Form\View\Helper\Captcha\AbstractWord input and hidden attributes</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3148">3148: Fixing obsolete method of checking headers, made it use the new method.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3149">3149: Zf2634 - Adding missing method Client::encodeAuthHeader</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3151">3151: Rename variable to what it probably should be</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3155">3155: strip duplicated semicolon</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3156">3156: fix typos in docblocks</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3162">3162: Allow Forms to have an InputFilterSpecification</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3163">3163: Added support of driver_options to Mysqli DB Driver</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3164">3164: Cast $step to float in \Zend\Validator\Step</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3166">3166: [#2678] Sqlsrv driver incorrectly throwing exception when $sqlOrResource...</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3167">3167: Fix #3161 by checking if the server port already exists in the host</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3169">3169: Fixing issue #3036</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3170">3170: Fixing issue #2554</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3171">3171: hotfix : add  '$argName' as 'argument %s' in sprintf ( at 1st parameter )</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3178">3178: Maintain priority flag when cloning a Fieldset</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3184">3184: fix misspelled getCacheStorge()</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3186">3186: Dispatching to a good controller but wrong action triggers a Fatal Error</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3187">3187: Fixing ansiColorMap by removing extra m's showed in the console</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3194">3194: Write clean new line for writeLine method (no background color)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3197">3197: Fix spelling error</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3201">3201: Session storage set save path</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3204">3204: [wip] Zend\Http\Client makes 2 requests to url if setStream(true) is called</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3207">3207: dead code clean up.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3208">3208: Zend\Mime\Part: Added EOL paramter to getEncodedStream()</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3213">3213: [#3173] Incorrect creating instance Zend/Code/Generator/ClassGenerator.php by fromArray</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3214">3214: Fix passing of tags to constructor of docblock generator class</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3217">3217: Cache: Optimized Filesystem::setItem with locking enabled by writing the...</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3220">3220: [2.0] Log Writer support for MongoClient driver class</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3226">3226: Licence is not accessable via web</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3229">3229: fixed bug in DefinitionList::hasMethod()</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3234">3234: Removed old Form TODO since all items are complete</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3236">3236: Issue #3222 - Added suport for multi-level nested ini config variables</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3237">3237: [BUG] Service Manager Not Shared Duplicate new Instance with multiple Abstract Factories</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3238">3238: Added French translation for captcha</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3250">3250: Issue #2912 - Fix for LicenseTag generation</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3252">3252: subject prepend text in options for Log\Writer\Mail</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3254">3254: Better capabilities surrounding console notFoundAction</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday. Next month, 
    January 2013, we plan to issue the next minor release, 2.1.0. 
</p>

EOC;
$post->setExtended($extended);

return $post;

