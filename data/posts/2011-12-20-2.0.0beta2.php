<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.0beta2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-12-20 14:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-12-20 14:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta2. Packages and installation instructions are
available at:
</p>

<ul class="ul">
    <li>
    <a href="http://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </li>
</ul>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
This is the second in a series of planned beta releases. The beta release
cycle is following the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen <em>approximately</em> every six weeks. The desire is for
developers to adopt and work with new components as they are shipped, and
provide feedback so we can polish the distribution.
</p>

<p>
Once all code in the proposed <a href="http://framework.zend.com/wiki/pages/viewpage.action?pageId=43745438">standard distribution</a> 
has reached maturity and reasonable stability, we will freeze the API and
prepare for Release Candidate status. 
</p>


<p>
Featured components and functionality of 2.0.0beta2 include:
</p>

<ul class="ul">
    <li><b>Refactored Mail component</b>
    <ul class="ul">
        <li>The Storage API has been left intact, though several classes and interfaces were shuffled around.</li>
        <li>Zend\Mail\Mail was renamed to Zend\Mail\Message; it now 
            encapsulates a mail message and all headers.  MIME messages are 
            created by attaching a Zend\Mime\Message object as the mail message 
            body</li>
        <li>Added Zend\Mail\Address and Zend\Mail\AddressList, used to 
            represent single addresses and address collections, particularly 
            within mail headers
            </li>
        <li>Added Zend\Mail\Header\* and Zend\Mail\Headers, representations of 
            mail headers.</li>
        <li>A new Zend\Mail\Transport interface defines simply 
            <code>send(Message $message)</code>.  The SMTP, File, and Sendmail 
            transports were rewritten to consume Message objects, and to introduce 
            Options classes.</li>
    </ul>
    </li>
    <li><b>Refactored Cache component</b>
    <ul class="ul">
        <li>Completely rewritten component.</li>
        <li>New API features storage adapters and adapter plugins for 
            implementing cache storage and features such as serialization, 
            clearing, and optimizing.</li>
        <li>Current adapters include filesystem, APC, memcached, and memory.</li>
        <li>All adapters can describe capabilities.</li>
        <li>Plugins are implemented as event listeners.</li>
        <li>A new "Pattern" API was created to simplify things like method, 
            class, object, and output caching.</li>
    </ul>
    </li>
    <li><b>MVC updates</b>
    <ul class="ul">
        <li>Zend\Module\Manager was stripped of most functionality; it now 
            simply iterates requested modules and triggers events.</li>
        <li>Former manager functionality such as class loading and 
            instantiation, <code>init()</code> triggering, configuration gathering, 
            and autoloader seeding were moved to event listeners.</li>
        <li>Post-module loading configuration globbing support was added, 
            simplifying the story of overriding module configuration.</li>
        <li>The recommended filesystem no longer uses plurals for directory 
            names.</li>
        <li>The recommendations now include a <code>chdir(__DIR__ . 
            '/../')</code> from the "public/index.php" file, and specifying 
            configuration paths to be relative to application directory.</li>
    </ul>
    </li>
</ul>

<p>
    In addition, over 100 bug and feature requests were handled since 2.0.0beta1.
</p>


<p>
    The <a href="http://github.com/zendframework/ZendSkeletonApplication">skeleton
    application</a> and a <a
    href="http://github.com/zendframework/ZendSkeletonModule">skeleton
    module</a> built for 2.0.0beta1 have been updated for 2.0.0beta2, and are a 
    great place to look to help get you started. You may also want to check out the <a
    href="http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.quick-start.html">quick start
    guide to the MVC</a>.
powerful. 
</p>

<p>
As a reminder, all ZF2 components are also available individually as <a href="http://pear2.php.net">Pyrus</a> 
packages; packages.zendframework.com is our official channel.
</p>

<p>
I'd like to thank each and every person who has contributed ideas, feedback, 
pull requests (no pull request is too small!), testing, and more -- we have a 
solid chunk of quality new functionality to test now thanks to your efforts!
</p>
EOC;
$post->setExtended($extended);

return $post;

