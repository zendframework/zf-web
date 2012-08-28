<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-09-26 Dev status update');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-09-26 15:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-09-26 15:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    Zend Framework status update for the weeks of 13 - 26 September 2011.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2 id="toc_4.1">2011-09-14 IRC Meeting</h2>

<p>
We held our third IRC meeting on Wednesday, 14 September 2011. On the agenda
were:
</p>

<ul class="ul">
<li>
<a href="http://framework.zend.com/wiki/display/ZFDEV2/Comparison+of+config+styles">RFC on configuration</a>
</li>
<li>
Where components falling outside the standard distribution should live if
   incomplete
</li>
<li>
Goals of the Module Manager
</li>
<li>
Directory structure for modules
</li>
</ul>

<h3 id="toc_4.1.1">Configuration</h3>

<p>
Between the competing RFCs on configuration, the IRC discussion, and some
back-and-forth following the meeting, the following summarizes the current
consensus on how ZF2 will deal with component configuration:
</p>

<ul class="ul">
<li>
Hard dependencies that do not have sane defaults would be in the constructor
   signature
</li>
<li>
The last argument in the constructor signature would be optional, and expect
   an instance of the component's Options class
</li>
<li>
If a dependency has a sane default, we would not include an argument in the
   constructor, but <em>would</em> allow setter injection (which could be automated by
   the DI container)
</li>
<li>
The component's Options class would handle optional configuration arguments,
   and provide validation for those arguments.
</li>
<li>
The component would directly access optional arguments from the Options
   instance it composes
</li>
</ul>

<p>
Note the word "Options". In discussions, we decided we should call this
functionality "Options" as it denotes that the values are optional, and also to
prevent nomenclature conflicts with the already existing <code>Zend\Config</code>
component. We will be producing a poll on the wiki to do a final vote <em>very soon</em>. 
</p>

<h3 id="toc_4.1.2">Unfinished "Extras" components</h3>

<p>
The discussion centered around whether or not we would remove unrefactored
components that fall outside the standard distribution -- things like service
components that have not been converted to namespaces, updated to latest
exceptions practices, and not refactored to use the new HTTP functionality.
</p>

<p>
Only slight debate erupted -- the decision is:
</p>

<ul class="ul">
<li>
Keep this functionality in the master branch
</li>
<li>
Add an annotation such as "@incomplete" to the file and/or class-level
   docblocks.
</li>
<li>
Add the "@incomplete" annotation to related test classes, and add a rule to
   <code>phpunit.xml</code> to skip such tests.
</li>
<li>
Comment out sections in the manual referring to these components.
</li>
</ul>

<p>
We will then add rules to packaging to omit such classes and resources.
</p>

<h3 id="toc_4.1.3">Module Manager Goals</h3>

<p>
The Module Manager is a component for scanning and initializing modules during
application bootstrapping. As such, it's fairly central to the new MVC approach,
and we wanted to be sure we captured its goals. A sizable list of goals was
presented, and we voted on each. There are too many to relate here, so instead
I'll simply
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-09-14+Meeting+Log#2011-09-14MeetingLog-ModuleManagergoals">link you to the summary</a>.
</p>

<p>
Most items were straight-forward, but there are a few conflicting ideas about
what the scope of the manager; should it simply act as a kernel for
bootstrapping, or have deep ties within the application?
</p>

<p>
Current development of this tool has taken the former approach, but has exposed
a number of useful features that allow a ton of flexibility for a variety of
approaches. One, in particular, is a method <code>getLoadedModules()</code>, which returns
an associative array of module name/module objects. With this, I was able to do
such things as loop through modules, check for the existence of specific
methods, and then call them to do things such as event listener registration. 
</p>

<h3 id="toc_4.1.4">Module Directory Structure</h3>

<p>
By the time of the meeting, we already had a couple different module directory
structures floating around, and the discussion centered on which one to use.
Except that several people brought up one very, very good point: with a
well-known class (the module's "Module" class) that we're querying, the
structure doesn't matter, and does not need to be enforced.
</p>

<p>
As such, our decision was that we should have a <em>recommended</em> structure that
satisfies the various use cases we originally brainstormed. Among these:
</p>

<ul class="ul">
<li>
Ability to serve multiple namespaces (if desired)
</li>
<li>
Separation of code from non-code assets, if provided (such as CSS, JS, HTML)
</li>
<li>
Separation of view templates, if provided, from code
</li>
<li>
Separation of configuration, if provided, from code
</li>
</ul>

<p>
As such, the following structure was <em>generally</em> agreed upon as a
recommendation:
</p>

<pre class="highlight">
modules/
    SpinDoctor/
        Module.php
        autoload_classmap.php
        autoload_function.php
        autoload_register.php
        configs/
            module.config.php
            routes.config.ini
        public/
            images/
            css/
                spin-doctor.css
            js/
                spin-doctor.js
        src/
            SpinDoctor/
                Controller/
                    SpinDoctorController.php
                    DiscJockeyController.php
                Form/
                    Request.php
        tests/
            bootstrap.php
            phpunit.xml
            SpinDoctor/
                Controller/
                    SpinDoctorControllerTest.php
                    DiscJockeyControllerTest.php
        views/
            spin-doctor/
                album.phtml
            disc-jockey/
                turntable.phtml
</pre>

<p>
Public assets such as images, CSS, and JS could either be symlinked into the
public tree, copied, or managed by an asset manager such as
<a href="https://github.com/kriswallsmith/assetic">Assetic</a>. 
</p>

<p>
Regarding the various <code>autoload_*.php</code> files, these were brought up in a
<a href="http://ralphschindler.com/2011/09/19/autoloading-revisited">post from Ralph</a>,
based on discussions he and I have had. The idea behind these is that we can
satisfy several typical use cases for modules:
</p>

<ul class="ul">
<li>
Download and use: simply <code>require 'autoload_register.php';</code> and start using
   classes.
</li>
<li>
Use with custom registration: <code>spl_autoload_register(include 'autoload_function.php');</code>, 
   which allows you to specify to <code>spl_autoload_register</code> whether or not to
   append or prepend the function to your autoloader stack.
</li>
<li>
Custom autoloading class map: aggregate the returns of
   <code>autoload_classmap.php</code> into a single classmap for your application.
</li>
</ul>

<p>
There's still some discussion going around these files, but they've been adopted
in the prototypes at this time.
</p>

<h2 id="toc_4.2">MVC Prototype Status</h2>

<p>
The MVC prototype has grown tremendously, in large part due to the efforts of
<a href="http://evan.pro/">Evan Coury</a> and his work on the Module Manager. The module
manager now does the following:
</p>

<ul class="ul">
<li>
Aggregates configuration from all modules
    <ul class="ul">
    <li>
    Including modules provided as phars!
    </li>
    </ul>
</li>
<li>
Provides introspection and access to discovered modules
</li>
<li>
Optionally allows configuration caching
</li>
</ul>

<p>
The MVC prototype has also grown. Based on a suggestion from Greg N. on the
mailing lists, the EventManager was refactored slightly to do the following:
</p>

<ul class="ul">
<li>
Removed references to "handlers" in favor of "listeners" to provide a
   consistent terminology (both internally as well as with other systems of
   similar design)
</li>
<li>
<code>trigger</code> now allows passing an <code>Event</code> object for any one of its required
   arguments. This allows creation of custom <code>Event</code> objects, as well as re-use
   of them.
</li>
<li>
<code>trigger</code> was modified to allow an optional final argument, either the fourth
   argument or an argument following an <code>Event</code> object: a callback that
   indicates whether or not to halt execution. This largely eliminates the need
   for <code>triggerUntil</code> at this time.
</li>
<li>
Locally attached listeners are now combined with static listeners into a
   single priority queue when <code>trigger</code> is called. This provides a consistent
   expectation, and allows you to register static listeners that can be called
   prior to those attached locally.
</li>
</ul>

<p>
What the above allowed was the ability to move routing and dispatching into
listeners within <code>Zend\Mvc\Application</code>, using a custom <code>MvcEvent</code> object. The
upshot is:
</p>

<ul class="ul">
<li>
Simpler code
</li>
<li>
Accessors for well-known (and/or expected) objects (e.g., <code>getRequest()</code>,
   <code>getRouteMatch()</code>, <code>getResult()</code>)
</li>
<li>
Events are registered with priority in order to shape the execution workflow
</li>
<li>
The ability to replace the default routing and dispatch listeners with custom
   listeners.
</li>
</ul>

<p>
This last point allowed me to prototype a 
<a href="https://github.com/weierophinney/zf-quickstart/tree/features/zf2_mvc-zf1_emulation">ZF1-emulation layer in the new ZF2 paradigm</a>.
This effort was surprisingly easy, and helped illustrate how flexible the
prototype can be.
</p>

<p>
Additional work on the MVC centered around error handling, and providing a
simple mechanism for discovering and handling errors. The 
<a href="https://github.com/weierophinney/zf-quickstart/tree/features/zf2-mvc-module">zf-quickstart example</a>
showed that this largely eliminates the need for an <code>ErrorController</code>.
</p>

<p>
At this time, we're quickly closing in on what the MVC will likely look like for
ZF2, and hope to merge the <code>ZendMvc</code> and <code>ZendModule</code> modules into the library
very soon.
</p>

<h2 id="toc_4.3">DI Annotation Support</h2>

<p>
Ralph has been refactoring the <code>Reflection</code>, <code>CodeGenerator</code>, and <code>Code\Scanner</code>
components to follow a consistent API, and to live under a common tree,
<code>Zend\Code</code>. Part of this work is to also provide a parser/tokenizer for PHP
docblocks, with the goal of providing annotation support to components that need
it. In particular, this could assist the Dependency Injection component,
allowing more intelligent hinting as to what setters are required and/or the
specific arguments to inject. Another potential use might be with modules, to
indicate what they provide and/or methods that should be called at
initialization.
</p>

<p>
This work should hit master this week, and will be compatible with the
<a href="http://pecl.php.net/package/docblock">docblock extension</a>.
</p>

<h2 id="toc_4.4">Cloud Infrastructure</h2>

<p>
Enrico has been working on updating the Amazon, Rackspace, and GoGrid services
to use the new HTTP functionality. In doing so, he's discovered areas where the
HTTP component needed improvement, as well as places he could better test. At
this time, all changes he's done are incorporated in the master branch, with the
exception of some tests for GoGrid.
</p>

<p>
This work has helped round out a new offering in Zend Framework, as well as to
test recent development work and ideas such as the HTTP component and the
Options proposal.
</p>

<h2 id="toc_4.5">The Future</h2>

<p>
There are new RFCs and discussions erupting regularly on the mailing list and in
the #zftalk.2 IRC channel on Freenode; I encourage you to subscribe to the former and join in the latter so that you can participate.
</p>

<p>
Also, we have an IRC meeting <em>this week</em>, 28 September 2011 at 17:00 UTC. The
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-09-28+Meeting+Agenda">agenda</a>
is online, but could potentially use some more topics and votes. Talk to you
Wednesday!
</p>
EOC;
$post->setExtended($extended);

return $post;
