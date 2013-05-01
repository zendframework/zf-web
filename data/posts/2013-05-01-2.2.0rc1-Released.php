<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.0rc1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-05-01 16:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-05-01 16:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.0rc1! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="https://packages.zendframework.com/">http://packages.zendframework.com/</a>
    </li>
</ul>

<p>
    This is a <em>release candidate</em>. It is not the final release, and 
    while stability is generally considered good, there may still be issues
    to resolve between now and the stable release. Use in production with 
    caution.
</p>

<p>
    <strong>DO</strong> please test your applications on this RC, as we would 
    like to ensure that it remains backwards compatible, and that the migration
    path is smooth.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Changes in this version</h2>

<ul>
    <li>
    <p>
        <strong>Addition of many more plugin managers and abstract service factories.</strong>
        In order to simplify usage of the <code>ServiceManager</code> as an <a href="http://en.wikipedia.org/wiki/Inversion_of_Control">Inversion of Control</a> 
        container, as well as to provide more flexibility in and consistency in how various
        framework components are consumed, a number of plugin managers and service factories
        were created and enabled. 
    </p>

    <p>
        Among the various plugin managers created are Translator loader manager, a Hydrator
        plugin manager (allowing named hydrator instances), and an InputFilter manager.
    </p>

    <p>
        New factories include a Translator service factory, and factories for 
        both the Session configuration and SessionManager.
    </p>
        
    <p>
        New abstract factories include one for the DB component (allowing you to manage
        multiple named adapters), Loggers (for having multiple Logger instances),
        Cache storage (for managing multiple cache backends), and Forms (which makes use
        of the existing FormElementsPluginManager, as well as the new Hydrator and InputFilter
        plugin managers).
    </p>
    </li>

    <li>
    <p>
        <strong>Data Definition Language (DDL) support in Zend\Db.</strong> DDL 
        provides the ability to create, alter, and drop tables in a relational 
        database system. Zend\Db now offers abstraction around DDL, and 
        specifically MySQL and ANSI SQL-92; we will gradually add this 
        capability for the other database vendors we support.
    </p>
    </li>

    <li>
        <strong>Authentication:</strong> The DB adapter now supports non-RDBMS credential validation.
    </li>

    <li>
        <strong>Cache:</strong> New storage backend: Redis.
    </li>

    <li>
        <strong>Code:</strong> The ClassGenerator now has a removeMethod() method.
    </li>

    <li>
        <strong>Console:</strong> Incremental improvements to layout and colorization of banners
        and usage messages; fixes for how literal and non-literal matches are returned.
    </li>

    <li>
        <strong>DB:</strong> New DDL support (noted earlier); many incremental improvements.
    </li>

    <li>
        <strong>Filter:</strong> New DateTimeFormatter filter.
    </li>

    <li>
        <strong>Form:</strong> Many incremental improvements to selected 
        elements; new FormAbstractServiceFactory for defining form services; minor improvements
        to make the form component work with the DI service factory.
    </li>

    <li>
        <strong>InputFilter</strong>: new CollectionInputFilter for working 
        with form Collections; new InputFilterPluginManager providing 
        integration and services for the ServiceManager.
    </li>

    <li>
        <strong>I18n:</strong> We removed ext/intl as a hard requirement, and made it only a
        suggested requirement; the Translator has an optional dependency on the EventManager,
        providing the ability to tie into "missing message" and "missing translations" events;
        new country-specific PhoneNumber validator.
    </li>

    <li>
        <strong>ModuleManager:</strong> Now allows passing actual Module instances (not just names).
    </li>

    <li>
        <strong>Navigation:</strong> Incremental improvements, particularly to URL generation.
    </li>

    <li>
        <strong>MVC:</strong> You can now configure the initial set of MVC 
        event listeners in the configuration file; the MVC stack now detects generic HTTP responses
        when detecting event short circuiting; the default ExceptionStrategy 
        now allows returning JSON; opt-in translatable segment routing; many incremental
        improvements to the AbstractRestfulController to make it more configurable and
        extensible; the Forward plugin was refactored to no longer require a 
        ServiceLocatorAware controller, and instead receive the ControllerManager via its
        factory.
    </li>

    <li>
        <strong>Paginator:</strong> Support for TableGateway objects.
    </li>

    <li>
        <strong>ServiceManager:</strong> Incremental improvements; performance optimizations;
        delegate factories, which provide a way to write factories for objects that replace
        a service with a decorator; "lazy" factories, allowing the ability to 
        delay factory creation invocation until the moment of first use.
    </li>

    <li>
        <strong>Stdlib:</strong> Addition of a HydratorAwareInterface; creation 
        of a HydratorPluginManager.
    </li>

    <li>
        <strong>SOAP:</strong> Major refactor of WSDL generation to make it more maintainable.
    </li>

    <li>
        <strong>Validator:</strong> New Brazilian IBAN format for IBAN validator; validators 
        now only return unique error messages; improved Maestro detection in 
        CreditCard validator.
    </li>

    <li>
        <strong>Version:</strong> use the ZF website API for finding the latest version,
        instead of GitHub.
    </li>

    <li>
        <strong>View:</strong> Many incremental improvements, primarily to 
        helpers; deprecation of the Placeholder Registry and removal of it from 
        the implemented placeholder system; new explicit factory classes for helpers
        that have collaborators (making them easier to override/replace).
    </li>
</ul>

<h2>Changelog</h2>

<p>
    Almost 200 patches were applied for 2.2.0. We will not release a full
    changelog until we create the stable release. In the meantime, you can
    view a full set of patches applied for 2.2.0 in the 2.2.0 milestone on
    GitHub:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues?milestone=14&state=closed">Zend Framework 2.2.0 milestone</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Please join me in thanking everyone who provided new features and code 
    improvements for this upcoming 2.2.0 release!
</p>

<h2>Roadmap</h2>

<p>
    We plan to release additional RCs every 3-5 days until we feel the 2.2.0
    release is generally stable; we anticipate a stable release in the next
    2-3 weeks.
</p>

<p>
    Again, <strong>DO</strong> please test your applications on this RC, as we 
    would like to ensure that it remains backwards compatible, and that the 
    migration path is smooth.
</p>

EOC;
$post->setExtended($extended);

return $post;
