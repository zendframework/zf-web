<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.1.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-02-06 16:21', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-02-06 16:21', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.1.1! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    Our contributors have been busy stress-testing the newly released 2.1.0, and
    we have a large number of improvements ready.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changelog</h2>

<p>
    This release includes almost 40 patches. These patches tidy up the 2.1 tree
    significantly, bringing fixes and improvements that greatly enhance the 
    usability of the DB and Session components. The full list is as follows:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/2510">2510: Zend\Session\Container does not allow modification by reference</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/2899">2899: Can't inherit abstract function Zend\Console\Prompt\PromptInterface::show()</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3455">3455: Added DISTINCT on Zend\Db\Sql\Select</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3456">3456: Connection creation added in Pgsql.php createStatement method</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3608">3608: Fix validate data contains arrays as values</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3610">3610: Form: rely on specific setter</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3618">3618: Fix bug when $indent have some string</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3622">3622: Updated Changelog with BC notes for 2.1 and 2.0.7</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3623">3623: Authentication using DbTable Adapter doesn't work for 2.1.0</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3625">3625: Missing instance/object for parameter route upgrading to 2.1.*</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3627">3627: Making relative links in Markdown files</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3629">3629: Zend\Db\Select using alias in joins can results in wrong SQL</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3638">3638: Fixed method that removed part from parts in Mime\Message</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3639">3639: Session Metadata and SessionArrayStorage requestaccesstime fixes.</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3640">3640: [#3625] Do not query abstract factories for registered invokables</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3641">3641: Zend\Db\Sql\Select Fix for #3629</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3645">3645: Exception on destructing the SMTP Transport instance</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3648">3648: Ensure run() always returns Application instance</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3649">3649: Created script to aggregate return status</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3650">3650: InjectControllerDependencies initializer overriding an previously defined EventManager</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3651">3651: Hotfix/3650</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3656">3656: Zend\Validator\Db\AbstractDb.php and mysqli</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3658">3658: Zend\Validator\Db\AbstractDb.php and mysqli (issue: 3656)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3661">3661: ZF HTTP Status Code overwritten</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3662">3662: Remove double injection in Plugin Controller Manager</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3663">3663: Remove useless shared in ServiceManager</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3671">3671: Hotfix/restful head identifier</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3673">3673: Add translations for Zend\Validator\File\UploadFile</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3679">3679: remove '\' character from Traversable </a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3680">3680: Zend\Validator\Db Hotfix (supersedes #3658)</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3681">3681: [#2899] Remove redundant method declaration</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3682">3682: Zend\Db\Sql\Select Quantifier (DISTINCT, ALL, + Expression) support - supersedes #3455</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3684">3684: Remove the conditional class declaration of ArrayObject</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3687">3687: fix invalid docblock</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3689">3689: [#3684] Polyfill support for version-dependent classes</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3690">3690: oracle transaction support</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3692">3692: Hotfix/db parametercontainer mixed use</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases (typically) happen monthly on the third Wednesday. Despite the
    release today, we will still likely target a 2.1.2 release in two weeks.
</p>

EOC;
$post->setExtended($extended);

return $post;
