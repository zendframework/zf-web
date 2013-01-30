<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.7 and 2.1.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-01-30 14:15', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-01-30 14:15', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of <em>both</em> Zend Framework <strong>2.0.7</strong> and <strong>2.1.0</strong>! Packages and installation instructions are
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

<h2>2.0.7</h2>

<p>
    This is the last scheduled release in the 2.0 series, and contains over 150
    bugfixes. <a href="<?php echo $this->url('changelog/release', 
    array('version' => '2.0.7')) ?>">Read the changelog for the full set of 
    improvements</a>.
</p>

<p>
    Many thanks to all the contributors who helped polish this initial feature
    branch and improve it!
</p>

<h2>2.1.0</h2>

<p>
    <strong>2.1.0</strong> is the first new feature release for ZF2. Most
    features are incremental improvements on existing components; we also
    have one brand new component as well. Thinks you'll see include:
</p>

<ul>
    <li>New <strong>Zend\Permissions\Rbac</strong> component, providing
        Role-Based Authorization Controls. These complement our existing
        <code>Zend\Permissions\Acl</code> component, providing another
        mechanism for providing authorization for your applications.
        We have Kyle Spraggs to thank for this addition.
    </li>

    <li>New <strong>Zend\Test</strong> component, providing the ability
        to perform functional or integration testing on your ZF2 applications,
        courtesy of Blanchon Vincent.
    </li>

    <li>Support for <strong>Oracle</strong> and <strong>IBM DB2</strong>
        databases in <code>Zend\Db</code>. Many thanks to Ralph Schindler for
        spearheading these efforts.
    </li>

    <li>A new <strong>Zend\Stdlib\StringUtils</strong> class to provide unified
        functionality around manipulating strings, particularly those in multibyte
        character sets. Thanks to Marc Bennewitz!
    </li>

    <li><strong>scrypt</strong> support for <code>Zend\Crypt</code>.
        Thanks go to Enrico Zimuel for this addition.
    </li>

    <li>Apache <strong>htpassword</strong> support in <code>Zend\Crypt</code> 
        and in the <strong>HTTP</strong> authentication adapter; thanks go to 
        Enrico Zimuel again!
    </li>

    <li>New integration for handling and manipulating file uploads with the
        <code>InputFilter</code>, <code>Form</code>, and <code>Mvc</code> components,
        including capablities around the <a href="http://en.wikipedia.org/wiki/Post/Redirect/Get">PRG</a>
        pattern. Please thank Chris Martin for his huge amount of work around this!
    </li>

    <li>A new <strong>render.error</strong> event, allowing you to fail
        gracefully in the event of a view rendering error. This allows you
        to present a static error page in such situations, as well as to
        log the problem. Thanks go to radnan for this addition.
    </li>

    <li>Additional integration between a variety of plugin managers and the
        service manager was created, covering form elements, filters, validators,
        route classes, and serializers; this allows application-level configuration
        of these plugin managers, providing a simplified interface for configuring
        custom plugins.
    </li>

    <li>Martin Meredith provided seven new <strong>traits</strong> for end-user use
        in PHP 5.4 applications.
    </li>

    <li>The <strong>Authentication</strong> component received support for 
        <strong>storage chains</strong> and <strong>validators</strong>.
    </li>

    <li>Better <strong>console</strong> support, including better help 
        messages, increased capabilities around colorisation, and more.
    </li>

    <li>Many incremental improvements in <code>Zend\Db</code>; in particular, 
        addition of <strong>profiling</strong> support, <strong>cross-table 
        select join</strong> support, <strong>derived table in select join</strong>,
        and <strong>literal</strong> objects.
    </li>

    <li><strong>Zend\Logger</strong> has new <strong>FirePHP</strong>, 
        <strong>ChromePHP</strong>, <strong>MongoDB</strong>, and 
        <strong>FingersCrossed</strong> writers; thanks go to Walter Tamboer, 
        Jeremy Mikola, and Stefan Kleff.
    </li>

    <li>The <strong>MVC</strong> layer sports more flexibility and capabilities 
        in the AbstractRestfulController, including automated 
        content-negotiation for JSON requests, and support for most HTTP methods, 
        including OPTIONS and HEAD (and the ability to support arbitrary HTTP methods).
    </li>

    <li><strong>Zend\Session</strong> now has a <strong>MongoDB</strong>
        save handler, and provides <strong>better interoperability</strong>
        between sessions managed by ZF2 and 3rd party code.
    </li>
</ul>

<p>
    For the complete list of more than 140 changes, 
    <a href="<?php echo $this->url('changelog/release', 
    array('version' => '2.1.0')) ?>">read the changelog</a>.
</p>

<h2>New Components</h2>

<p>
    In addition to the new components in 2.1.0, we have two new service 
    components to announce:
</p>

<ul>
    <li><a href="https://github.com/zendframework/ZendService_Apple_Apns">ZendService_Apple_Apns</a>,
        which provides push notification capabilities for Apple iOS. This component may be installed
        via Composer or Pyrus.
    </li>

    <li><a href="https://github.com/zendframework/ZendService_Google_Gcm">ZendService_Google_Gcm</a>,
        which provides push notification capabilities for Google Android. This 
        component may be installed via Composer or Pyrus.
    </li>
</ul>

<p>
    I'd like to thank Mike Willbanks for the time and effort he put into these
    components, and for providing them to the project!
</p>

<h2>New Tooling</h2>

<p>
    Enrico Zimuel has been hacking on the <strong>ZFTool</strong> project, to provide
    tooling support for the framework. This has resulted in <a 
    href="https://packages.zendframework.com/zftool.phar">zftool.phar</a>, which provides
    the following capabilities at this time:
</p>

<ul>
    <li>Skeleton application creation</li>
    <li>Module creation within a skeleton</li>
    <li>Autoloader classmap creation</li>
    <li>ZF2 installation to a directory</li>
</ul>

<p>
    Expect more capabilities in the future!
</p>

<h2>New Responsive Website</h2>

<p>
    Regular visitors to the website may notice some changes. Contributor Frank Brückner
    has been updating the site to implement a responsive design, provide more consistency
    between pages, and in particular, navigation, and over all make the site cleaner.
</p>

<p>
    The end result: the site should now be usable on a variety of platforms and 
    screen sizes, allowing you to visit on your desktop, tablet, or mobile phone!
</p>

<h2>Potential Breakage</h2>

<p>
    Both 2.0.7 and 2.1.0 include a fix to the classes <code>Zend\Filter\Encrypt</code>
    and <code>Zend\Filter\Decrypt</code> which may pose a small break for end-users.
    Each requires an encryption key be passed to either the constructor or the 
    <code>setKey()</code> method now; this was done to improve the security of each
    class.
</p>

<p>
    In 2.1.0, <code>Zend\Session</code> includes a new <code>Zend\Session\Storage\SessionArrayStorage</code>
    class, which acts as a direct proxy to the <code>$_SESSION</code> superglobal. The
    <code>SessionManager</code> class now uses this new storage class by default, in order
    to fix an error that occurs when directly manipulating nested arrays of <code>$_SESSION</code>
    in third-party code. For most users, the change will be seamless. Those affected will be
    those (a) directly accessing the storage instance, and (b) using object 
    notation to access session members:
</p>

<pre class="highlight">
$foo = null;
/** @var $storage Zend\Session\Storage\SessionStorage */
if (isset($storage->foo)) {
    $foo = $storage->foo;
}
</pre>

<p>
    If you are using array notation, as in the following example, your code 
    remains forwards compatible:
</p>

<pre class="highlight">
$foo = null;

/** @var $storage Zend\Session\Storage\SessionStorage */
if (isset($storage['foo'])) {
    $foo = $storage['foo'];
}
</pre>

<p>
    If you are not working directly with the storage instance, you will be 
    unaffected.
</p>

<p>
    For those affected, the following courses of action are possible:
</p>

<ul>
    <li>Update your code to replace object property notation with array notation, OR</li>

    <li>Initialize and register a 
        <code>Zend\Session\Storage\SessionStorage</code> object explicitly with the 
        session manager instance.
    </li>
</ul>
</p>

<h2>Thank You!</h2>

<p>
    As always, I'd like to thank the many contributors who made these
    releases possible! The project is gaining in consistency and capabilities
    daily as a result of your efforts.
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday; expect
    version 2.1.1 on 20 February 2013. The next minor release, 2.2.0,
    is tentatively scheduled for 24 April 2013.
</p>

EOC;
$post->setExtended($extended);

return $post;
