<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.0beta3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-03-02 13:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-03-02 13:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta3. Packages and installation instructions are
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
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta3. Packages and installation instructions are
available at:
</p>

<dl>
    <dd>
    <a href="http://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </dd>
</dl>

<p>
This is the third in a series of planned beta releases. The beta release
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
Featured components and functionality of 2.0.0beta3 include:
</p>

<ul class="ul">
    <li><b>Refactored Config component</b>
    <ul class="ul">
        <li>All configuration readers have been moved under the <code>Zend\Config\Reader</code> namespace</li>
        <li>A <code>Zend\Config\Factory</code> has been added to simplify retrieving a config object from a config file</li>
        <li>Supported configuration formats now support importing additional configuration files</li>
        <li>All constant processing has been moved to a <code>Zend\Config\Processor</code> namespace, and expanded to be more powerful</li>
        <li>Work was provided by Ben Scholzen, Artur Bodera, Enrico Zimuel, and Evan Coury</li>
    </ul>
    </li>
    <li><b>New View layer</b>
    <ul class="ul">
        <li>New subcomponents include <code>Zend\View\Model</code>, <code>Zend\View\Resolver</code>, <code>Zend\View\Renderer</code>, and <code>Zend\View\Strategy</code></li>
        <li>The old <code>Zend_View</code> class has been moved to <code>Zend\View\Renderer\PhpRenderer</code>, and rewritten to move most of its responsibilities into collaborators, greatly simplifying its design while simultaneously giving it more capabilities.</li>
        <li>A new class, <code>Zend\View\View</code>, allows selecting rendering strategies on a per-template basis, based on arbitrary criteria, and optionally injecting rendering results into a Response object</code>
        <li>MVC integration streamlines common use cases, including View Model creation and injection, 404 and error page creation, and more.</li>
        <li>Work was provided by Matthew Weier O'Phinney, with copious feedback from Rob Allen.</li>
    </ul>
    </li>
    <li><b>Rewritten DB layer</b>
    <ul class="ul">
        <li>New architecture features low-level drivers, which also provide access to
    the PHP resource being consumed; adapters, which provide basic abstraction
    for common CRUD operations; new SQL abstraction layer, with full predicate
    support; abstraction for ResultSet's, with the ability to cast rows to
    specific object types; abstraction for SQL metadata; and a revised Table and
    Row Data Gateway.</li>
        <li>Work was provided by Ralph Schindler</li>
    </ul>
    </li>
    <li><b>New AgileZen component</b>
    <ul class="ul">
      <li>Support for the full <a href="http://www.agilezen.com">AgileZen</a> API</li>
      <li>Developed for use with <a href="http://framework.zend.com/zf2/board">our planning board</a> </li>
      <li>Work was provided by Enrico Zimuel</li>
    </ul>
    </li>
    <li><b>PHP 5.4 Support</b>
    <ul class="ul">
      <li>A number of issues when running ZF2 under PHP 5.4 were identified and corrected.</li>
    </ul>
    </li>
</ul>

<p>
    A number of other components received a fair amount of attention during 
    this beta cycle, including Zend\GData, Zend\Navigation, Zend\Session, 
    Zend\Service\StrikeIron, Zend\Service\Technorati, Zend\Service\Twitter, 
    Zend\Http\Header\Accept*, Zend\Ldap, Zend\OAuth. A fair amount of feedback 
    on the MVC and Module components was also provided and acted upon.
</p>

<p><b>
    In all, around 200 bug and feature requests were handled since 2.0.0beta2 --
    about twice what was handled for beta2!
</b></p>


<p>
    The <a href="http://github.com/zendframework/ZendSkeletonApplication">skeleton
    application</a> and a <a
    href="http://github.com/zendframework/ZendSkeletonModule">skeleton
    module</a> have been updated for 2.0.0beta3, and are a 
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
pull requests (no pull request is too small!), testing, and more -- what the
Zend Framework community has accomplished in the last two months is nothing
short of astonishing!
</p>
EOC;
$post->setExtended($extended);

return $post;

