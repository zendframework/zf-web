<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-11-21 Dev status update');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-11-21 12:35', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-11-21 12:35', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    What's going on in ZF2 development this week?
</p>

<p>
A few things!
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2 id="toc_3.1">Repository Changes!</h2>

<p>
We've now moved our canonical repository to GitHub, which is where most folks
were forking and doing development anyways. The reasons were several:
</p>

<ul class="ul">
<li>
The main reason for self-hosting was to make checking CLA status simpler. As
   ZF2 development no longer requires a CLA, this reason is gone.
</li>
<li>
ACLs for providing commit access are easier to manage on GitHub, and do not
   require us to first receive SSH keys from contributors.
</li>
<li>
Using GitHub directly simplifies the pull request process. When
   self-hosting, we would merge and push to the canonical repo, and then need to
   manually close the pull request; using GitHub, PRs are automatically closed
   when the code is merged. Additionally, because the mirroring only occurred a
   few times per day, it was not immediately evident on GitHub when a change
   was available to test.
</li>
<li>
There was often confusion by developers on where the most current changes
   were, particularly if they forked from the GitHub repository.
</li>
</ul>

<p>
The practical upshot is that if you had git.zendframework.com as a remote on
your repository, you should remove it. If you didn't have a
github.com/zendframework/zf2 remote, you should add one. The
<a href="http://bit.ly/zf2gitguide">ZF2 Git Guide</a> details adding the remote.
</p>

<h2 id="toc_3.2">MVC Developments</h2>

<p>
Two big things occurred in the MVC this week.
</p>

<p>
First, we did some re-thinking of the duties of the Module Manager. Previously,
it was responsible for merging configuration and firing module initialization.
A recommended part of module initialization was to initialize autoloading.
</p>

<p>
What we noticed was:
</p>

<ul class="ul">
<li>
Configuration merging was getting more complex, and we were getting
   potentially incompatible feature requests.
</li>
<li>
Our modules were getting hard dependencies on things like autoloaders, and
   were also introducing a lot of boiler-plate code.
</li>
<li>
We had no clear path to how we might cache autoloading configuration for
   production.
</li>
<li>
We were noticing more and more places where we might want to loop through the
   loaded modules in order to trigger methods of interest.
</li>
</ul>

<p>
So, the solution was to change things: <code>Zend\Module\Manager::loadModule()</code> now
triggers a "loadModule" event, passing it the newly created module. This allows
listeners to react to modules real-time as they're loaded. 
</p>

<p>
This also meant that the code for the following actions could be moved to
listeners:
</p>

<ul class="ul">
<li>Autoloading</li>
<li>Configuration loading</li>
<li>Module "initialization"</li>
</ul>

<p>
This allows modules that look like this:
</p>

<pre class="highlight">
namespace Blog;

use InvalidArgumentException,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    public function init()
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'bootstrap'));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/configs/module.config.php';
    }
    
    /* ... */
}
</pre>

<p>
This looks about the same as before! The differences are:
</p>

<ul class="ul">
<li><code>getAutoloaderConfig()</code> can simply return an array of options to pass to
   <code>Zend\Loader\AutoloaderFactory</code>. This allows us to obtain a fully configured
   autoloader at the end -- which we will eventually be able to cache, and thus
   eliminate the need for the autoloader listener.
</li>
<li><code>getConfig()</code> is no longer called directly by the module manager, but instead
   by a listener. Again, this will make it possible to cache the full
   application configuration.
</li>
<li><code>init()</code> is called by a listener now. </li>
</ul>

<p>
In other words, the differences are largely under the hood. But those
differences mean that it's trivially easy to develop your own listeners to tie
into the module loading process in order to do interesting things -- all without
needing to touch or extend the main module manager.
</p>

<h2 id="toc_3.3">Application Configuration</h2>

<p>
Several people indicated that much as they like module configuration merging,
they weren't liking the solutions for overriding configuration at the
application level. The solutions to date have been:
</p>

<ul class="ul">
<li>Register the module with overrides last -- for instance, your Application module.</li>
<li>Create a configuration-only module that registers last.</li>
</ul>

<p>
The consensus was that a module for simply providing configuration overrides
"sucks", and that using the "Application" module sometimes was problematic
(especially for purposes of registering view script paths).
</p>

<p>
The solution was to add some logic to provide application-level configuration.
This was added to the configuration listener, and allows for you to specify a
directory with configuration (ala "conf.d" style configuration now commonly used
across a number of *nix distributions); this configuration is then merged after
module configuration is aggregated. Your <code>index.php</code> would then contain
something like the following:
</p>

<pre class="highlight">
$moduleManager = new Zend\Module\Manager($appConfig['modules']);
$listenerOptions = new Zend\Module\Listener\ListenerOptions($appConfig['module_listener_options']);
$moduleManager->setDefaultListenerOptions($listenerOptions);
$moduleManager->getConfigListener()->addConfigGlobPath(dirname(__DIR__) . '/config/autoload/*.config.php');
$moduleManager->loadModules();
</pre>

<p>
Hopefully, these changes will simplify how app-specific configuration is
managed.
</p>

<h2 id="toc_3.4">Beta2 is coming!</h2>

<p>
Beta2 is coming up soon! We're hoping to have it ready by the end of the month.
The components currently under development for beta2 include:
</p>

<ul class="ul">
    <li>Zend\Log</li>
    <li>Zend\Cache</li>
    <li>Zend\Mail</li>
</ul>

<p>
Cache is mostly complete and needs some review and input regarding its usage of
the EventManager. Log and Mail are currently under development. We encourage
you to reach out on the #zftalk.2 IRC channel on Freenode or the zf-contributors
mailing list if you would like to assist with testing or development of these
components.
</p>

<h2 id="toc_3.5">IRC meeting this week</h2>

<p>
We have another <a 
href="http://framework.zend.com/wiki/display/ZFDEV2/2011-11-23+Meeting+Agenda">IRC 
meeting this week</a>. Follow the link to see the agenda -- and add to it if 
you want to discuss additional topics. 
</p>

EOC;
$post->setExtended($extended);

return $post;
