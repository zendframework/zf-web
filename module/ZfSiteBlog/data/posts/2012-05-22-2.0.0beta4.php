<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.0beta4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-05-22 15:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-05-22 15:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
The Zend Framework community is pleased to announce the immediate availability
of Zend Framework 2.0.0beta4. Packages and installation instructions are
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
This is the fourth in a series of planned beta releases. The beta release
cycle is following the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen <em>approximately</em> every six weeks. The desire is for
developers to adopt and work with new components as they are shipped, and
provide feedback so we can polish the distribution.
</p>

<p>
Our plan at this time is to have one last beta towards the end of June 2012, 
and then begin the release candidate process. At this time, the MVC is looking
and operating in the way we expect it to for the stable release.
</p>

<p>
Beta4 was tremendously busy; as I compiled the release notes and looked through
the pull requests, I discovered we'd handled more than double the number of 
pull requests, and did similarly in terms of delivering new features and rewrites.
The community did a tremendous amount of work polishing the framework and making
it better.
</p>

<p>
From the release changelog:
</p>

<pre>
 - Config component (Enrico Zimuel)
    -  Added reader and writer implementations for JSON and YAML configuration
 - Crypt and Math (Enrico Zimuel)
    - Creates a generic API for string and stream en/decryption
    - Provides bcrypt support
    - Provides BigInteger support
    - Provides common methodology surrounding credential encryption and hashing
 - Db layer (Ralph Schindler)
    - Zend\Db\Adapter: added buffer() to the ResultInterface, added that feature
      to Mysqli Result object
    - Zend\Db\Adapter: added ability to subselect Sqlite for returning a true
      count()
    - Zend\Db\Adapter: added API to return helper closures from the Adapter API
    - Renamed "database" to "schema" in all usages across Zend\Db
    - Zend\Db\Adapter: Various fixes for PDO connection parameters
    - Zend\Db\Sql: created a shared AbstractSql implementation to share
      expression processing
    - Zend\Db\Sql: created a more robust "Expression" object for use in Select
      and Predicates
    - Zend\Db\Sql: created an internal workflow and architecture to handle the
      creation of platform specific queries
    - Zend\Db\Sql: implemented limit() and offset() API to Select
    - Zend\Db\Sql: added having(), order() to SELECT API
    - Zend\Db\Sql: added alias support to Select::columns()
    - Zend\Db\TableGateway: reorganized AbstractTableGateway and TableGateway,
      removed other extensions in favor of "Features"
    - Zend\Db\TableGateway: created a "Features" API in TableGatway to promote
      horizontal extension of TableGatway
 - Di (Ralph Schindler, Marco Pivetta)
    - Added method injectDependencies($instance), to allow injecting an object
      after an instance is already available (used in the ServiceManager)
    - Various fixes based on issue reports
 - Dojo
    - REMOVED. Support was for out-dated versions of Dojo, and with the new Form
      rewrite, it needs to be completely rewritten. This is targetted for post
      2.0.0 at this time.
 - EventManager (Matthew Weier O'Phinney)
    - New SharedEventManager, a non-static version of the original
      StaticEventManager
    - StaticEventManager now extends SharedEventManager and implements a
      singleton pattern
    - New ServiceManager creates a shared instance of SharedEventManager and
      injects it in a non-shared EventManager instance per service; static usage
      is discouraged at this time.
    - attachAggregate() now accepts an optional $priority, which, when present,
      will be passed to the ListenerAggregate, allowing specifying a priority
      during attachment of its events.
    - EventManager now can handle arrays of events as well as wildcard events
    - SharedEventManager now can handle arrays of contexts, wildcard contexts,
      and arrays/wildcard events.
 - Form (Matthew Weier O'Phinney, Kyle Spraggs, Guilherme Blanco)
    - Complete rewrite
    - Elements compose a name and attributes
    - Fieldsets compose a name, attributes, and elements and fieldsets
    - Forms compose a name, attributes, elements, fieldsets, an InputFilter, and
      optionally a Hydrator and bound object.
    - New form view helpers accept the Form objects in order to generate markup.
    - Object binding allows direct binding of model data to and from the Form.
 - InputFilter (Matthew Weier O'Phinney)
    - New component for object-oriented creation of input filters
    - Input objects compose filter and validator chains, as well as metadata
      such as required, allow empty, break on failure, and more.
    - InputFilter objects compose Input and InputFilter objects, and allow
      validating the entire set or specified validation groups.
 - Log (Enrico Zimuel, Benoit Durand)
    - Refactored to provide more flexibility
    - Adds API discoverability (instead of method overloading)
    - Uses the PluginBroker for loading writers and formatters
    - Uses PriorityQueue to manage writer priority
    - Uses FilterChain for filtering messages
    - Adds a renderer for exceptions, a JSON formatter, and additional interfaces
 - Mail (Enrico Zimuel)
    - Allow batch sending via the SMTP transport
 - ModuleManager (Evan Coury, Matthew Weier O'Phinney)
    - Renamed from "Module" to "ModuleManager"
    - Renamed "Consumer" subnamespace to "Feature"
    - Added new listeners:
      - OnBootstrapListener (Module classes defining onBootstrap() will have
        that method attached as a listener on the Application bootstrap event)
      - LocatorRegistrationListener (Module classes implementing the
        LocatorRegisteredInterface feature will be injected in the
        ServiceManager)
      - ServiceListener (Module classes defining getServiceConfiguration() will
        have that method called, and the configuration merged; once all modules
        are loaded, that merged configuration will be passed to the
        ServiceManager)
 - MVC (Matthew Weier O'Phinney, Ralph Schindler, Evan Coury)
    - Removed Bootstrap class and rewrote Application class
      - Composes a ServiceManager, and simply fires events
    - Added RouteListener and DispatchListener classes, implementing the default
      route and dispatch strategies.
    - Created a new "Service" subnamespace, with ServiceManager configuration
      and factories for the default MVC services.
    - Created a new "ViewManager" class, which triggers on the bootstrap event,
      at which time it creates the various objects of the view layer and wires
      them together as well as registers them with the appropriate events.
    - InjectTemplateListener now uses the controller namespace to further
      namespace the view template; the default is now 
      "<normalized top-level namespace>/<normalized controller name>/<action>"
 - ServiceManager component (Ralph Schindler, Matthew Weier O'Phinney)
    - Highly performant, programmatic service creation
    - Largely replaces DI, but can also consume Zend\Di
    - Allows:
      - Service registration
      - Lazy-loaded service objects
      - Service factories
      - Service aliasing
      - Abstract (fallback) factories
      - Initializers (manipulate instances after creation)
    - Fully integrated in the MVC solution
 - Renamed interfaces (Gabriel Baker, Sascha Prolic, Maks3w)
   - Most, if not all, interfaces were renamed to suffix with the word
     "Interface". This is to promote discovery of interfaces, as well as make
     naming simpler.
   - Exceptions are affected by this as well. Exception marker interfaces were
     renamed to ExceptionInterface and pushed into the Exception subnamespace of
     each component
 - Composer support (Rob Allen, Marco Pivetta, Kyle Spraggs)
   - Zend Framework is now installable via Composer (http://packagist.org/), as
     are each of its individual components
 - Travis CI integration (Marco Pivetta, Maks3w)
   - ZF2 is tested on each commit by http://travis-ci.org/
</pre>

<p>
    In all, well over <strong>400</strong>(!) bug and feature requests were 
    handled since 2.0.0beta2 -- about twice what was handled for beta3, which
    was in turn twice what we handled for beta2! We've definitely hit some
    critical momentum and adoption.
</p>

<p>
    Two changes are particularly noteworthy, as they are not so much code changes
    as infrastructure changes: ZF2 now has <a 
    href="http://packagist.org">Composer</a> support on a per-component basis, and 
    the project now has continuous integration via <a 
    href="http://travis-ci.org">Travis CI</a>. A number of contributors helped
    make these initiatives not only possible, but succesful.
</p>

<p>
    The <a href="http://github.com/zendframework/ZendSkeletonApplication">skeleton
    application</a> and a <a
    href="http://github.com/zendframework/ZendSkeletonModule">skeleton
    module</a> have been updated for 2.0.0beta4, and are a 
    great place to look to help get you started. You may also want to check out the <a
    href="http://packages.zendframework.com/docs/latest/manual/en/zend.mvc.quick-start.html">quick start
    guide to the MVC</a>.
</p>

<p>
As a reminder, all ZF2 components are also available individually as <a href="http://pear2.php.net">Pyrus</a> 
 AND composer packages; packages.zendframework.com is our official channel.
</p>

<p>
I'd like to thank each and every person who has contributed ideas, feedback, 
pull requests (no pull request is too small!), testing, and more -- I'm constantly
amazed at the amount of energy and support the community contributes to the framework!
</p>
EOC;
$post->setExtended($extended);

return $post;
