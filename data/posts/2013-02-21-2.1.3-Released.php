<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.1.3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-02-21 16:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-02-21 16:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.1.3! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This release has been pushed out quickly on the heels of 2.1.2 to fix an
    issue with autoloading PHP version-specific class implementations that was
    affecting PHP 5.3.3 users. Two other potential regressions were also
    addressed.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>PHP 5.3.3 Users</h2>

<p>
    This release finally resolves issues with providing PHP version-specific 
    classes, specifically for PHP 5.3.3 users.
</p>

<p>
    If you are using <a href="http://getcomposer.org/">Composer</a> to manager
    your dependencies, a <code>composer.phar update</code> should resolve any
    issues.
</p>

<p>
    If you are not, you have two options.
</p>

<p>
    First, when starting new applications with the <a href="https://github.com/zendframework/ZendSkeletonApplication">ZendSkeletonApplication</a>,
    class substitution will now happen by default.
</p>

<p>
    Otherwise, add the following lines to your <code>init_autoloader.php</code>
    file, as indicated by the comments below:
</p>

<pre class="highlight">
// The following line should be on or around line 34:
if ($zf2Path) {
    if (isset($loader)) {
        $loader->add('Zend', $zf2Path);
    } else {
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true
            )
        ));

        // Add the following two lines:
        require $zf2Path . '/Zend/Stdlib/compatibility/autoload.php';
        require $zf2Path . '/Zend/Session/compatibility/autoload.php';
    }
}
</pre>

<h2>Routing Fixes</h2>

<p>
    Two fixes to routing were made after discovering potential regressions.
</p>

<p>
    The first was to hostname routing. Changes were introduced in 2.1.2 to make 
    matching optional nested subdomains possible; unfortunately, this broke cases
    where the primary domain was specified. A fix has been included in 2.1.3 that
    fixes the regression (while simultaneously allowing the new behavior).
</p>

<p>
    A bug in console routing was also uncovered; camelCased or MixedCase options
    were allowed in route definitions, but route matching was normalizing options
    to lowercase, causing false negative matches. This was fixed for 2.1.3.
</p>

<h2>Changelog</h2>

<p>
    Below are links to the issues addressed.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/3714">3714: Zend\Stdlib\ArrayObject::offsetExists() returning by reference</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3855">3855: Fix #3852</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3856">3856: Simple route case insensitive</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    I'd like to thank those that tested the PHP 5.3.3 autoloading fixes, as well
    as Nick Calugar for providing the fix to hostname routing and Michael Gallego
    for the fixes to console routing.
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday; expect version 2.1.4
    to drop 20 March 2013. We're also gearing up for version 2.2.0, which we are 
    targetting at the end of April 2013.
</p>

EOC;
$post->setExtended($extended);

return $post;
