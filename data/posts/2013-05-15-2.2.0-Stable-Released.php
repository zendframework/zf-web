<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.0 Stable Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-05-15 10:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-05-15 10:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.0! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
            <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is the first <em>stable</em> release in the 2.2 series.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Usability and Consistency</h2>

<p>
    The primary focus of the 2.2 release has been usability and consistency, 
    primarily with regard to creation and configuration of services such as 
    hydrators, input filters, logs, DB connections, cache objects, translators, 
    and forms.
</p>

<p>
    Most of these services now have what are known as "Abstract Factories" that
    are either registered by default, or can be added quickly to your 
    application configuration. Abstract factories are used by the service
    manager when you have multiple services that follow the same instantiation
    pattern, but which have different names. The typical pattern the new 
    abstract factories follow is to use key/configuration pairs under a common
    top-level configuration key to describe the instances desired:
</p>

<pre class="highlight">
'log' => array(
    'Application\Log' => array(
        'writers' => array(
            array(
                'name'     => 'stream',
                'priority' => 1000,
                'options'  => array(
                    'stream' => 'data/logs/app.log',
                ),
            ),
        ),
    ),
),
</pre>

<p>
    The above creates a logger named "Application\Log" which you can retrieve 
    directly from the service manager. If you wanted to have additional loggers,
    you could do so by adding additional entries under the "log" heading, each
    named, and each providing configuration for a logger.
</p>

<p>
    Besides the logger abstract factory illustrated above, the following 
    components each have abstract factories now, too, using the configuration 
    keys noted:
</p>

<ul>
    <li><code>Zend\Cache</code>: "caches" configuration section, allowing multiple
        named cache storage objects.
    </li>

    <li><code>Zend\Db</code>: "adapters" subkey of the "db" configuration section;
        this abstract factory allows you to finally have multiple named DB adapter
        instances, effectively allowing for read-only and write-only connections.
    </li>

    <li><code>Zend\Form</code>: "forms" configuration section (which makes use 
        of several old and new plugin managers, as noted below).</li>
</ul>

<p>
    A number of new plugin managers were also added. Plugin managers are 
    specialized service manager instances used by objects that will be consuming
    many different related object instances, often based on runtime conditions.
    As examples, view helpers and controller plugins are mediated by plugin
    managers.
</p>

<p>
    The new plugin manager instances include:
</p>

<ul>
    <li><code>Zend\Stdlib\Hydrator\HydratorPluginManager</code>, for retrieving hydrator
        instances. This allows re-use of individual hydrators, and coupled with the 
        forms abstract factory, allows usage of custom hydrators across your form 
        instances.
    </li>

    <li><code>Zend\InputFilter\InputFilterPluginManager</code>, for retrieving
        (configurable) input filter instances. This allows re-use of input filters, as
        well as ensures that all input instances are provided with custom validators
        and/or filters (from the existing validator and filter plugin managers). The
        forms abstract factory makes use of this, which allows us to finally tie 
        together the various plugin managers to create fully configurable and custom
        forms.
    </li>
</ul>

<p>
    Finally, a couple new service factories were created. Service factories 
    usually have a 1:1 relationship between the named service and the instance
    provided, and are ideal for situations where you only need one instance of
    a given service type. In the case of the new factories for 2.2, these include
    <em>translators</em> and <em>sessions</em>.
</p>

<h2>Data Definition Language Abstraction</h2>

<p>
    Zend Framework 2.2 also offers initial support in <code>Zend\Db</code> 
    for dynamic DDL queries.  DDL, for Data Definition Language, is a subset of 
    SQL that comprises different commands for building RDBMS data 
    structures like tables, columns, constraints, indexes, views, triggers 
    and the like.
</p>

<p>
    Initial support is limited to creating tables with SQL92 data-types, and 
    some specialization for MySQL support.  Here is an example of <code>CREATE 
    TABLE</code> statement:
</p>

<pre class="highlight">
    use Zend\Db\Sql\Sql;
    use Zend\Db\Sql\Ddl;

    $t = new Ddl\CreateTable();
    $t->setTable('bar');
    $t->addColumn(new Ddl\Column\Integer('id', 12, true, null,
['auto_increment' => true, 'comment' => 'Some comment']));
    $t->addColumn(new Ddl\Column\Varchar('name', 255));
    $t->addColumn(new Ddl\Column\Char('foo', 20));
    $t->addConstraint(new Ddl\Constraint\PrimaryKey('id'));
    $t->addConstraint(new Ddl\Constraint\UniqueKey(['name', 'foo'],
'my_unique_key'));

    $sql = new Sql($adapter);
    echo $sql->getSqlStringForSqlObject($t);
</pre>

<p>Once this table is created, it can then be altered:</p>

<pre class="highlight">
    $t = new Ddl\AlterTable('bar');
    $t->changeColumn('name', new Ddl\Column\Varchar('new_name', 50));
    $t->addColumn(new Ddl\Column\Varchar('another', 255));
    $t->addColumn(new Ddl\Column\Varchar('other_id', 255));
    $t->dropColumn('foo');
    $t->addConstraint(new Ddl\Constraint\ForeignKey(
        'my_fk', 'other_id', 'other_table', 'id', 'CASCADE', 'CASCADE'
    ));
    $t->dropConstraint('my_index');
    echo $sql->getSqlStringForSqlObject($t);
</pre>

<p>Or even dropped:</p>

<pre class="highlight">
    $dt = new Ddl\DropTable('bar');
    echo $sql->getSqlStringForSqlObject($dt);
</pre>

<p>
    What can this be used for?
</p>

<p>
    That is where you come in.  This particular feature was asked for numerous 
    times during ZF1 development.  We'd like to see what kind of ZF2 modules 
    can be created with this base infrastructure.  Migration assistant?  ORM 
    database creation tool?  Advanced CMS?  Let us know; we'll be adding more 
    vendor specific support over the 2.2 to 2.3 timeline.
</p>

<h2>Hydrator Improvements</h2>

<p>
    As noted earlier, <code>Zend\Stdlib\Hydrator</code> now has a plugin manager
    you can compose into your objects for managing hydrator instances. However,
    beyond that, we also now have an "Aggregate Hydrator", which allows you to
    provide specialized mapping of your object types to hydrators via an event-based
    system.
</p>

<p>
    Why is this exciting? Many of our users utilize <a href="http://doctrine-project.org">Doctrine</a>
    as an Object Relational Mapping (ORM) system. Oftentimes, the entities that you
    work with will also form a hierarchical structure. The Aggregate Hydrator allows
    allows you to attach a single hydrator to the parent object, and ensure that all
    child and descendant objects are either hydrated or extracted according to their
    type.
</p>

<h2>Reducing Dependencies</h2>

<p>
    We have started work on a new story for the framework: reducing dependencies
    for individual components. We have received feedback from a number of 
    developers and organizations indicating that even though each component
    can be installed individually, the number of dependencies most components
    mark as required leads to a situation where they feel they must choose
    whether or not they adopt the framework, versus adopting just the component.
    While of course we'd like them to adopt the framework, we'd rather they
    get a taste for it, if you will.
</p>

<p>
    While this story is primarily slated for 2.3, we have made our first steps
    in 2.2, with the <code>Zend\Feed</code> and <code>Zend\Validator</code>
    components. 
</p>

<p>
    <code>Zend\Validator</code> removed its dependency on the i18n component.
    We achieved this by creating <a href="http://martinfowler.com/eaaCatalog/separatedInterface.html" target="_blank">Separated 
    Interfaces</a> for the translator. Considering translation was only enabled
    if you explicitly injected a translator, this was a natural course of action.
    (It also introduced a minor backwards compatibility break; see below for more
    information.)
</p>

<p>
    For <code>Zend\Feed</code>, many "required" dependencies were actually
    optional already, and we could mark them as such. There were two that were
    not, however, and which required similar treatment as <code>Zend\Validator</code>
    in creating separated interfaces: the service manager (used for extension
    management) and HTTP (for fetching remote feeds with the reader). Interfaces
    were developed for each of these, and <code>Zend\Feed</code> now has only
    two required dependencies. A nice side benefit is that you can now use
    third-party HTTP clients with <code>Zend\Feed\Reader</code>!
</p>

<h2>Migration Notes</h2>

<p>
    While we have worked hard to keep code backwards compatible (BC), there are a few
    noteworth changes that <em>may</em> affect your code.
</p>

<ul>
    <li><code>Zend\Validator</code> no longer directly consumes a <code>Zend\I18n\Translator\Translator</code> 
        instance; instead, you must either implement <code>Zend\Validator\Translator\TranslatorInterface</code>
        or use <code>Zend\Mvc\I18n\Translator</code>. In most cases, this change
        should be transparent, as validator instances managed by the 
        ValidatorPluginManager will already be using the correct instance.
    </li>

    <li>In 2.1.5, a BC break was accidently introduced into <code>Zend\Navigation</code> in
        order to enable a feature: MVC pages were altered to always use route match values when
        available when generating URIs. 2.2.0 was modified to add a flag to enable this
        behavior on demand, but defaults to the original behavior, which does not
        pass the route match values to the pages. If you relied on this behavior
        in 2.1.5, add the following option to your individual MVC page definitions:

        <pre class="highlight">
'use_route_match' => true,
        </pre>
    </li>
</ul>

<h2>Other Notable Improvements</h2>

<ul>
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
    Greater than 150 patches were applied for 2.2.0. 
</p>

<ul>
    <li><a href="/changelog/2.2.0">http://framework.zend.com/changelog/2.2.0</a></li>
</ul>

<h2>Other Announcements</h2>

<p>
    Over a month ago, we migrated <a href="https://github.com/zendframework/zf1">Zend 
    Framework 1 to GitHub</a>. At that time, we also migrated active issues created since
    1.12.0 to the <a href="https://github.com/zendframework/zf1/issues">GitHub issue tracker</a>,
    and marked our self-hosted issue tracker read-only. We have decided to turn off that issue
    tracker, but still retain the original issues at their original locations for purposes
    of history and transparency. You can find information on the change on our <a href="/issues">
    issues landing page</a>.
</p>

<h2>Thank You!</h2>

<p>
    Please join me in thanking everyone who provided new features and code 
    improvements for the 2.2.0 release! We had a huge leap forward in usability
    of many components, and a number of key new features that make developing
    applications simpler. We'll be continuing on these themes for the next
    release as well.
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases are scheduled for the third Wednesday of each month;
    expect 2.2.1 on 19 June 2013. Minor releases are scheduled roughly every 
    quarter; look for 2.3 sometime around mid-August or early September. 
    Proposals and ideas for stories will be presented on the zf-contributors
    mailing list; subscribe by sending an email to 
    zf-contributors-subscribe [at] lists.zend.com if you are interested in
    assisting with its development.
</p>

EOC;
$post->setExtended($extended);

return $post;
