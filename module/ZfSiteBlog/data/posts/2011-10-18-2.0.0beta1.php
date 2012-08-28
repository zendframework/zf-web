<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.0beta1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-10-18 08:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-10-18 08:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta1. Packages and installation instructions are
available at:
</p>

<dl>
    <dd>
    <a href="http://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </dd>
</dl>
EOS;
$post->setBody($body);

$extended =<<<'EOC'

<p>
This is the first in a series of planned beta releases. The beta release
cycle will follow the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen <em>no less than</em> every six weeks. The desire is for
developers to adopt and work with new components as they are shipped, and
provide feedback so we can polish the distribution.
</p>

<p>
Once all code in the proposed <a href="http://framework.zend.com/wiki/pages/viewpage.action?pageId=43745438">standard distribution</a> 
has reached maturity and reasonable stability, we will freeze the API and
prepare for Release Candidate status. 
</p>


<p>
Featured components and functionality of 2.0.0beta1 include:
</p>

<ul>
<li>
New and refactored autoloaders:
<ul>
<li>
Zend\Loader\StandardAutoloader
</li>
<li>
Zend\Loader\ClassMapAutoloader
</li>
<li>
Zend\Loader\AutoloaderFactory
</li>
</ul>
</li>
<li>
New plugin broker strategy
<ul>
<li>
Zend\Loader\Broker and Zend\Loader\PluginBroker
</li>
</ul>
</li>
<li>
Reworked Exception system
<ul>
<li>
Allow catching by specific Exception type
</li>
<li>
Allow catching by component Exception type
</li>
<li>
Allow catching by SPL Exception type
</li>
<li>
Allow catching by base Exception type
</li>
</ul>
</li>
<li>
Rewritten Session component
</li>
<li>
Refactored View component
<ul>
<li>
Split helpers into a PluginBroker
</li>
<li>
Split variables into a Variables container
</li>
<li>
Split script paths into a TemplateResolver
</li>
<li>
Renamed base View class "PhpRenderer"
</li>
<li>
Refactored helpers to utilize __invoke() when possible
</li>
</ul>
</li>
<li>
Refactored HTTP component
</li>
<li>
New Zend\Cloud\Infrastructure component
</li>
<li>
New EventManager component
</li>
<li>
New Dependency Injection (Zend\Di) component
</li>
<li>
New Code component
<ul>
<li>
Incorporates refactored versions of former Reflection and
     CodeGenerator components.
</li>
<li>
Introduces Scanner component.
</li>
<li>
Introduces annotation system.
</li>
</ul>
</li>
</ul>

<p>
The above components provide a solid foundation for Zend Framework 2, and
largely make up the framework "core". However, the cornerstone feature of beta1
is what they enable: the new MVC layer:
</p>

<ul>
<li>
Zend\Module, for developing modular application architectures.
</li>
<li>
Zend\Mvc, a completely reworked MVC layer built on top of HTTP,
   EventManager, and Di.
</li>
</ul>

<p>
We've built a <a
    href="http://github.com/zendframework/ZendSkeletonApplication">skeleton
    application</a> and a <a
    href="http://github.com/zendframework/ZendSkeletonModule">skeleton
    module</a> to help get you started, as well as a <a
    href="http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.quick-start.html">quick start
    guide to the MVC</a>; the new MVC is truly flexible, and moreover, simple and
powerful. 
</p>

<p>
Additionally, for those who haven't clicked on the packages link above, we are
debuting our new distribution mechanisms for ZF2: the ability to use
<a href="http://pear2.php.net">Pyrus</a> to install individual components and/or groups of
components.
</p>

<p>
Since mid-August, we've gone from a <em>few dozen</em> pull requests on the
<a href="http://github.com/zendframework/zf2">ZF2 git repository</a> to <strong><em>over 500</em></strong>,
originating from both long-time Zend Framework contributors as well as those
brand-new to the project. I'd like to thank each and every one of them, but also
call out several individuals who have made some outstanding and important
contributions during that time frame:
</p>

<ul>
<li>
<a href="http://evan.pro">Evan Coury</a>, who prototyped and then implemented the new
   module system.
</li>
<li>
<a href="http://akrabat.com">Rob Allen</a>, who, because he was doing a tutorial at
   <a href="http://conference.phpnw.org.uk/">PHPNW</a> on ZF2, provided a lot of early
   feedback, ideas, and advice on the direction of the MVC.
</li>
<li>
<a href="http://www.dasprids.de/">Ben Scholzen</a>, who wrote a new router system, in
   spite of a massive injury from a cycling accident.
</li>
<li>
<a href="http://ralphschindler.com">Ralph Schindler</a>, who has had to put up with my
   daily "devil's advocate" and "think of the user!" rants for the past several
   months, and still managed to provide comprehensive code manipulation tools, a
   Dependency Injection framework, and major contributions to the HTTP
   component.
</li>
<li>
<a href="http://www.zimuel.it/en/">Enrico Zimuel</a>, who got tossed requirements for the
   cloud infrastructure component, and then had to rework most of it after
   rewriting the HTTP client from the ground up... and who still managed three
   back-to-back-to-back conferences as we prepared the release.
</li>
<li>
<a href="http://www.linkedin.com/in/abodera">Artur Bodera</a>, who often has played
   devil's advocate, and persisted pressing his opinions on the direction of the
   framework, often despite heavy opposition. We may not implement all (or many)
   of the features you want, but you've definitely influenced the direction of
   the MVC incredibly.
</li>
<li>
<a href="http://blog.astrumfutura.com/">Pádraic Brady</a>, who
   <a href="http://zend-framework-community.634137.n4.nabble.com/A-Rant-From-Mr-Grumpy-on-ZF2-tp3721463p3721463.html">started the runaway train rolling with a rant</a>, 
   and helped make the project much more transparent, enabling the MVC
   development to occur in the first place.
</li>
</ul>

<p>
Welcome to the ZF2 beta cycle!
</p>
EOC;
$post->setExtended($extended);

return $post;
