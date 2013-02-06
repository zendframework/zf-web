<?php
$tags = array (
  '2.0.0' => 'Zend Framework 2.0.0
====================

This is the first stable release of the new version 2 release branch.

04 September 2012

NEW IN ZEND FRAMEWORK 2
-----------------------

New and/or refactored components include:

- EventManager - provides subject/observer, pubsub, aspect-oriented programming,
  signal slots, and event systems for your applications.
- ServiceManager - provides an Inversion of Control (IoC) container for managing
  object life-cycles and dependencies via a configurable and programmable
  interface.
- DI - provides a Dependency Injection Container, another form of IoC.
- MVC - a newly written MVC layer for ZF2, using controllers as services, and
  wiring everything together using events.
- ModuleManager - a solution for providing plug-and-play functionality for
  either MVC applications or any application requiring pluggable 3rd party code.
  Within ZF2, the ModuleManager provides the MVC with services, and assists in
  wiring events.
- Loader - provides new options for autoloading, including standard, performant
  PSR-0 loading and class-map-based autoloading.
- Code - provides support for code reflection, static code scanning, and
  annotation parsing.
- Config - more performant and more flexible configuration parsing and creation.
- Escaper - a new component for providing context-specific escaping solutions
  for HTML, HTML attributes, CSS, JavaScript, and combinations of contexts as
  well.
- HTTP - rewritten to provide better header, request, and response abstraction.
- I18n - a brand new internationalization and localization layer built on top of
  PHP\'s ext/intl extension.
- InputFilter - a new component for providing normalization and validation of
  sets of data.
- Form - rewritten from the ground up to properly separate validation, domain
  modeling, and presentation concerns. Allows binding objects to forms, defining
  forms and input filters via annotations, and more.
- Log - rewritten to be more flexible and provide better capabilities
  surrounding message formats and filtering.
- Mail - rewritten to separate concerns more cleanly between messages and
  transports.
- Session - rewritten to make testing easier, as well as to make it more
  configurable for end users.
- Uri - rewritten to provide a cleaner, more object oriented interface.

Many components have been ported from Zend Framework 1, and operate in
practically identical manners to their 1.X equivalent. Others, such as the
service components, have been moved to their own repositories to ensure that as
APIs change, they do not need to wait on the framework to release new versions.

Welcome to a new generation of Zend Framework!',
  '2.0.0beta4' => 'Zend Framework 2.0.0beta4

THIS RELEASE IS A DEVELOPMENT RELEASE AND NOT INTENDED FOR PRODUCTION USE.
PLEASE USE AT YOUR OWN RISK.

This is the fourth in a series of planned beta releases. The beta release
cycle will follow the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen _approximately_ every six weeks.

Once the established milestones have been reached and the featureset has reached
maturity and reasonable stability, we will freeze the API and prepare for
Release Candidate (RC) status. At this time, we are only planning for one more
beta release (beta5) before starting the RC process.

PLEASE NOTE: this beta includes a large number of breaks from the previous beta,
due to introduction of the ServiceManager, changes to the EventManager, renaming
of the ModuleManager, rewrite of the Form component (and removal of the Dojo
component), and several changes in the View layer. Please consult the
ZendSkeletonApplication to get an idea of the changes necessary to make your
application work with beta4.

 - Config component (Enrico Zimuel)
    -  Added reader and writer implementations for JSON and YAML configuration
 - Crypt and Math (Enrico Zimuel)
    - Creates a generic API for string and stream en/decryption
    - Provides bcrypt support
    - Provides BigInteger support
    - Provides common methodology surrounding credential encryption and hashing
 - Db layer (Ralph Schindler)
    - Zend\\Db\\Adapter: added buffer() to the ResultInterface, added that feature
      to Mysqli Result object
    - Zend\\Db\\Adapter: added ability to subselect Sqlite for returning a true
      count()
    - Zend\\Db\\Adapter: added API to return helper closures from the Adapter API
    - Renamed "database" to "schema" in all usages across Zend\\Db
    - Zend\\Db\\Adapter: Various fixes for PDO connection parameters
    - Zend\\Db\\Sql: created a shared AbstractSql implementation to share
      expression processing
    - Zend\\Db\\Sql: created a more robust "Expression" object for use in Select
      and Predicates
    - Zend\\Db\\Sql: created an internal workflow and architecture to handle the
      creation of platform specific queries
    - Zend\\Db\\Sql: implemented limit() and offset() API to Select
    - Zend\\Db\\Sql: added having(), order() to SELECT API
    - Zend\\Db\\Sql: added alias support to Select::columns()
    - Zend\\Db\\TableGateway: reorganized AbstractTableGateway and TableGateway,
      removed other extensions in favor of "Features"
    - Zend\\Db\\TableGateway: created a "Features" API in TableGatway to promote
      horizontal extension of TableGatway
 - Di (Ralph Schindler, Marco Pivetta)
    - Added method injectDependencies($instance), to allow injecting an object
      after an instance is already available (used in the ServiceManager)
    - Various fixes based on issue reports
 - Dojo
    - REMOVED. Support was for out-dated versions of Dojo, and with the new Form
      rewrite, it needs to be completely rewritten. This is targetted for post
      2.0.0 at this time.
 - EventManager (Matthew Weier O\'Phinney)
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
 - Form (Matthew Weier O\'Phinney, Kyle Spraggs, Guilherme Blanco)
    - Complete rewrite
    - Elements compose a name and attributes
    - Fieldsets compose a name, attributes, and elements and fieldsets
    - Forms compose a name, attributes, elements, fieldsets, an InputFilter, and
      optionally a Hydrator and bound object.
    - New form view helpers accept the Form objects in order to generate markup.
    - Object binding allows direct binding of model data to and from the Form.
 - InputFilter (Matthew Weier O\'Phinney)
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
 - ModuleManager (Evan Coury, Matthew Weier O\'Phinney)
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
 - MVC (Matthew Weier O\'Phinney, Ralph Schindler, Evan Coury)
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
 - ServiceManager component (Ralph Schindler, Matthew Weier O\'Phinney)
    - Highly performant, programmatic service creation
    - Largely replaces DI, but can also consume Zend\\Di
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

Over *400* pull requests for a variety of features and bugfixes were handled
since beta3!',
  '2.0.6' => 'Zend Framework 2.0.6

19 Dec 2012

- 2885: Zend\\Db\\TableGateway\\AbstractTableGateway won\'t work with Sqlsrv
  db adapter (https://github.com/zendframework/zf2/issues/2885)
- 2922: Fix #2902 (https://github.com/zendframework/zf2/issues/2922)
- 2961: Revert PR #2943 for 5.3.3 fix
  (https://github.com/zendframework/zf2/issues/2961)
- 2962: Allow Accept-Encoding header to be set explicitly by http
  request (https://github.com/zendframework/zf2/issues/2962)
- 3033: Fix error checking on Zend\\Http\\Client\\Adapter\\Socket->write().
  (https://github.com/zendframework/zf2/issues/3033)
- 3040: remove unused \'use DOMXPath\' and property $count and $xpath
  (https://github.com/zendframework/zf2/issues/3040)
- 3043: improve conditional : reduce file size
  (https://github.com/zendframework/zf2/issues/3043)
- 3044: Extending Zend\\Mvc\\Router\\Http\\Segment causes error
  (https://github.com/zendframework/zf2/issues/3044)
- 3047: Fix Zend\\Console\\Getopt::getUsageMessage()
  (https://github.com/zendframework/zf2/issues/3047)
- 3049: Hotfix/issue #3033
  (https://github.com/zendframework/zf2/issues/3049)
- 3050: Fix : The annotation @\\Zend\\Form\\Annotation\\AllowEmpty declared
  on does not accept any values
  (https://github.com/zendframework/zf2/issues/3050)
- 3052: Fixed #3051 (https://github.com/zendframework/zf2/issues/3052)
- 3061: changed it back \'consist\' => the \'must\' should be applied to all
  parts of the sentence
  (https://github.com/zendframework/zf2/issues/3061)
- 3063: hotfix: change sha382 to sha384 in
  Zend\\Crypt\\Key\\Derivation\\SaltedS2k
  (https://github.com/zendframework/zf2/issues/3063)
- 3070: Fix default value unavailable exception for in-build php classes
  (https://github.com/zendframework/zf2/issues/3070)
- 3074: Hotfix/issue #2451 (https://github.com/zendframework/zf2/issues/3074)
- 3091: console exception strategy displays previous exception message
  (https://github.com/zendframework/zf2/issues/3091)
- 3114: Fixed Client to allow also empty passwords in HTTP
  Authentication. (https://github.com/zendframework/zf2/issues/3114)
- 3125: #2607 - Fixing how headers are accessed
  (https://github.com/zendframework/zf2/issues/3125)
- 3126: Fix for GitHub issue 2605
  (https://github.com/zendframework/zf2/issues/3126)
- 3127: fix cs: add space after casting
  (https://github.com/zendframework/zf2/issues/3127)
- 3130: Obey PSR-2 (https://github.com/zendframework/zf2/issues/3130)
- 3144: Zend\\Form\\View\\Helper\\Captcha\\AbstractWord input and hidden
  attributes (https://github.com/zendframework/zf2/issues/3144)
- 3148: Fixing obsolete method of checking headers, made it use the new
  method. (https://github.com/zendframework/zf2/issues/3148)
- 3149: Zf2634 - Adding missing method Client::encodeAuthHeader
  (https://github.com/zendframework/zf2/issues/3149)
- 3151: Rename variable to what it probably should be
  (https://github.com/zendframework/zf2/issues/3151)
- 3155: strip duplicated semicolon
  (https://github.com/zendframework/zf2/issues/3155)
- 3156: fix typos in docblocks
  (https://github.com/zendframework/zf2/issues/3156)
- 3162: Allow Forms to have an InputFilterSpecification
  (https://github.com/zendframework/zf2/issues/3162)
- 3163: Added support of driver\\_options to Mysqli DB Driver
  (https://github.com/zendframework/zf2/issues/3163)
- 3164: Cast $step to float in \\Zend\\Validator\\Step
  (https://github.com/zendframework/zf2/issues/3164)
- 3166: [#2678] Sqlsrv driver incorrectly throwing exception when
  $sqlOrResource... (https://github.com/zendframework/zf2/issues/3166)
- 3167: Fix #3161 by checking if the server port already exists in the
  host (https://github.com/zendframework/zf2/issues/3167)
- 3169: Fixing issue #3036 (https://github.com/zendframework/zf2/issues/3169)
- 3170: Fixing issue #2554 (https://github.com/zendframework/zf2/issues/3170)
- 3171: hotfix : add  \'$argName\' as \'argument %s\' in sprintf ( at 1st
  parameter ) (https://github.com/zendframework/zf2/issues/3171)
- 3178: Maintain priority flag when cloning a Fieldset
  (https://github.com/zendframework/zf2/issues/3178)
- 3184: fix misspelled getCacheStorge()
  (https://github.com/zendframework/zf2/issues/3184)
- 3186: Dispatching to a good controller but wrong action triggers a
  Fatal Error (https://github.com/zendframework/zf2/issues/3186)
- 3187: Fixing ansiColorMap by removing extra m\'s showed in the console
  (https://github.com/zendframework/zf2/issues/3187)
- 3194: Write clean new line for writeLine method (no background color)
  (https://github.com/zendframework/zf2/issues/3194)
- 3197: Fix spelling error (https://github.com/zendframework/zf2/issues/3197)
- 3201: Session storage set save path
  (https://github.com/zendframework/zf2/issues/3201)
- 3204: [wip] Zend\\Http\\Client makes 2 requests to url if
  setStream(true) is called
  (https://github.com/zendframework/zf2/issues/3204)
- 3207: dead code clean up.
  (https://github.com/zendframework/zf2/issues/3207)
- 3208: Zend\\Mime\\Part: Added EOL paramter to getEncodedStream()
  (https://github.com/zendframework/zf2/issues/3208)
- 3213: [#3173] Incorrect creating instance
  Zend/Code/Generator/ClassGenerator.php by fromArray
  (https://github.com/zendframework/zf2/issues/3213)
- 3214: Fix passing of tags to constructor of docblock generator class
  (https://github.com/zendframework/zf2/issues/3214)
- 3217: Cache: Optimized Filesystem::setItem with locking enabled by
  writing the... (https://github.com/zendframework/zf2/issues/3217)
- 3220: [2.0] Log Writer support for MongoClient driver class
  (https://github.com/zendframework/zf2/issues/3220)
- 3226: Licence is not accessable via web
  (https://github.com/zendframework/zf2/issues/3226)
- 3229: fixed bug in DefinitionList::hasMethod()
  (https://github.com/zendframework/zf2/issues/3229)
- 3234: Removed old Form TODO since all items are complete
  (https://github.com/zendframework/zf2/issues/3234)
- 3236: Issue #3222 - Added suport for multi-level nested ini config
  variables (https://github.com/zendframework/zf2/issues/3236)
- 3237: [BUG] Service Manager Not Shared Duplicate new Instance with
  multiple Abstract Factories
  (https://github.com/zendframework/zf2/issues/3237)
- 3238: Added French translation for captcha
  (https://github.com/zendframework/zf2/issues/3238)
- 3250: Issue #2912 - Fix for LicenseTag generation
  (https://github.com/zendframework/zf2/issues/3250)
- 3252: subject prepend text in options for Log\\Writer\\Mail
  (https://github.com/zendframework/zf2/issues/3252)
- 3254: Better capabilities surrounding console notFoundAction
  (https://github.com/zendframework/zf2/issues/3254)',
  '2.0.0rc5' => 'Zend Framework 2.0.0rc5

This is the fifth release candidate for 2.0.0. We will be releasing RCs
on a weekly basis until we feel all critical issues are addressed. At
this time, we anticipate few API changes before the stable release, and
recommend testing your production applications against it.

23 August 2012

- Zend\\Db
  - Now handles null values properly in execute mode.
  - Added SqlSrv integration tests (prompted by a bug report of issues with
    establishing a connection).
- Zend\\Form
  - The FormButton helper now allows translation. However, to make this work, it
    now requires that the label value is set in the element.
  - Fixed an issue with the MultiCheckBox helper to ensure checked/unchecked
    values are properly populated.
  - The FormCheckbox view helper now requires that the element is a Checkbox
    element; this is done to ensure the required options are available.
- Zend\\Log
  - The table name constructor option is now optional, allowing you to pass it
    in a configuration array.
  - Now allows using DateTime with the Db Writer.
- Zend\\Http
  - Added ability to set the SSL capath option.
  - Added a check for the sslcapath being set if sslverifypeer is enabled when
    first connecting to an SSL-enabled site; an exception is thrown if all
    conditions are not met.
  - PhpEnvironment\\Request object now falls back to \'/\' for the base path if the
    SCRIPT_FILENAME server variable is not present (true in PHP 5.4 web server
    and several others).
- Zend\\I18n
  - Caching translations now works.
- Zend\\Mvc
  - forward() plugin no longer results in double template rendering.
- Zend\\ServiceManager
  - Now ensures that when $allowOverride is enabled that services registered
    with the same name overwrite properly.
- Zend\\Validator
  - The Uri validator is now listed in the validator plugin manager.
  - The EmailAddress validator now allows setting custom error messages.
- General fixes
  - Removed all locations of error suppression remaining in the framework.
  - Synced the implementations of Zend\\Mvc\\Controller\\Plugin\\Url and
    Zend\\View\\Helper\\Url.

More than 20 pull requests for a variety of features and bugfixes were handled
since RC4, as well as around 30 documentation changes!',
  '2.1.0' => 'Zend Framework 2.1.0

- 2378: ZF2-417 Form Annotation Hydrator options support
  (https://github.com/zendframework/zf2/issues/2378)
- 2390: Expose formally protected method in ConfigListener
  (https://github.com/zendframework/zf2/issues/2390)
- 2405: [WIP] Feature/accepted model controller plugin
  (https://github.com/zendframework/zf2/issues/2405)
- 2424: Decorator plugin manager was pointing to an inexistent class
  (https://github.com/zendframework/zf2/issues/2424)
- 2428: Form develop/allow date time
  (https://github.com/zendframework/zf2/issues/2428)
- 2430: [2.1] Added the scrypt key derivation algorithm in Zend\\Crypt
  (https://github.com/zendframework/zf2/issues/2430)
- 2439: [2.1] Form File Upload refactor
  (https://github.com/zendframework/zf2/issues/2439)
- 2486: The Upload validator might be broken
  (https://github.com/zendframework/zf2/issues/2486)
- 2506: Throwing exception in template (and/or layout) doesnt fails gracefully
  (https://github.com/zendframework/zf2/issues/2506)
- 2524: Throws exception when trying to generate bcrypt
  (https://github.com/zendframework/zf2/issues/2524)
- 2537: Create a NotIn predicate
  (https://github.com/zendframework/zf2/issues/2537)
- 2616: Initial ZF2 RBAC Component
  (https://github.com/zendframework/zf2/issues/2616)
- 2629: JsonStrategy should set response charset
  (https://github.com/zendframework/zf2/issues/2629)
- 2647: Fix/bcrypt: added the set/get BackwardCompatibility
  (https://github.com/zendframework/zf2/issues/2647)
- 2668: Implement XCache storage adapter (fixes #2581)
  (https://github.com/zendframework/zf2/issues/2668)
- 2671: Added fluent inteface to prepend and set method. Zend\\View\\Container\\AbstractContainer
  (https://github.com/zendframework/zf2/issues/2671)
- 2725: Feature/logger factory
  (https://github.com/zendframework/zf2/issues/2725)
- 2726: Zend\\Validator\\Explode does not handle NULL
  (https://github.com/zendframework/zf2/issues/2726)
- 2727: Added ability to add additional information to the logs via processors.
  (https://github.com/zendframework/zf2/issues/2727)
- 2772: Adding cookie route. Going to open PR for comments.
  (https://github.com/zendframework/zf2/issues/2772)
- 2815: Fix fro GitHub issue 2600 (Cannot check if a table is read only)
  (https://github.com/zendframework/zf2/issues/2815)
- 2819: Support for ListenerAggregates in SharedEventManager
  (https://github.com/zendframework/zf2/issues/2819)
- 2820: Form plugin manager
  (https://github.com/zendframework/zf2/issues/2820)
- 2863: Handle postgres sequences
  (https://github.com/zendframework/zf2/issues/2863)
- 2876: memcached changes
  (https://github.com/zendframework/zf2/issues/2876)
- 2884: Allow select object to pass on select->join
  (https://github.com/zendframework/zf2/issues/2884)
- 2888: Bugfix dateformat helper
  (https://github.com/zendframework/zf2/issues/2888)
- 2918: \\Zend\\Mime\\Mime::LINEEND causes problems with some SMTP-Servers
  (https://github.com/zendframework/zf2/issues/2918)
- 2945: SOAP 1.2 support for WSDL generation
  (https://github.com/zendframework/zf2/issues/2945)
- 2947: Add DateTimeSelect element to form
  (https://github.com/zendframework/zf2/issues/2947)
- 2950: Abstract row gatewayset from array
  (https://github.com/zendframework/zf2/issues/2950)
- 2968: Zend\\Feed\\Reader\\Extension\\Atom\\Entry::getAuthors and Feed::getAuthors
  should return Collection\\Author
  (https://github.com/zendframework/zf2/issues/2968)
- 2973: Zend\\Db\\Sql : Create NotIn predicate
  (https://github.com/zendframework/zf2/issues/2973)
- 2977: Method signature of merge() in Zend\\Config\\Config prevents mocking
  (https://github.com/zendframework/zf2/issues/2977)
- 2988: Cache: Added storage adapter using a session container
  (https://github.com/zendframework/zf2/issues/2988)
- 2990: Added note of new xcache storage adapter
  (https://github.com/zendframework/zf2/issues/2990)
- 3010: [2.1][File Uploads] Multi-File input filtering and FilePRG plugin update
  (https://github.com/zendframework/zf2/issues/3010)
- 3011: Response Json Client
  (https://github.com/zendframework/zf2/issues/3011)
- 3016: [develop] PRG Plugin fixes: Incorrect use of session hops expiration
  (https://github.com/zendframework/zf2/issues/3016)
- 3019: [2.1][develop] PRG Plugins fix
  (https://github.com/zendframework/zf2/issues/3019)
- 3055: Zend Validators complain of array to string conversion for nested array
  values that do not pass validation when using E\\_NOTICE
  (https://github.com/zendframework/zf2/issues/3055)
- 3058: [2.1][File Upload] Session Progress fixes
  (https://github.com/zendframework/zf2/issues/3058)
- 3059: [2.1] Add reference to ChromePhp LoggerWriter in WriterPluginManager
  (https://github.com/zendframework/zf2/issues/3059)
- 3069: Hotfix/xcache empty namespace
  (https://github.com/zendframework/zf2/issues/3069)
- 3073: Documentation and code  mismatch
  (https://github.com/zendframework/zf2/issues/3073)
- 3084: Basic support for aggregates in SharedEventManager according to feedback...
  (https://github.com/zendframework/zf2/issues/3084)
- 3086: Updated constructors to accept options array according to AbstractWriter...
  (https://github.com/zendframework/zf2/issues/3086)
- 3088: Zend\\Permissions\\Rbac roles should inherit parent permissions, not child
  permissions
  (https://github.com/zendframework/zf2/issues/3088)
- 3093: Feature/cookies refactor
  (https://github.com/zendframework/zf2/issues/3093)
- 3105: RFC Send Response Workflow
  (https://github.com/zendframework/zf2/issues/3105)
- 3110: Stdlib\\StringUtils
  (https://github.com/zendframework/zf2/issues/3110)
- 3140: Tests for Zend\\Cache\\Storage\\Adapter\\MemcachedResourceManager
  (https://github.com/zendframework/zf2/issues/3140)
- 3195: Date element formats not respected in validators.
  (https://github.com/zendframework/zf2/issues/3195)
- 3199: [2.1][FileUploads] FileInput AJAX Post fix
  (https://github.com/zendframework/zf2/issues/3199)
- 3212: Cache: Now an empty namespace means disabling namespace support
  (https://github.com/zendframework/zf2/issues/3212)
- 3215: Check $exception type before throw
  (https://github.com/zendframework/zf2/issues/3215)
- 3219: Fix hook in plugin manager
  (https://github.com/zendframework/zf2/issues/3219)
- 3224: Zend\\Db\\Sql\\Select::getSqlString(Zend\\Db\\Adapter\\Platform\\Mysql) doesn\'t
  work properly with limit param
  (https://github.com/zendframework/zf2/issues/3224)
- 3243: [2.1] Added the support of Apache\'s passwords
  (https://github.com/zendframework/zf2/issues/3243)
- 3246: [2.1][File Upload] Change file upload filtering to preserve the $\\_FILES
  array
  (https://github.com/zendframework/zf2/issues/3246)
- 3247: Fix zend test with the new sendresponselistener
  (https://github.com/zendframework/zf2/issues/3247)
- 3257: Support nested error handler
  (https://github.com/zendframework/zf2/issues/3257)
- 3259: [2.1][File Upload] RenameUpload filter rewrite w/option to use uploaded
  \'name\'
  (https://github.com/zendframework/zf2/issues/3259)
- 3263: correcting ConsoleResponseSender\'s __invoke
  (https://github.com/zendframework/zf2/issues/3263)
- 3276: DateElement now support a string
  (https://github.com/zendframework/zf2/issues/3276)
- 3283: fix Undefined function DocBlockReflection::factory error
  (https://github.com/zendframework/zf2/issues/3283)
- 3287: Added missing constructor parameter
  (https://github.com/zendframework/zf2/issues/3287)
- 3308: Update library/Zend/Validator/File/MimeType.php
  (https://github.com/zendframework/zf2/issues/3308)
- 3314: add ContentTransferEncoding Headers
  (https://github.com/zendframework/zf2/issues/3314)
- 3316: Update library/Zend/Mvc/ResponseSender/ConsoleResponseSender.php
  (https://github.com/zendframework/zf2/issues/3316)
- 3334: [2.1][develop] Added missing Exception namespace to Sha1 validator
  (https://github.com/zendframework/zf2/issues/3334)
- 3339: Xterm\'s 256 colors integration for Console.
  (https://github.com/zendframework/zf2/issues/3339)
- 3343: add SimpleStreamResponseSender + Tests
  (https://github.com/zendframework/zf2/issues/3343)
- 3349: Provide support for more HTTP methods in the AbstractRestfulController
  (https://github.com/zendframework/zf2/issues/3349)
- 3350: Add little more fun to console
  (https://github.com/zendframework/zf2/issues/3350)
- 3357: Add default prototype tags in reflection
  (https://github.com/zendframework/zf2/issues/3357)
- 3359: Added filter possibility
  (https://github.com/zendframework/zf2/issues/3359)
- 3363: Fix minor doc block errors
  (https://github.com/zendframework/zf2/issues/3363)
- 3365: Fix trailing spaces CS error causing all travis builds to fail
  (https://github.com/zendframework/zf2/issues/3365)
- 3366: Zend\\Log\\Logger::registerErrorHandler() should accept a parameter to set
  the return value of the error_handler callback
  (https://github.com/zendframework/zf2/issues/3366)
- 3370: [2.1] File PRG plugin issue when merging POST data with nested keys
  (https://github.com/zendframework/zf2/issues/3370)
- 3376: Remove use of deprecated /e-modifier of preg_replace
  (https://github.com/zendframework/zf2/issues/3376)
- 3377: removed test failing since PHP>=5.4
  (https://github.com/zendframework/zf2/issues/3377)
- 3378: Improve code generators consistency
  (https://github.com/zendframework/zf2/issues/3378)
- 3385: render view one last time in case exception thrown from inside view
  (https://github.com/zendframework/zf2/issues/3385)
- 3389: FileExtension validor error in Form context
  (https://github.com/zendframework/zf2/issues/3389)
- 3392: Development branch of AbstractRestfulController->processPostData()
  doesn\'t handle Content-Type application/x-www-form-urlencoded correctly
  (https://github.com/zendframework/zf2/issues/3392)
- 3404: Provide default $_SESSION array superglobal proxy storage adapter
  (https://github.com/zendframework/zf2/issues/3404)
- 3405: fix dispatcher to catch legitimate exceptions
  (https://github.com/zendframework/zf2/issues/3405)
- 3414: Zend\\Mvc\\Controller\\AbstractRestfulController: various fixes to Json
  handling
  (https://github.com/zendframework/zf2/issues/3414)
- 3418: [2.1] Additional code comments for FileInput
  (https://github.com/zendframework/zf2/issues/3418)
- 3420: Authentication Validator
  (https://github.com/zendframework/zf2/issues/3420)
- 3421: Allow to set arbitrary status code for Exception strategy
  (https://github.com/zendframework/zf2/issues/3421)
- 3426: Zend\\Form\\View\\Helper\\FormSelect
  (https://github.com/zendframework/zf2/issues/3426)
- 3427: `Zend\\ModuleManager\\Feature\\ProvidesDependencyModulesInterface`
  (https://github.com/zendframework/zf2/issues/3427)
- 3440: [#3376] Better fix
  (https://github.com/zendframework/zf2/issues/3440)
- 3442: Better content-type negotiation
  (https://github.com/zendframework/zf2/issues/3442)
- 3446: Zend\\Form\\Captcha setOptions don\'t follow interface contract
  (https://github.com/zendframework/zf2/issues/3446)
- 3450: [Session][Auth] Since the recent BC changes to Sessions,
  Zend\\Authentication\\Storage\\Session does not work
  (https://github.com/zendframework/zf2/issues/3450)
- 3454: ACL permissions are not correctly inherited.
  (https://github.com/zendframework/zf2/issues/3454)
- 3458: Session data is empty in Session SaveHandler\'s write function
  (https://github.com/zendframework/zf2/issues/3458)
- 3461: fix for zendframework/zf2#3458
  (https://github.com/zendframework/zf2/issues/3461)
- 3470: Session not working in current development?
  (https://github.com/zendframework/zf2/issues/3470)
- 3479: Fixed #3454.
  (https://github.com/zendframework/zf2/issues/3479)
- 3482: Feature/rest delete replace collection
  (https://github.com/zendframework/zf2/issues/3482)
- 3483: [#2629] Add charset to Content-Type header
  (https://github.com/zendframework/zf2/issues/3483)
- 3485: Zend\\Db Oracle Driver
  (https://github.com/zendframework/zf2/issues/3485)
- 3491: Update library/Zend/Code/Generator/PropertyGenerator.php
  (https://github.com/zendframework/zf2/issues/3491)
- 3493: [Log] fixes #3366: Now Logger::registerErrorHandler() accepts continue
  (https://github.com/zendframework/zf2/issues/3493)
- 3494: [2.1] Zend\\Filter\\Word\\* no longer extends Zend\\Filter\\PregReplace
  (https://github.com/zendframework/zf2/issues/3494)
- 3495: [2.1] Added Zend\\Stdlib\\StringUtils::hasPcreUnicodeSupport()
  (https://github.com/zendframework/zf2/issues/3495)
- 3496: [2.1] fixed tons of missing/wrong use statements
  (https://github.com/zendframework/zf2/issues/3496)
- 3498: add method to Zend\\Http\\Response\\Stream
  (https://github.com/zendframework/zf2/issues/3498)
- 3499: removed "self" typehints in Zend\\Config and Zend\\Mvc
  (https://github.com/zendframework/zf2/issues/3499)
- 3501: Exception while createing RuntimeException in Pdo Connection class
  (https://github.com/zendframework/zf2/issues/3501)
- 3507: hasAcl dosn\'t cheks $defaultAcl Member Variable
  (https://github.com/zendframework/zf2/issues/3507)
- 3508: Removed all @category, @package, and @subpackage annotations
  (https://github.com/zendframework/zf2/issues/3508)
- 3509: Zend\\Form\\View\\Helper\\FormSelect
  (https://github.com/zendframework/zf2/issues/3509)
- 3510: FilePRG: replace array_merge with ArrayUtils::merge
  (https://github.com/zendframework/zf2/issues/3510)
- 3511: Revert PR #3088 as discussed in #3265.
  (https://github.com/zendframework/zf2/issues/3511)
- 3519: Allow to pull route manager from sl
  (https://github.com/zendframework/zf2/issues/3519)
- 3523: Components dependent on Zend\\Stdlib but it\'s not marked in composer.json
  (https://github.com/zendframework/zf2/issues/3523)
- 3531: [2.1] Fix variable Name and Resource on Oracle Driver Test
  (https://github.com/zendframework/zf2/issues/3531)
- 3532: Add legend translation support into formCollection view helper
  (https://github.com/zendframework/zf2/issues/3532)
- 3538: ElementPrepareAwareInterface should use FormInterface
  (https://github.com/zendframework/zf2/issues/3538)
- 3541: \\Zend\\Filter\\Encrypt and \\Zend\\Filter\\Decrypt not working together?
  (https://github.com/zendframework/zf2/issues/3541)
- 3543: Hotfix: Undeprecate PhpEnvironement Response methods
  (https://github.com/zendframework/zf2/issues/3543)
- 3545: Removing service initializer as of zendframework/zf2#3537
  (https://github.com/zendframework/zf2/issues/3545)
- 3546: Add RoleInterface
  (https://github.com/zendframework/zf2/issues/3546)
- 3555: [2.1] [Forms] [Bug] Factory instantiates Elements directly but should be
  using the FormElementManager
  (https://github.com/zendframework/zf2/issues/3555)
- 3556: fix for zendframework/zf2#3555
  (https://github.com/zendframework/zf2/issues/3556)
- 3557: [2.1] Fixes for FilePRG when using nested form elements
  (https://github.com/zendframework/zf2/issues/3557)
- 3559: Feature/translate flash message
  (https://github.com/zendframework/zf2/issues/3559)
- 3561: Zend\\Mail SMTP Fix Connection Handling
  (https://github.com/zendframework/zf2/issues/3561)
- 3566: Flash Messenger fixes for PHP < 5.4, and fix for default namespacing
  (https://github.com/zendframework/zf2/issues/3566)
- 3567: Zend\\Db: Adapter construction features + IbmDb2 & Oracle Platform
  features
  (https://github.com/zendframework/zf2/issues/3567)
- 3572: Allow to add serializers through config
  (https://github.com/zendframework/zf2/issues/3572)
- 3576: BC Break in Controller Loader, controllers no more present in controller
  loader.
  (https://github.com/zendframework/zf2/issues/3576)
- 3583: [2.1] Fixed an issue on salt check in Apache Password
  (https://github.com/zendframework/zf2/issues/3583)
- 3584: Zend\\Db Fix for #3290
  (https://github.com/zendframework/zf2/issues/3584)
- 3585: [2.1] Added the Apache htpasswd support for HTTP Authentication
  (https://github.com/zendframework/zf2/issues/3585)
- 3586: Zend\\Db Fix for #2563
  (https://github.com/zendframework/zf2/issues/3586)
- 3587: Zend\\Db Fix/Feature for #3294
  (https://github.com/zendframework/zf2/issues/3587)
- 3597: Zend\\Db\\TableGateway hotfix for MasterSlaveFeature
  (https://github.com/zendframework/zf2/issues/3597)
- 3598: Feature Zend\\Db\\Adapter\\Profiler
  (https://github.com/zendframework/zf2/issues/3598)
- 3599: [WIP] Zend\\Db\\Sql Literal Objects
  (https://github.com/zendframework/zf2/issues/3599)
- 3600: Fixed the unit test for Zend\\Filter\\File\\Encrypt and Decrypt
  (https://github.com/zendframework/zf2/issues/3600)
- 3605: Restore Zend\\File\\Transfer
  (https://github.com/zendframework/zf2/issues/3605)
- 3606: Zend\\Db\\Sql\\Select Add Support For SubSelect in Join Table - #2881 &
  #2884
  (https://github.com/zendframework/zf2/issues/3606)',
  '2.0.1' => 'This is the first maintenance release for the 2.0 series.

20 Sep 2012

- 2285: Seed RouteMatch params as long as params is set. This permits setting an
  empty array. (https://github.com/zendframework/zf2/pull/2285)
- 2286: prepareNotFoundViewModel listner -  eventResult as ViewModel if set
  (https://github.com/zendframework/zf2/pull/2286)
- 2290: <span>$label</span> only when filled
  (https://github.com/zendframework/zf2/pull/2290)
- 2292: Allow (int)0 in coomments count in entry feed
  (https://github.com/zendframework/zf2/pull/2292)
- 2295: force to check className parameters
  (https://github.com/zendframework/zf2/pull/2295)
- 2296: mini-fix in controller plugin manager
  (https://github.com/zendframework/zf2/pull/2296)
- 2297: fixed phpdoc in Zend\\Mvc\\ApplicationInterface
  (https://github.com/zendframework/zf2/pull/2297)
- 2298: Update to Date element use statements to make it clearer which DateTime
  (https://github.com/zendframework/zf2/pull/2298)
- 2300: FormRow translate label fix (#ZF2-516)
  (https://github.com/zendframework/zf2/pull/2300)
- 2302: Notifications now to #zftalk.dev
  (https://github.com/zendframework/zf2/pull/2302)
- 2306: Fix several cs (https://github.com/zendframework/zf2/pull/2306)
- 2307: Removed comment about non existent Zend_Tool
  (https://github.com/zendframework/zf2/pull/2307)
- 2308: Fix pluginmanager get method error
  (https://github.com/zendframework/zf2/pull/2308)
- 2309: Add consistency with event name
  (https://github.com/zendframework/zf2/pull/2309)
- 2310: Update library/Zend/Db/Sql/Select.php
  (https://github.com/zendframework/zf2/pull/2310)
- 2311: Version update (https://github.com/zendframework/zf2/pull/2311)
- 2312: Validator Translations (https://github.com/zendframework/zf2/pull/2312)
- 2313: ZF2-336: Zend\\Form adds enctype attribute as multipart/form-data
  (https://github.com/zendframework/zf2/pull/2313)
- 2317: Make Fieldset constructor consistent with parent Element class
  (https://github.com/zendframework/zf2/pull/2317)
- 2321: ZF2-534 Zend\\Log\\Writer\\Syslog prevents setting application name
  (https://github.com/zendframework/zf2/pull/2321)
- 2322: Jump to cache-storing instead of returning
  (https://github.com/zendframework/zf2/pull/2322)
- 2323: Conditional statements improved(minor changes).
  (https://github.com/zendframework/zf2/pull/2323)
- 2324: Fix for ZF2-517: Zend\\Mail\\Header\\GenericHeader fails to parse empty
  header (https://github.com/zendframework/zf2/pull/2324)
- 2328: Wrong \\__clone method (https://github.com/zendframework/zf2/pull/2328)
- 2331: added validation support for optgroups
  (https://github.com/zendframework/zf2/pull/2331)
- 2332: README-GIT update with optional pre-commit hook
  (https://github.com/zendframework/zf2/pull/2332)
- 2334: Mail\\Message::getSubject() should return value the way it was set
  (https://github.com/zendframework/zf2/pull/2334)
- 2335: ZF2-511 Updated refactored names and other fixes
  (https://github.com/zendframework/zf2/pull/2335)
- 2336: ZF-546 Remove duplicate check for time
  (https://github.com/zendframework/zf2/pull/2336)
- 2337: ZF2-539 Input type of image should not have attribute value
  (https://github.com/zendframework/zf2/pull/2337)
- 2338: ZF2-543: removed linked but not implemented cache adapters
  (https://github.com/zendframework/zf2/pull/2338)
- 2341: Updated Zend_Validate.php pt_BR translation to 25.Jul.2011 EN Revision
  (https://github.com/zendframework/zf2/pull/2341)
- 2342: ZF2-549 Zend\\Log\\Formatter\\ErrorHandler does not handle complex events
  (https://github.com/zendframework/zf2/pull/2342)
- 2346: updated Page\\Mvc::isActive to check if the controller param was
  tinkered (https://github.com/zendframework/zf2/pull/2346)
- 2349: Zend\\Feed Added unittests for more code coverage
  (https://github.com/zendframework/zf2/pull/2349)
- 2350: Bug in Zend\\ModuleManager\\Listener\\LocatorRegistrationListener
  (https://github.com/zendframework/zf2/pull/2350)
- 2351: ModuleManagerInterface is never used
  (https://github.com/zendframework/zf2/pull/2351)
- 2352: Hotfix for AbstractDb and Csrf Validators
  (https://github.com/zendframework/zf2/pull/2352)
- 2354: Update library/Zend/Feed/Writer/AbstractFeed.php
  (https://github.com/zendframework/zf2/pull/2354)
- 2355: Allow setting CsrfValidatorOptions in constructor
  (https://github.com/zendframework/zf2/pull/2355)
- 2356: Update library/Zend/Http/Cookies.php
  (https://github.com/zendframework/zf2/pull/2356)
- 2357: Update library/Zend/Barcode/Object/AbstractObject.php
  (https://github.com/zendframework/zf2/pull/2357)
- 2358: Update library/Zend/ServiceManager/AbstractPluginManager.php
  (https://github.com/zendframework/zf2/pull/2358)
- 2359: Update library/Zend/Server/Method/Parameter.php
  (https://github.com/zendframework/zf2/pull/2359)
- 2361: Zend\\Form Added extra unit tests and some code improvements
  (https://github.com/zendframework/zf2/pull/2361)
- 2364: Remove unused use statements
  (https://github.com/zendframework/zf2/pull/2364)
- 2365: Resolve undefined classes and constants
  (https://github.com/zendframework/zf2/pull/2365)
- 2366: fixed typo in Zend\\View\\HelperPluginManager
  (https://github.com/zendframework/zf2/pull/2366)
- 2370: Error handling in AbstractWriter::write using Zend\\Stdlib\\ErrorHandler
  (https://github.com/zendframework/zf2/pull/2370)
- 2372: Update library/Zend/ServiceManager/Config.php
  (https://github.com/zendframework/zf2/pull/2372)
- 2375: zend-inputfilter already requires
  (https://github.com/zendframework/zf2/pull/2375)
- 2376: Activate the new GitHub feature: Contributing Guidelines
  (https://github.com/zendframework/zf2/pull/2376)
- 2377: Update library/Zend/Mvc/Controller/AbstractController.php
  (https://github.com/zendframework/zf2/pull/2377)
- 2379: Typo in property name in Zend/Db/Metadata/Object/AbstractTableObject.php
  (https://github.com/zendframework/zf2/pull/2379)
- 2382: PHPDoc params in AbstractTableGateway.php
  (https://github.com/zendframework/zf2/pull/2382)
- 2384: Replace Router with Http router in url view helper
  (https://github.com/zendframework/zf2/pull/2384)
- 2387: Replace PHP internal fmod function because it gives false negatives
  (https://github.com/zendframework/zf2/pull/2387)
- 2388: Proposed fix for ZF2-569 validating float with trailing 0\'s (10.0,
  10.10) (https://github.com/zendframework/zf2/pull/2388)
- 2391: clone in Filter\\FilterChain
  (https://github.com/zendframework/zf2/pull/2391)
- Security fix: a number of classes were not using the Escaper component in
  order to perform URL, HTML, and/or HTML attribute escaping. Please see
  http://framework.zend.com/security/advisory/ZF2012-03 for more details.',
  '2.0.0beta5' => '*Zend Framework 2.0.0beta5*

6 July 2012

THIS RELEASE IS A DEVELOPMENT RELEASE AND NOT INTENDED FOR PRODUCTION USE.
PLEASE USE AT YOUR OWN RISK.

This is the fifth and last in a series of planned beta releases. The
beta release cycle has followed the "gmail" style of betas, whereby new
features have been added in each new release, and BC has not been
guaranteed.

Following this release, we plan to perform some repository cleanup, a
standards audit, and documentation migration. Once these tasks are
complete, we will prepare our first Release Candidate (RC).

PLEASE NOTE: this beta includes a number of breaks from the previous
beta. Please read the notes below prefixed with "BC BREAK" for specific
breakages. The ZendSkeletonApplication typically reflects any BC changes
that have been made, and is a good resource.

 - Escaper component (Padraic Brady)
    - Provides context-specific escaping mechanisms for HTML content,
      HTML attributes, URLs, CSS, and JavaScript.
    - BC BREAK: The escape() view helper was removed, and replaced with
      escapeHtml(), escapeHtmlAttr(), escapeJs(), escapeCss(), and
      escapeUrl() implementations.
 - New I18n component (Ben Scholzen, Chris Martin, Dennis Portnov,
   Matthew Weier O\'Phinney)
    - New component leveraging PHP\'s ext/intl extension to provide
      internationalization (i18n) and localization (L10n) features and
      capabilities to applications.
    - LEVERAGES:
        - DateTime, DateTimezone, IntlDateFormatter
        - Locale
        - NumberFormatter
    - BC BREAK: REMOVES the following components:
        - Zend\\Currency
        - Zend\\Date
        - Zend\\Locale
        - Zend\\Measure
        - Zend\\Translator
        - All filters, validators, and view helpers that relied on the
          above.
    - PROVIDES:
        - Zend\\I18n\\Translator, including support for gettext and
          PHP-array-based translations (more are planned).
        - Zend\\I18n\\Filter, containing localized filtering capabilites
          for Alnum (alphanumeric), Alpha (alphabetic), and NumberFormat
          (numerical strings).
        - Zend\\I18n\\Validator, containing localized validation
          capabilities for Alnum (alphanumeric), Alpha (alphabetic),
          Iban (international bank account number standard), Int
          (integer), and PostCode (localized postal codes).
        - Zend\\I18n\\View, containing localized view helpers for
          CurrencyFormat, DateFormat, NumberFormat, Translate, and
          TranslatePlural.
 - Db layer additions (Ralph Schindler, Rob Allen, Guillaume Metayer,
   Sascha Howe, Chris Testroet, Evan Coury, Ben Youngblood)
    - Metadata support
    - Postgresql adapter/driver
    - New HydratingResultSet, allowing the ability to specify a custom
      hydrator (from Zend\\Stdlib\\Hydrator) for hydrating row objects.
    - Many bugfixes and stabilizations
 - Form additions (Matthew Weier O\'Phinney, Michaël Gallego, Yanick Rochon)
    - Annotations support: Ability to use annotations with a domain
      object in order to define a form, fieldsets, elements, inputs and
      input filters, and more.
    - Hydration of fieldsets; fieldsets may compose their own hydrators
      if desired.
    - Collection support; allows multiple instances of the same
      fieldset. As an example, you might have an interface that
      allows adding a set of form elements via an XHR call; on the
      backend, these would be defined as a collection, allowing
      arbitrary numbers of these fieldsets to be submitted.
    - New view helpers covering most HTML5-specific element types, most
      XHTML-specific element types. Additionally, a number of the
      HTML5-specific element types now have Element implementations to
      create turn-key solutions that include validation and filtering.
    - BC BREAK: Options support. Many attributes were being used not as
      HTML attributes but to define behavior. The ElementInterface now
      has an accessor and mutator for options. Examples of options
      include labels for non-radio/checkbox/select elements, the CAPTCHA
      adapter for CAPTCHA elements, CSRF tokens, etc. If you were
      defining labels in your forms, please move the label and label
      attributes definitions from the "attributes" to the "options" of
      the element, fieldset, or form.
    - BC BREAK: new interface, ElementPrepareAwareInterface, defining
      the method "prepareElement(Form $form)". The FieldsetInterface,
      and, by extension, FormInterface, extend this new interface. It is
      used to allow preparing elements prior to creating a
      representation.
 - MVC additions (Kyle Spraggs, Evan Coury, Matthew Weier O\'Phinney)
    - New "Params" controller plugin. Allows retrieving query, post,
      cookie, header, and route parameters. Usage is
      $this->params()->fromQuery($name, $default).
    - New listener, Zend\\Mvc\\ModuleRouteListener. When enabled, if a
      route match contains a "\\__NAMESPACE__" key, that namespace value
      will be prepended to the value of the "controller" key. This
      should typically be used in the root route for a given module, to
      ensure controller names do not clash.
    - Bootstrap simplification. A new "init()" method was created that
      accepts the path to a configuration file, and then creates and
      bootstraps the application; this eliminates all common boilerplate
      for the bootstrap scripts.
 - Hydrator changes (Adam Lundrigan)
    - BC BREAK: the ClassMethods hydrator now assumes by default that
      it should convert between underscore_separated names and
      camelCase.
 - BC BREAK: Doctrine Annotations Parser (Matthew Weier O\'Phinney, Marco
   Pivetta, Guilherme Blanco)
    - Zend\\Code\\Annotation now has a dependency on Doctrine\\Common for
      its annotation parser.
    - Annotations now conform to Doctrine\'s standards by default, but
      the AnnotationManager in ZF2 allows attaching alternate parsers
      for specific annotation types.
 - BC BREAK: Removal of Plugin Broker usage (Matthew Weier O\'Phinney,
   Evan Coury)
    - All uses of the Plugin Broker / Plugin Class Locator combination
      were removed. A new class, Zend\\ServiceManager\\AbstractPluginManager,
      was created and used to replace all previous usages of the plugin
      broker. This provides more flexibility in creation of plugins, as
      well as reduces the number of APIs developers need to learn.
    - Configuration of plugin managers is now done at the top-level. All
      plugin manager configuration follows the format utilized by
      Zend\\ServiceManager\\ServiceConfiguration, and
      Zend\\ModuleManager\\Listener\\ServiceListener has been updated to
      allow informing it of plugin manager instances it should manage,
      as well as the configuration key to utilize.
 - BC BREAK: Coding Standards (Maks3w, Sascha Prolic, Rob Allen)
    - Renamed most abstract classes to prefix them with the term
      "Abstract". In particular, ActionController and RestfulController
      are now AbstractActionController and AbstractRestfulController.
    - Renamed getters in HTTP, EventManager, and Mail components. These
      components were using accessors such as "events()", "query()",
      "headers()", etc. All such accessors were renamed to prepend
      "get", and, in the case of "events()", renamed to indicate the
      actual object retrieved ("getEventManager()").
 - SECURITY FIX: XmlRpc (Matthew Weier O\'Phinney)
    - A security issue arising from XML eXternal Entity (XXE) injection
      was patched; see http://framework.zend.com/security/advisory/ZF2012-01

Over *400* pull requests for a variety of features and bugfixes were handled
since beta4!',
  '2.0.7' => 'Zend Framework 2.0.7

- 1992: [2.1] Adding simple Zend/I18n/Loader/Tmx
  (https://github.com/zendframework/zf2/issues/1992)
- 2024: Add HydratingResultSet::toEntityArray()
  (https://github.com/zendframework/zf2/issues/2024)
- 2031: [2.1] Added MongoDB session save handler
  (https://github.com/zendframework/zf2/issues/2031)
- 2080: [2.1] Added a ChromePhp logger
  (https://github.com/zendframework/zf2/issues/2080)
- 2086: [2.1] Module class map cache
  (https://github.com/zendframework/zf2/issues/2086)
- 2100: [2.1] refresh() method in Redirect plugin
  (https://github.com/zendframework/zf2/issues/2100)
- 2105: [2.1] Feature/unidecoder
  (https://github.com/zendframework/zf2/issues/2105)
- 2106: [2.1] Class annotation scanner
  (https://github.com/zendframework/zf2/issues/2106)
- 2125: [2.1] Add hydrator wildcard and new hydrator strategy
  (https://github.com/zendframework/zf2/issues/2125)
- 2129: [2.1] Feature/overrideable di factories
  (https://github.com/zendframework/zf2/issues/2129)
- 2152: [2.1] [WIP] adding basic table view helper
  (https://github.com/zendframework/zf2/issues/2152)
- 2175: [2.1] Add DateSelect and MonthSelect elements
  (https://github.com/zendframework/zf2/issues/2175)
- 2189: [2.1] Added msgpack serializer
  (https://github.com/zendframework/zf2/issues/2189)
- 2190: [2.1] [WIP] Zend\\I18n\\Filter\\SlugUrl - Made a filter to convert text to
  slugs
  (https://github.com/zendframework/zf2/issues/2190)
- 2208: [2.1] Update library/Zend/View/Helper/HeadScript.php
  (https://github.com/zendframework/zf2/issues/2208)
- 2212: [2.1] Feature/uri normalize filter
  (https://github.com/zendframework/zf2/issues/2212)
- 2225: Zend\\Db\\Sql : Create NotIn predicate
  (https://github.com/zendframework/zf2/issues/2225)
- 2232: [2.1] Load Messages from other than file
  (https://github.com/zendframework/zf2/issues/2232)
- 2271: [2.1] Ported FingersCrossed handler from monolog to ZF2
  (https://github.com/zendframework/zf2/issues/2271)
- 2288: Allow to create empty option in Select
  (https://github.com/zendframework/zf2/issues/2288)
- 2305: Add support for prev and next link relationships
  (https://github.com/zendframework/zf2/issues/2305)
- 2315: Add MVC service factories for Filters and Validators
  (https://github.com/zendframework/zf2/issues/2315)
- 2316: Add paginator factory & adapter plugin manager
  (https://github.com/zendframework/zf2/issues/2316)
- 2333: Restore mail message from string
  (https://github.com/zendframework/zf2/issues/2333)
- 2339: ZF2-530 Implement PropertyScanner
  (https://github.com/zendframework/zf2/issues/2339)
- 2343: Create Zend Server Monitor Event
  (https://github.com/zendframework/zf2/issues/2343)
- 2367: Convert abstract classes that are only offering static methods
  (https://github.com/zendframework/zf2/issues/2367)
- 2374: Modified Acl/Navigation to be extendable
  (https://github.com/zendframework/zf2/issues/2374)
- 2381: Method Select::from can accept instance of Select as subselect
  (https://github.com/zendframework/zf2/issues/2381)
- 2389: Add plural view helper
  (https://github.com/zendframework/zf2/issues/2389)
- 2396: Rbac component for ZF2
  (https://github.com/zendframework/zf2/issues/2396)
- 2399: Feature/unidecoder new
  (https://github.com/zendframework/zf2/issues/2399)
- 2411: Allow to specify custom pattern for date
  (https://github.com/zendframework/zf2/issues/2411)
- 2414: Added a new validator to check if input is instance of certain class
  (https://github.com/zendframework/zf2/issues/2414)
- 2415: Add plural helper to I18n
  (https://github.com/zendframework/zf2/issues/2415)
- 2417: Allow to render template separately
  (https://github.com/zendframework/zf2/issues/2417)
- 2648: AbstractPluginManager should not respond to...
  (https://github.com/zendframework/zf2/issues/2648)
- 2650: Add view helper and controller plugin to pull the current identity from ...
  (https://github.com/zendframework/zf2/issues/2650)
- 2670: quoteIdentifier() & quoteIdentifierChain() bug
  (https://github.com/zendframework/zf2/issues/2670)
- 2702: Added addUse method in ClassGenerator
  (https://github.com/zendframework/zf2/issues/2702)
- 2704: Functionality/writer plugin manager
  (https://github.com/zendframework/zf2/issues/2704)
- 2706: Feature ini adapter translate
  (https://github.com/zendframework/zf2/issues/2706)
- 2718: Chain authentication storage
  (https://github.com/zendframework/zf2/issues/2718)
- 2774: Fixes #2745 (generate proper query strings).
  (https://github.com/zendframework/zf2/issues/2774)
- 2783: Added methods to allow access to the routes of the SimpleRouteStack.
  (https://github.com/zendframework/zf2/issues/2783)
- 2794: Feature test phpunit lib
  (https://github.com/zendframework/zf2/issues/2794)
- 2801: Improve Zend\\Code\\Scanner\\TokenArrayScanner
  (https://github.com/zendframework/zf2/issues/2801)
- 2807: Add buffer handling to HydratingResultSet
  (https://github.com/zendframework/zf2/issues/2807)
- 2809: Allow Zend\\Db\\Sql\\TableIdentifier in Zend\\Db\\Sql\\Insert, Update & Delete
  (https://github.com/zendframework/zf2/issues/2809)
- 2812: Catch exceptions thrown during rendering
  (https://github.com/zendframework/zf2/issues/2812)
- 2821: Added loadModule.post event to loadModule().
  (https://github.com/zendframework/zf2/issues/2821)
- 2822: Added the ability for FirePhp to understand \'extras\' passed to \\Zend\\Log
  (https://github.com/zendframework/zf2/issues/2822)
- 2841: Allow to remove attribute in form element
  (https://github.com/zendframework/zf2/issues/2841)
- 2844: [Server] & [Soap] Typos and docblocks
  (https://github.com/zendframework/zf2/issues/2844)
- 2848: fixing extract behavior of Zend\\Form\\Element\\Collection and added
  ability to use own fieldset helper within FormCollection-helper
  (https://github.com/zendframework/zf2/issues/2848)
- 2855: add a view event
  (https://github.com/zendframework/zf2/issues/2855)
- 2868: [WIP][Server] Rewrite Reflection API to reuse code from
  Zend\\Code\\Reflection API
  (https://github.com/zendframework/zf2/issues/2868)
- 2870: [Code] Add support for @throws, multiple types and typed arrays
  (https://github.com/zendframework/zf2/issues/2870)
- 2875: [InputFilter] Adding hasUnknown and getUnknown methods to detect and get
  unknown inputs
  (https://github.com/zendframework/zf2/issues/2875)
- 2919: Select::where should accept PredicateInterface
  (https://github.com/zendframework/zf2/issues/2919)
- 2927: Add a bunch of traits to ZF2
  (https://github.com/zendframework/zf2/issues/2927)
- 2931: Cache: Now an empty namespace means disabling namespace support
  (https://github.com/zendframework/zf2/issues/2931)
- 2953: [WIP] #2743 fix docblock @category/@package/@subpackage
  (https://github.com/zendframework/zf2/issues/2953)
- 2989: Decouple Zend\\Db\\Sql from concrete Zend\\Db\\Adapter implementations
  (https://github.com/zendframework/zf2/issues/2989)
- 2995: service proxies / lazy services
  (https://github.com/zendframework/zf2/issues/2995)
- 3017: Fixing the problem with order and \\Zend\\Db\\Sql\\Expression
  (https://github.com/zendframework/zf2/issues/3017)
- 3028: Added Json support for POST and PUT operations in restful controller.
  (https://github.com/zendframework/zf2/issues/3028)
- 3056: Add pattern & storage cache factory
  (https://github.com/zendframework/zf2/issues/3056)
- 3057: Pull zend filter compress snappy
  (https://github.com/zendframework/zf2/issues/3057)
- 3078: Allow NodeList to be accessed via array like syntax.
  (https://github.com/zendframework/zf2/issues/3078)
- 3081: Fix for Collection extract method updates targetElement object
  (https://github.com/zendframework/zf2/issues/3081)
- 3106: Added template map generator
  (https://github.com/zendframework/zf2/issues/3106)
- 3189: Added xterm\'s 256 colors
  (https://github.com/zendframework/zf2/issues/3189)
- 3200: Added ValidatorChain::attach() and ValidatorChain::attachByName() to
  keep consistent with FilterChain
  (https://github.com/zendframework/zf2/issues/3200)
- 3202: Added NTLM authentication support to Zend\\Soap\\Client\\DotNet.
  (https://github.com/zendframework/zf2/issues/3202)
- 3218: Zend-Form: Allow Input Filter Preference Over Element Defaults
  (https://github.com/zendframework/zf2/issues/3218)
- 3230: Add Zend\\Stdlib\\Hydrator\\Strategy\\ClosureStrategy
  (https://github.com/zendframework/zf2/issues/3230)
- 3241: Reflection parameter type check
  (https://github.com/zendframework/zf2/issues/3241)
- 3260: Zend/Di, retriving same shared instance for different extra parameters
  (https://github.com/zendframework/zf2/issues/3260)
- 3261: Fix sendmail key
  (https://github.com/zendframework/zf2/issues/3261)
- 3262:  Allows several translation files for same domain / locale
  (https://github.com/zendframework/zf2/issues/3262)
- 3269: A fix for issue #3195. Date formats are now used during validation.
  (https://github.com/zendframework/zf2/issues/3269)
- 3272: Support for internationalized .IT domain names
  (https://github.com/zendframework/zf2/issues/3272)
- 3273: Parse docblock indented with tabs
  (https://github.com/zendframework/zf2/issues/3273)
- 3285: Fixed wrong return usage and added @throws docblock
  (https://github.com/zendframework/zf2/issues/3285)
- 3286: remove else in already return early
  (https://github.com/zendframework/zf2/issues/3286)
- 3288: Removed unused variable
  (https://github.com/zendframework/zf2/issues/3288)
- 3292: Added Zend Monitor custom event support
  (https://github.com/zendframework/zf2/issues/3292)
- 3295: Proposing removal of subscription record upon unsubscribe
  (https://github.com/zendframework/zf2/issues/3295)
- 3296: Hotfix #3046 - set /dev/urandom as entropy file for Session
  (https://github.com/zendframework/zf2/issues/3296)
- 3298: Add PROPFIND Method to Zend/HTTP/Request
  (https://github.com/zendframework/zf2/issues/3298)
- 3300: Zend\\Config - Fix count after merge
  (https://github.com/zendframework/zf2/issues/3300)
- 3302: Fixed #3282
  (https://github.com/zendframework/zf2/issues/3302)
- 3303: Fix indentation, add trailing \',\' to last element in array
  (https://github.com/zendframework/zf2/issues/3303)
- 3304: Missed the Zend\\Text dependency for Zend\\Mvc in composer.json
  (https://github.com/zendframework/zf2/issues/3304)
- 3307: Fix an issue with inheritance of placeholder registry
  (https://github.com/zendframework/zf2/issues/3307)
- 3313: Fix buffering getTotalSpace
  (https://github.com/zendframework/zf2/issues/3313)
- 3317: Fixed FileGenerator::setUse() to ignore already added uses.
  (https://github.com/zendframework/zf2/issues/3317)
- 3318: Fixed FileGenerator::setUses() to allow passing in array of strings.
  (https://github.com/zendframework/zf2/issues/3318)
- 3320: Change @copyright Year : 2012 with 2013
  (https://github.com/zendframework/zf2/issues/3320)
- 3321: remove relative link in CONTRIBUTING.md
  (https://github.com/zendframework/zf2/issues/3321)
- 3322: remove copy variable for no reason
  (https://github.com/zendframework/zf2/issues/3322)
- 3324: enhance strlen to improve performance
  (https://github.com/zendframework/zf2/issues/3324)
- 3326: Minor loop improvements
  (https://github.com/zendframework/zf2/issues/3326)
- 3327: Fix indentation
  (https://github.com/zendframework/zf2/issues/3327)
- 3328: pass on the configured format to the DateValidator instead of hardcoding it
  (https://github.com/zendframework/zf2/issues/3328)
- 3329: Fixed DefinitionList::hasMethod()
  (https://github.com/zendframework/zf2/issues/3329)
- 3331: no chaining in form class\' bind method
  (https://github.com/zendframework/zf2/issues/3331)
- 3333: Fixed Zend/Mvc/Router/Http/Segment
  (https://github.com/zendframework/zf2/issues/3333)
- 3340: Add root namespace character
  (https://github.com/zendframework/zf2/issues/3340)
- 3342: change boolean to bool for consistency
  (https://github.com/zendframework/zf2/issues/3342)
- 3345: Update library/Zend/Form/View/Helper/FormRow.php
  (https://github.com/zendframework/zf2/issues/3345)
- 3352: ClassMethods hydrator and wrong method definition
  (https://github.com/zendframework/zf2/issues/3352)
- 3355: Fix for GitHub issue 2511
  (https://github.com/zendframework/zf2/issues/3355)
- 3356: ZF session validators
  (https://github.com/zendframework/zf2/issues/3356)
- 3362: Use CamelCase for naming
  (https://github.com/zendframework/zf2/issues/3362)
- 3369: Removed unused variable in Zend\\Json\\Decoder.php
  (https://github.com/zendframework/zf2/issues/3369)
- 3386: Adding attributes for a lightweight export
  (https://github.com/zendframework/zf2/issues/3386)
- 3393: [Router] no need to correct ~ in the path encoding
  (https://github.com/zendframework/zf2/issues/3393)
- 3396: change minimal verson of PHPUnit
  (https://github.com/zendframework/zf2/issues/3396)
- 3403: [ZF-8825] Lower-case lookup for "authorization" header
  (https://github.com/zendframework/zf2/issues/3403)
- 3409: Fix for broken handling of
  Zend\\ServiceManager\\ServiceManager::shareByDefault = false (Issue #3408)
  (https://github.com/zendframework/zf2/issues/3409)
- 3410: [composer] Sync replace package list
  (https://github.com/zendframework/zf2/issues/3410)
- 3415: Remove import of Zend root namespace
  (https://github.com/zendframework/zf2/issues/3415)
- 3423: Issue #3348 fix
  (https://github.com/zendframework/zf2/issues/3423)
- 3425: German Resources Zend_Validate.php updated.
  (https://github.com/zendframework/zf2/issues/3425)
- 3429: Add __destruct to SessionManager
  (https://github.com/zendframework/zf2/issues/3429)
- 3430: SessionManager: Throw exception when attempting to setId after the
  session has been started
  (https://github.com/zendframework/zf2/issues/3430)
- 3437: Feature/datetime factory format
  (https://github.com/zendframework/zf2/issues/3437)
- 3438: Add @method tags to the AbstractController
  (https://github.com/zendframework/zf2/issues/3438)
- 3439: Individual shared setting does not override the shareByDefault setting
  of the ServiceManager
  (https://github.com/zendframework/zf2/issues/3439)
- 3443: Adding logic to check module dependencies at module loading time
  (https://github.com/zendframework/zf2/issues/3443)
- 3445: Update library/Zend/Validator/Hostname.php
  (https://github.com/zendframework/zf2/issues/3445)
- 3452: Hotfix/session mutability
  (https://github.com/zendframework/zf2/issues/3452)
- 3473: remove surplus call deep namespace
  (https://github.com/zendframework/zf2/issues/3473)
- 3477: The display_exceptions config-option is not passed to 404 views.
  (https://github.com/zendframework/zf2/issues/3477)
- 3480: [Validator][#2538] hostname validator overwrite
  (https://github.com/zendframework/zf2/issues/3480)
- 3484: [#3055] Remove array to string conversion notice
  (https://github.com/zendframework/zf2/issues/3484)
- 3486: [#3073] Define filter() in Decompress filter
  (https://github.com/zendframework/zf2/issues/3486)
- 3487: [#3446] Allow generic traversable configuration to Captcha element
  (https://github.com/zendframework/zf2/issues/3487)
- 3492: Hotfix/random crypt test fail
  (https://github.com/zendframework/zf2/issues/3492)
- 3502: Features/port supermessenger
  (https://github.com/zendframework/zf2/issues/3502)
- 3513: Fixed bug in acl introduced by acca10b6abe74b3ab51890d5cbe0ab8da4fdf7e0
  (https://github.com/zendframework/zf2/issues/3513)
- 3520: Replace all is\\_null($value) calls with null === $value
  (https://github.com/zendframework/zf2/issues/3520)
- 3527: Explode validator: allow any value type to be validated
  (https://github.com/zendframework/zf2/issues/3527)
- 3530: The hasACL and hasRole don\'t check their default member variables
  (https://github.com/zendframework/zf2/issues/3530)
- 3550: Fix for the issue #3541 - salt size for Encrypt/Decrypt Filter
  (https://github.com/zendframework/zf2/issues/3550)
- 3562: Fix: Calling count() results in infinite loop
  (https://github.com/zendframework/zf2/issues/3562)
- 3563: Zend\\Db: Fix for #3523 changeset - composer.json and stdlib
  (https://github.com/zendframework/zf2/issues/3563)
- 3571: Correctly parse empty Subject header
  (https://github.com/zendframework/zf2/issues/3571)
- 3575: Fix name of plugin referred to in exception message
  (https://github.com/zendframework/zf2/issues/3575)
- 3579: Some minor fixes in \\Zend\\View\\Helper\\HeadScript() class
  (https://github.com/zendframework/zf2/issues/3579)
- 3593: \\Zend\\Json\\Server Fix \\_getDefaultParams if request-params are an
  associative array
  (https://github.com/zendframework/zf2/issues/3593)
- 3594: Added contstructor to suppressfilter
  (https://github.com/zendframework/zf2/issues/3594)
- 3601: Update Travis to start running tests on PHP 5.5
  (https://github.com/zendframework/zf2/issues/3601)
- 3604: fixed Zend\\Log\\Logger::registerErrorHandler() doesn\'t log previous
  exceptions
  (https://github.com/zendframework/zf2/issues/3604)',
  '2.0.0rc6' => 'Zend Framework 2.0.0rc6

This is the sixth release candidate for 2.0.0. At this time, we anticipate that
this will be the final release candidate before issuing a stable release.
We highly recommend testing your production applications against it.

29 August 2012

- Zend\\Config
  - The INI adapter now allows bracket notation for appending values to an array
    within INI definitions.
- Zend\\Db
  - ResultInterface adds isBuffered() method for checking if the resultset is
    buffered or not. Allows for more fine grained control of result set
    buffering, including using the database engine\'s native buffering.
  - Insertions with multi-part keys now work properly.
  - Expression objects may now be passed to the order() method of a Select
    object.
- Zend\\Form
  - You can now omit error messages on elements when rendering via formRow(), by
    passing a boolean false as the third argument of the helper.
  - You can now use concrete hydrator instances with the factory.
  - You may now set the CSRF validator class and/or options to use on the Csrf
    element
  - The Select, Radio, and MultiCheckbox elements and view helpers were
    refactored to move value options into properties, instead of attributes.
    This makes them more consistent with other elements, and simplifies the
    interfaces.
  - Forms now lazy-load an input filter if none has been specified; this should
    simplify usage for many, and remove the "no input filter attached"
    exception.
  - All form helpers for buttons (button, submit, reset) now allow translation.
  - The formRow() view helper now allows you to set the CSS class used to
    designate an input with errors.
- Zend\\Http
  - Some browser/web server combingations set SERVER_NAME to the IPv6 address,
    and enclose it in brackets. The PhpEnvironment\\Request object now correctly
    detects such situations.
  - The Socket client will only fallback to SSLv3 if the ssltransport
    configuration key is not set (instead of also allowing SSLv2).
- Zend\\I18n\\Translator
  - Loader\\LoaderInterface was splitted into Loader\\FileLoaderInterface and
    Loader\\RemoteLoaderInterface. The latter one will be used in ZF 2.1 for
    a database loader.
  - Translator::addTranslationPattern() and the option "translation_patterns"
    were renamed to Translator::addTranslationFilePattern and
    "translation_file_patterns".
  - A new method Translator::addRemoteTranslations() was added.
- Zend\\Mvc
  - Application no longer defines the "application" identifier for its composed
    EventManager instance. If you had listeners listening on that context,
    update them to use "Zend\\Mvc\\Application". See this thread for more details:

      http://zend-framework-community.634137.n4.nabble.com/Change-to-Zend-Mvc-Application-s-event-identifiers-tp4656517.html

  - The redirect plugin\'s toRoute() method signature is now synced with that of
    the url plugin\'s fromRoute() method.
  - The PRG plugin now allows passing no arguments; if you do so, the currently
    matched route will be used for the redirect.
- Zend\\Paginator
  - Removes the factory() and related methods. This was done to be more
    consistent with other components, and also because the utility was not
    terribly useful; in most cases, developers needed to configure the adapter
    up-front anyways.
- Zend\\Stdlib
  - ClassMethods Hydrator now supports boolean getters prefixed with "is".
- Zend\\Validator
  - DB validators no longer mix positional and named parameters.

More than 30 pull requests for a variety of features and bugfixes were handled
since RC5, as well as almost 20 documentation changes!',
  '2.1.1' => 'Zend Framework 2.1.1

- 2510: Zend\\Session\\Container does not allow modification by reference
  (https://github.com/zendframework/zf2/issues/2510)
- 2899: Can\'t inherit abstract function
  Zend\\Console\\Prompt\\PromptInterface::show()
  (https://github.com/zendframework/zf2/issues/2899)
- 3455: Added DISTINCT on Zend\\Db\\Sql\\Select
  (https://github.com/zendframework/zf2/issues/3455)
- 3456: Connection creation added in Pgsql.php createStatement method
  (https://github.com/zendframework/zf2/issues/3456)
- 3608: Fix validate data contains arrays as values
  (https://github.com/zendframework/zf2/issues/3608)
- 3610: Form: rely on specific setter
  (https://github.com/zendframework/zf2/issues/3610)
- 3618: Fix bug when $indent have some string
  (https://github.com/zendframework/zf2/issues/3618)
- 3622: Updated Changelog with BC notes for 2.1 and 2.0.7
  (https://github.com/zendframework/zf2/issues/3622)
- 3623: Authentication using DbTable Adapter doesn\'t work for 2.1.0
  (https://github.com/zendframework/zf2/issues/3623)
- 3625: Missing instance/object for parameter route upgrading to 2.1.\\*
  (https://github.com/zendframework/zf2/issues/3625)
- 3627: Making relative links in Markdown files
  (https://github.com/zendframework/zf2/issues/3627)
- 3629: Zend\\Db\\Select using alias in joins can results in wrong SQL
  (https://github.com/zendframework/zf2/issues/3629)
- 3638: Fixed method that removed part from parts in Mime\\Message
  (https://github.com/zendframework/zf2/issues/3638)
- 3639: Session Metadata and SessionArrayStorage requestaccesstime fixes.
  (https://github.com/zendframework/zf2/issues/3639)
- 3640: [#3625] Do not query abstract factories for registered invokables
  (https://github.com/zendframework/zf2/issues/3640)
- 3641: Zend\\Db\\Sql\\Select Fix for #3629
  (https://github.com/zendframework/zf2/issues/3641)
- 3645: Exception on destructing the SMTP Transport instance
  (https://github.com/zendframework/zf2/issues/3645)
- 3648: Ensure run() always returns Application instance
  (https://github.com/zendframework/zf2/issues/3648)
- 3649: Created script to aggregate return status
  (https://github.com/zendframework/zf2/issues/3649)
- 3650: InjectControllerDependencies initializer overriding an previously
  defined EventManager
  (https://github.com/zendframework/zf2/issues/3650)
- 3651: Hotfix/3650
  (https://github.com/zendframework/zf2/issues/3651)
- 3656: Zend\\Validator\\Db\\AbstractDb.php and mysqli
  (https://github.com/zendframework/zf2/issues/3656)
- 3658: Zend\\Validator\\Db\\AbstractDb.php and mysqli (issue: 3656)
  (https://github.com/zendframework/zf2/issues/3658)
- 3661: ZF HTTP Status Code overwritten
  (https://github.com/zendframework/zf2/issues/3661)
- 3662: Remove double injection in Plugin Controller Manager
  (https://github.com/zendframework/zf2/issues/3662)
- 3663: Remove useless shared in ServiceManager
  (https://github.com/zendframework/zf2/issues/3663)
- 3671: Hotfix/restful head identifier
  (https://github.com/zendframework/zf2/issues/3671)
- 3673: Add translations for Zend\\Validator\\File\\UploadFile
  (https://github.com/zendframework/zf2/issues/3673)
- 3679: remove \'\\\' character from Traversable
  (https://github.com/zendframework/zf2/issues/3679)
- 3680: Zend\\Validator\\Db Hotfix (supersedes #3658)
  (https://github.com/zendframework/zf2/issues/3680)
- 3681: [#2899] Remove redundant method declaration
  (https://github.com/zendframework/zf2/issues/3681)
- 3682: Zend\\Db\\Sql\\Select Quantifier (DISTINCT, ALL, + Expression) support -
  supersedes #3455
  (https://github.com/zendframework/zf2/issues/3682)
- 3684: Remove the conditional class declaration of ArrayObject
  (https://github.com/zendframework/zf2/issues/3684)
- 3687: fix invalid docblock
  (https://github.com/zendframework/zf2/issues/3687)
- 3689: [#3684] Polyfill support for version-dependent classes
  (https://github.com/zendframework/zf2/issues/3689)
- 3690: oracle transaction support
  (https://github.com/zendframework/zf2/issues/3690)
- 3692: Hotfix/db parametercontainer mixed use
  (https://github.com/zendframework/zf2/issues/3692)',
  '2.0.2' => 'This is the second maintenance release for the 2.0 series.

21 Sep 2012

- 2383: Changed unreserved char definition in Zend\\Uri (ZF2-533) and added shell
  escaping to the test runner (https://github.com/zendframework/zf2/pull/2383)
- 2393: Trying to solve issue ZF2-558
  (https://github.com/zendframework/zf2/pull/2393)
- 2398: Segment route: add fix for optional groups within optional groups
  (https://github.com/zendframework/zf2/pull/2398)
- 2400: Use \'Router\' in http env and \'HttpRouter\' in cli
  (https://github.com/zendframework/zf2/pull/2400)
- 2401: Better precision for userland fmod algorithm
  (https://github.com/zendframework/zf2/pull/2401)',
  '2.0.0rc1' => '*Zend Framework 2.0.0rc1*

This is the first release candidate for 2.0.0. We will be releasing RCs
on a weekly basis until we feel all critical issues are addressed. At
this time, we anticipate no API changes before the stable release, and
recommend testing your production applications against it.

25 July 2012

 - Documentation
   - is now in a new repository,
     https://github.com/zendframework/zf2-documentation
   - Documentation has been converted from DocBook5 to ReStructured Text
     (reST or rst).
 - Coding standards fixes
   - We are (mostly) PSR-2 compliant at this time
 - Moved all Service components, include Cloud, GData, OAuth, OpenID,
   and Rest to separate repositories under the zendframework
   organization on GitHub. This will allow them to be versioned
   separately, which allows them to break backwards compatibility when
   necessitated by API changes.
 - Removed Zend\\InfoCard as InfoCard has been declared obsolete by
   MicroSoft.
 - Removed Zend\\Registry; without the singleton nature, it was confusing
   and no longer relevant.
 - Removed Zend\\Test component. Most features are now part of PHPUnit,
   and the others were ZF1-specific.
 - Removed Zend\\Wildfire, as its API was specific to ZF1, and because we
   can easily leverage FirePHP at this time.
 - Removed Zend\\DocBook, as it was primarily to assist in creating
   DocBook5 skeletons for the manual; since we\'ve moved to rst, this is
   no longer relevant.
 - Removed Zend\\Dojo, as the implementation was ZF1 specific, and
   out-of-date with recent Dojo releases.
 - Moved Amf, Markup, Pdf, Queue, Search, and TimeSync to separate
   repositories, as their APIs are not yet stable. PDF will be released
   with 2.0.0rc1, but only to provide a dependency for Zend\\Barcode.
 - Renamed any classes, properties, or methods referencing the word
   "configuration" to read "config" instead; this provides consistency
   internally, and with the Zend\\Config component.
 - Moved Zend\\Acl to Zend\\Permissions\\Acl.
 - Console
   - Added features for console routing, providing more flexibility over the
     traditional Getopt methodology.
   - Added colorization, tables, prompts, and a variety of other interactive
     features.
   - Added ability to use controllers to respond to console routes.
 - Crypt, Math, Filter\\Encrypt and Filter\\Decrypt
   - Random number generation was consolidated to Zend\\Math.
   - Removed the Mcrypt adapter in Filter and replaced with a
     BlockCipher algorithm.
 - DB
   - Metadata now understands enums and sets
   - Added Replace SQL statement type
   - AbstractRowGateway provides more cohesive access to field values.
 - EventManager
   - The first call to setSharedManager() will now seed the
     StaticEventManager, in order to match user expectations that the
     shared event manager is the same everywhere.
 - Form
   - Select-style elements now have options populated as value => label
     pairs instead of label => value pairs. This is done to ensure that
     option values are unique.
   - Added getValue() and setValue() to the ElementInterface to make a
     semantic distinction between an element value and attributes. This
     allows for easier handling of non-string or calculated values, such
     as those found in the Csrf and DateTime-family of elements.
   - getMessages() now omits any elements that have an empty messages
     array.
   - Allows removing elements from Collections
   - Fixed default validators for MultiCheckbox and Radio elements
   - Custom options are now allowed for all elements, fieldsets, and
     forms.
   - Labels and several other view helpers are now translator-capable
 - Http
   - set/getServer() and set/getEnv() were removed from Http\\Request
     and now part of Http\\PhpEnvironment\\Request
   - set/getFile() methods in Http\\PhpEnvironment\\Request
     were renamed to set/getFiles(). Also above methods
   - When submitted form has file inputs with brackets (name="file[]")
     $fileParams parameters in Http\\PhpEnvironment\\Request will be
     re-structured to have the same look as query/post/server/envParams
   - each of get(Post|Query|Headers|Files|Env|Server) were provided with
     two optional arguments, a specific key to retrieve, and a default
     value to return if not found.
   - Accept header parsing is more robust with regards to priority.
 - InputFilter
   - Added ability to retrieve input objects
 - I18n
   - Moved Zend\\I18n\\Validator\\Iban to Zend\\Validator\\Iban
     and replaced the option "locale" with "country_code"
 - Json
   - Enabled a number of additional flags for json_encode
 - Loader
   - Removed the PrefixPathLoader, and replaced all usages of it in the
     framework with custom AbstractPluginManager implementations; this
     includes Zend\\Feed, Zend\\Text, and Zend\\File\\Transfer.
 - Log
   - Added a MongoDB log writer
   - Added a FirePHP log writer
   - Refactored how filters are instantiated and managed to use an
     AbstractPluginManager instance.
 - Mail
   - Added a MessageId header class
 - ModuleManager
   - Made it possible to substitute alternate ServiceListener
     implementations
   - Moved default service configuration from the ModuleManager to the
     ServiceListener
 - MVC
   - Fixed a potential security issue in the ControllerLoader whereby
     arbitrary, non-controller classes could be instantiated. This
     involves removing the ability to fetch controllers via the DI Proxy
     (a minor backwards compatibility break).
   - Restful controller now provides a simpler way to marshal input data
     and override the default behavior.
   - Most View-related services were moved to their own factories to
     allow easier overriding by developers.
   - New PostRedirectGet plugin, to simplify PRG strategies for form
     submissions.
 - Serializer was refactored to make better use of PHP 5.3 features and
   to simplify the API.
 - ServiceManager
   - Allow passing the SM instance to initializers
   - Allow specifying the classname of an InitializerInterface
     implementation to addInitializer()
 - Validator
   - ValidatorChain now has a getValidators() method
   - InArray validator now does context-aware strict checks to prevent
     false positive matches, fixing a potential security vulnerability.
 - View
   - New AbstractTranslatorHelper, for helpers that should allow
     translations.

Almost *200* pull requests for a variety of features and bugfixes were handled
since beta5!',
  '2.0.0rc7' => 'Zend Framework 2.0.0rc7

This is the seventh release candidate for 2.0.0. At this time, we anticipate
that this will be the final release candidate before issuing a stable release.
We highly recommend testing your production applications against it.

31 August 2012

- Zend\\Di
  - Fixes ArrayDefinition and ClassDefinition hasMethods() methods to return
    boolean values.
- Zend\\Form
  - Fixes issue with multi-checkbox rendering.
- Zend\\I18n
  - DateFormat view helper now correctly falls back to date.timezone setting
    instead of system timezone.
- Zend\\Ldap
  - Fixes an error nesting condition
- Zend\\Log
  - Fixes an issue with Zend\\Log\\Formatter\\Simple whereby it was using a legacy
    key ("info") instead of the key standardized upon in ZF2 ("extra").
  - Simple formatter now defaults to JSON-encoding for array and object
    serialization (prevents issues with some writers.)
- Zend\\Mail
  - The Date header is now properly encoded as ASCII.
- Zend\\Mvc
  - Fixes an issue in the ViewHelperManagerFactory whereby a condition was
    testing against an uninitialized value.
  - Added zend-console to composer.json dependencies.
- Zend\\View
  - Breadcrumbs helper allows passing string container name now, allowing
    multiple navigation containers.
  - ServerUrl now works for servers behind proxies.',
  '2.0.0beta1' => 'Zend Framework 2.0.0beta1

THIS RELEASE IS A DEVELOPMENT RELEASE AND NOT INTENDED FOR PRODUCTION USE.
PLEASE USE AT YOUR OWN RISK.

This is the first in a series of planned beta releases. The beta release
cycle will follow the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen no less than every six weeks.

Once the established milestones have been reached and the featureset has
reached maturity and reasonable stability, we will freeze the API and
prepare for Release Candidate status.

NEW FEATURES
------------

- New and refactored autoloaders:
  - Zend\\Loader\\StandardAutoloader
  - Zend\\Loader\\ClassMapAutoloader
  - Zend\\Loader\\AutoloaderFactory
- New plugin broker strategy
  - Zend\\Loader\\Broker and Zend\\Loader\\PluginBroker
- Reworked Exception system
  - Allow catching by specific Exception type
  - Allow catching by component Exception type
  - Allow catching by SPL Exception type
  - Allow catching by base Exception type
- Rewritten Session component
- Refactored View component
  - Split helpers into a PluginBroker
  - Split variables into a Variables container
  - Split script paths into a TemplateResolver
  - Renamed base View class "PhpRenderer"
  - Refactored helpers to utilize __invoke() when possible
- Refactored HTTP component
- New Zend\\Cloud\\Infrastructure component
- New EventManager component
- New Dependency Injection (Zend\\Di) component
- New Code component
  - Incorporates refactored versions of former Reflection and
    CodeGenerator components.
  - Introduces Scanner component.
  - Introduces annotation system.
- New MVC layer
  - Zend\\Module, for developing modular application architectures.
  - Zend\\Mvc, a completely reworked MVC layer built on top of HTTP,
    EventManager, and Di.
- Introduces new packaging system, allowing the usage of Pyrus
  (http://pear2.php.net) to install individual components and/or groups
  of components.',
  '2.0.3' => 'Zend Framework 2.0.3

Resolves the following issues and/or includes the following changes:

- 2244: Fix for issue ZF2-503 (https://github.com/zendframework/zf2/issues/2244)
- 2318: Allow to remove decimals in CurrencyFormat
  (https://github.com/zendframework/zf2/issues/2318)
- 2363: Hotfix db features with eventfeature
  (https://github.com/zendframework/zf2/issues/2363)
- 2380: ZF2-482 Attempt to fix the buffer. Also added extra unit tests.
  (https://github.com/zendframework/zf2/issues/2380)
- 2392: Update library/Zend/Db/Adapter/Platform/Mysql.php
  (https://github.com/zendframework/zf2/issues/2392)
- 2395: Fix for http://framework.zend.com/issues/browse/ZF2-571
  (https://github.com/zendframework/zf2/issues/2395)
- 2397: Memcached option merge issuse
  (https://github.com/zendframework/zf2/issues/2397)
- 2402: Adding missing dependencies
  (https://github.com/zendframework/zf2/issues/2402)
- 2404: Fix to comments (https://github.com/zendframework/zf2/issues/2404)
- 2416: Fix expressionParamIndex for AbstractSql
  (https://github.com/zendframework/zf2/issues/2416)
- 2420: Zend\\Db\\Sql\\Select: Fixed issue with join expression named parameters
  overlapping. (https://github.com/zendframework/zf2/issues/2420)
- 2421: Update library/Zend/Http/Header/SetCookie.php
  (https://github.com/zendframework/zf2/issues/2421)
- 2422: fix add 2 space after @param in Zend\\Loader
  (https://github.com/zendframework/zf2/issues/2422)
- 2423: ManagerInterface must be interface, remove \'interface\' description
  (https://github.com/zendframework/zf2/issues/2423)
- 2425: Use built-in Travis composer
  (https://github.com/zendframework/zf2/issues/2425)
- 2426: Remove need of setter in ClassMethods hydrator
  (https://github.com/zendframework/zf2/issues/2426)
- 2432: Prevent space before end of tag with HTML5 doctype
  (https://github.com/zendframework/zf2/issues/2432)
- 2433: fix for setJsonpCallback not called when recieved JsonModel + test
  (https://github.com/zendframework/zf2/issues/2433)
- 2434: added phpdoc in Zend\\Db
  (https://github.com/zendframework/zf2/issues/2434)
- 2437: Hotfix/console 404 reporting
  (https://github.com/zendframework/zf2/issues/2437)
- 2438: Improved previous fix for ZF2-558.
  (https://github.com/zendframework/zf2/issues/2438)
- 2440: Turkish Translations for Captcha and Validate
  (https://github.com/zendframework/zf2/issues/2440)
- 2441: Allow form collection to have any helper
  (https://github.com/zendframework/zf2/issues/2441)
- 2516: limit(20) -> generates LIMIT \'20\' and throws an IllegalQueryException
  (https://github.com/zendframework/zf2/issues/2516)
- 2545: getSqlStringForSqlObject() returns an invalid SQL statement with LIMIT
  and OFFSET clauses (https://github.com/zendframework/zf2/issues/2545)
- 2595: Pgsql adapater has codes related to MySQL
  (https://github.com/zendframework/zf2/issues/2595)
- 2613: Prevent password to be rendered if form validation fails
  (https://github.com/zendframework/zf2/issues/2613)
- 2617: Fixed Zend\\Validator\\Iban class name
  (https://github.com/zendframework/zf2/issues/2617)
- 2619: Form enctype fix when File elements are within a collection
  (https://github.com/zendframework/zf2/issues/2619)
- 2620: InputFilter/Input when merging was not using raw value
  (https://github.com/zendframework/zf2/issues/2620)
- 2622: Added ability to specify port
  (https://github.com/zendframework/zf2/issues/2622)
- 2624: Form\'s default input filters added multiple times
  (https://github.com/zendframework/zf2/issues/2624)
- 2630: fix relative link ( remove the relative links ) in README.md
  (https://github.com/zendframework/zf2/issues/2630)
- 2631: Update library/Zend/Loader/AutoloaderFactory.php
  (https://github.com/zendframework/zf2/issues/2631)
- 2633: fix redundance errors "The input does not appear to be a valid date"
  show twice (https://github.com/zendframework/zf2/issues/2633)
- 2635: Fix potential issue with Sitemap test
  (https://github.com/zendframework/zf2/issues/2635)
- 2636: add isset checks around timeout and maxredirects
  (https://github.com/zendframework/zf2/issues/2636)
- 2641: hotfix : formRow() element error multi-checkbox and radio renderError
  not shown (https://github.com/zendframework/zf2/issues/2641)
- 2642: Fix Travis build for CS issue
  (https://github.com/zendframework/zf2/issues/2642)
- 2643: fix for setJsonpCallback not called when recieved JsonModel + test
  (https://github.com/zendframework/zf2/issues/2643)
- 2644: Add fluidity to the prepare() function for a form
  (https://github.com/zendframework/zf2/issues/2644)
- 2652: Zucchi/filter tweaks (https://github.com/zendframework/zf2/issues/2652)
- 2665: pdftest fix (https://github.com/zendframework/zf2/issues/2665)
- 2666: fixed url change (https://github.com/zendframework/zf2/issues/2666)
- 2667: Possible fix for rartests
  (https://github.com/zendframework/zf2/issues/2667)
- 2669: skip whem gmp is loaded
  (https://github.com/zendframework/zf2/issues/2669)
- 2673: Input fallback value option
  (https://github.com/zendframework/zf2/issues/2673)
- 2676: mysqli::close() never called
  (https://github.com/zendframework/zf2/issues/2676)
- 2677: added phpdoc to Zend\\Stdlib
  (https://github.com/zendframework/zf2/issues/2677)
- 2678: Zend\\Db\\Adapter\\Sqlsrv\\Sqlsrv never calls Statement\\initialize() (fix
  within) (https://github.com/zendframework/zf2/issues/2678)
- 2679: Zend/Log/Logger.php using incorrect php errorLevel
  (https://github.com/zendframework/zf2/issues/2679)
- 2680: Cache: fixed bug on getTotalSpace of filesystem and dba adapter
  (https://github.com/zendframework/zf2/issues/2680)
- 2681: Cache/Dba: fixed notices on tearDown db4 tests
  (https://github.com/zendframework/zf2/issues/2681)
- 2682: Replace \'Configuration\' with \'Config\' when retrieving configuration
  (https://github.com/zendframework/zf2/issues/2682)
- 2683: Hotfix: Allow items from Abstract Factories to have setShared() called
  (https://github.com/zendframework/zf2/issues/2683)
- 2685: Remove unused Uses (https://github.com/zendframework/zf2/issues/2685)
- 2686: Adding code to allow EventManager trigger listeners using wildcard
  identifier (https://github.com/zendframework/zf2/issues/2686)
- 2687: Hotfix/db sql nested expressions
  (https://github.com/zendframework/zf2/issues/2687)
- 2688: Hotfix/tablegateway event feature
  (https://github.com/zendframework/zf2/issues/2688)
- 2689: Hotfix/composer phpunit
  (https://github.com/zendframework/zf2/issues/2689)
- 2690: Use RFC-3339 full-date format (Y-m-d) in Date element
  (https://github.com/zendframework/zf2/issues/2690)
- 2691: join on conditions don\'t accept alternatives to columns
  (https://github.com/zendframework/zf2/issues/2691)
- 2693: Update library/Zend/Db/Adapter/Driver/Mysqli/Connection.php
  (https://github.com/zendframework/zf2/issues/2693)
- 2694: Bring fluid interface to Feed Writer
  (https://github.com/zendframework/zf2/issues/2694)
- 2698: fix typo in # should be :: in exception
  (https://github.com/zendframework/zf2/issues/2698)
- 2699: fix elseif in javascript Upload Demo
  (https://github.com/zendframework/zf2/issues/2699)
- 2700: fix cs in casting variable
  (https://github.com/zendframework/zf2/issues/2700)
- 2705: Fix french translation
  (https://github.com/zendframework/zf2/issues/2705)
- 2707: Improved error message when ServiceManager does not find an invokable
  class (https://github.com/zendframework/zf2/issues/2707)
- 2710: #2461 - correcting the url encoding of path segments
  (https://github.com/zendframework/zf2/issues/2710)
- 2711: Fix/demos ProgressBar/ZendForm.php : Object of class Zend\\Form\\Form
  could not be converted to string
  (https://github.com/zendframework/zf2/issues/2711)
- 2712: fix cs casting variable for (array)
  (https://github.com/zendframework/zf2/issues/2712)
- 2713: Update library/Zend/Mvc/Service/ViewHelperManagerFactory.php
  (https://github.com/zendframework/zf2/issues/2713)
- 2714: Don\'t add separator if not prefixing columns
  (https://github.com/zendframework/zf2/issues/2714)
- 2717: Extends when it can : Validator\\DateStep extends Validator\\Date to
  reduce code redundancy (https://github.com/zendframework/zf2/issues/2717)
- 2719: Fixing the Cache Storage Factory Adapter Factory
  (https://github.com/zendframework/zf2/issues/2719)
- 2728: Bad Regex for Content Type header
  (https://github.com/zendframework/zf2/issues/2728)
- 2731: Reset the Order part when resetting Select
  (https://github.com/zendframework/zf2/issues/2731)
- 2732: Removed references to Mysqli in Zend\\Db\\Adapter\\Driver\\Pgsql
  (https://github.com/zendframework/zf2/issues/2732)
- 2733: fix @package Zend_Validate should be Zend_Validator
  (https://github.com/zendframework/zf2/issues/2733)
- 2734: fix i18n @package and @subpackage value
  (https://github.com/zendframework/zf2/issues/2734)
- 2736: fix captcha helper test.
  (https://github.com/zendframework/zf2/issues/2736)
- 2737: Issue #2728 - Bad Regex for Content Type header
  (https://github.com/zendframework/zf2/issues/2737)
- 2738: fix link \'quickstart\' to version 2.0
  (https://github.com/zendframework/zf2/issues/2738)
- 2739: remove \'@subpackage\'  because Zend\\Math is not in subpackage
  (https://github.com/zendframework/zf2/issues/2739)
- 2742: remove () in echo-ing (https://github.com/zendframework/zf2/issues/2742)
- 2749: Fix for #2678 (Zend\\Db\'s Sqlsrv Driver)
  (https://github.com/zendframework/zf2/issues/2749)
- 2750: Adds the ability to instanciate by factory to AbstractPluginManager
  (https://github.com/zendframework/zf2/issues/2750)
- 2754: add the support to register module paths over namespace
  (https://github.com/zendframework/zf2/issues/2754)
- 2755:  remove Zend\\Mvc\\Controller\\PluginBroker from aliases in
  "$defaultServiceConfig" (https://github.com/zendframework/zf2/issues/2755)
- 2759: Fix Zend\\Code\\Scanner\\TokenArrayScanner
  (https://github.com/zendframework/zf2/issues/2759)
- 2764: Fixed Zend\\Math\\Rand::getString() to pass the parameter $strong to
  ::getBytes() (https://github.com/zendframework/zf2/issues/2764)
- 2765: Csrf: always use dedicated setter
  (https://github.com/zendframework/zf2/issues/2765)
- 2766: Session\\Storage: always preserve REQUEST_ACCESS_TIME
  (https://github.com/zendframework/zf2/issues/2766)
- 2768: Zend\\Validator dependency is missed in Zend\\Cache composer.json
  (https://github.com/zendframework/zf2/issues/2768)
- 2769: change valueToLDAP to valueToLdap and valueFromLDAP to valueFromLdap
  (https://github.com/zendframework/zf2/issues/2769)
- 2770: Memcached (https://github.com/zendframework/zf2/issues/2770)
- 2775: Zend\\Db\\Sql: Fix for Mysql quoting during limit and offset
  (https://github.com/zendframework/zf2/issues/2775)
- 2776: Allow whitespace in Iban
  (https://github.com/zendframework/zf2/issues/2776)
- 2777: Fix issue when PREG_BAD_UTF8_OFFSET_ERROR is defined but Unicode support
  is not enabled on PCRE (https://github.com/zendframework/zf2/issues/2777)
- 2778: Undefined Index fix in ViewHelperManagerFactory
  (https://github.com/zendframework/zf2/issues/2778)
- 2779: Allow forms that have been added as fieldsets to bind values to bound
  ob... (https://github.com/zendframework/zf2/issues/2779)
- 2782: Issue 2781 (https://github.com/zendframework/zf2/issues/2782)',
  '2.0.0rc2' => 'Zend Framework 2.0.0rc2

This is the second release candidate for 2.0.0. We will be releasing RCs
on a weekly basis until we feel all critical issues are addressed. At
this time, we anticipate few API changes before the stable release, and
recommend testing your production applications against it.

01 August 2012

 - REALLY removed Zend\\Markup from the repository (we reported it
   removed for RC1, and had in fact created the repository for it, but
   not removed it from the zf2 repository).
 - Addition of Hydrator strategies, to allow easier hydration of
   composed objects. The HydratorInterface remains unchanged, but the
   shipped hydrators now all implement both that and the new
   StrategyEnabledInterface.
 - Zend\\View\\Model\\ViewModel::setVariables() no longer overwrites the
   internal variables container by default. If you wish to do so, it
   does provide an optional $overwrite argument; passing a boolean true
   will cause the method to overwrite the container.
 - Zend\\Validator\\Iban was expanded to include Single Euro Payments Area
   (SEPA) support
 - Zend\\Mvc\\Controller\\ControllerManager now allows fetching controllers
   via DI. This is done via a new DiStrict abstract service factory,
   which only fetches services in a provided whitelist.
 - Zend\\Json\\Encoder now accepts IteratorAggregates.
 - Controller, Filter, and Validator plugin managers were fixed to no
   longer share instances.
 - Zend\\Form was updated to only bind values that were actually provided
   in the data. Additionally, if a Collection has no entries, it will be
   removed from the validation group. Finally, elements with the name
   "0" (zero) are now allowed.
 - Zend\\View\\Helper\\Doctype was updated to respond true to isRdfa() when
   the doctype is an HTML5 variant.
 - Zend\\Navigation was fixed to ensure the navigation services is passed
   correctly between helpers. Additionally, a bug in Mvc::isActive() was
   fixed to ensure routes were properly seeded.
 - The GreaterThan and LessThan validators were updated to pass
   constructor arguments to the parents, for consistency with other
   validators.
 - Log formatters are now responsible for formatting DateTime values in
   the log events.
 - The Console ViewManager was updated to extend from the standard HTTP
   version, and to use Config instead of Configuration, fixing several
   minor issues.
 - Zend\\Version was moved to Zend\\Version\\Version (for consistency)
 - Zend\\Debug was moved to Zend\\Debug\\Debug (for consistency)
 - All protected members that still had underscore prefixes were
   refactored to remove that prefix.
 - Identified and fixed all CS issues as identified by php-cs-fixer, and
   added php-cs-fixer to the Travis-CI build tasks.
 - ServiceManagerAwareInterface was removed from all but the most
   necessary locations, and replaced with ServiceLocatorAwareInterface.
 - Zend\\Feed\\Reader, Zend\\Dom, Zend\\Serializer\\Wddx, and Zend\\Soap were
   not properly protecting against XXE injections; these situations have
   now been corrected.

Around *70* pull requests for a variety of features and bugfixes were handled
since beta5!',
  '2.0.0beta2' => 'RELEASE INFORMATION
---------------
Zend Framework 2.0.0beta2

THIS RELEASE IS A DEVELOPMENT RELEASE AND NOT INTENDED FOR PRODUCTION USE.
PLEASE USE AT YOUR OWN RISK.

This is the second in a series of planned beta releases. The beta release
cycle will follow the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen approximately every six weeks.

Once the established milestones have been reached and the featureset has
reached maturity and reasonable stability, we will freeze the API and
prepare for Release Candidate status.

NEW FEATURES IN BETA2
---------------------

- Refactored Mail component
  - Does not change existing Storage API, except:
    - Zend\\Mail\\MailMessage was moved to Zend\\Mail\\Storage\\MailMessage
    - Zend\\Mail\\MailPart was moved to Zend\\Mail\\Storage\\MailPart
    - Zend\\Mail\\Message was moved to Zend\\Mail\\Storage\\Message
    - Zend\\Mail\\Part was moved to Zend\\Mail\\Storage\\Part
  - Zend\\Mail\\Mail was renamed to Zend\\Mail\\Message
    - Encapsulates a mail message and all headers
    - MIME messages are created by attaching a Zend\\Mime\\Message object as the
      mail message body
  - Added Zend\\Mail\\Address and Zend\\Mail\\AddressList
    - Used to represent single addresses and address collections, particularly
      within mail headers
  - Added Zend\\Mail\\Header\\* and Zend\\Mail\\Headers
    - Representations of mail headers
  - Zend\\Mail\\Transport interface defines simply "send(Message $message)"
    - Smtp, File, and Sendmail transports rewritten to consume Message objects,
      and to introduce Options classes.
- Refactored Zend\\Cache
  - Completely rewritten component.
  - New API features storage adapters and adapter plugins for implementing cache
    storage and features such as serialization, clearing, and optimizing.
    - Current adapters include filesystem, APC, memcached, and memory.
    - All adapters can describe capabilities.
    - Plugins are implemented as event listeners.
  - New "Pattern" API created to simplify things like method, class, object, and
    output caching.
- MVC updates
  - Zend\\Module\\Manager was stripped of most functionality; it now simply
    iterates requested modules and triggers events.
  - Former manager functionality such as class loading and instantiation,
    "init()" triggering, configuration gathering, and autoloader seeding were
    moved to event listeners.
  - Post-module loading configuration globbing support was added.
    - Simplifies story of overriding module configuration.
  - Recommended filesystem no longer uses plurals for directory names.
  - Recommendations now include a chdir(__DIR__ . \'/../\') from the
    public/index.php file, and specifying configuration paths to be relative to
    application directory.

In addition, over 100 bug and feature requests were handled since beta1.',
  '2.0.4' => 'Zend Framework 2.0.4

Resolves the following issues and/or includes the following changes:

- 2808: Add serializer better inheritance and extension
  (https://github.com/zendframework/zf2/issues/2808)
- 2813: Add test on canonical name with the ServiceManager
  (https://github.com/zendframework/zf2/issues/2813)
- 2832: bugfix: The helper DateFormat does not cache correctly when a pattern is
  set. (https://github.com/zendframework/zf2/issues/2832)
- 2837: Add empty option before empty check
  (https://github.com/zendframework/zf2/issues/2837)
- 2843: change self:: with static:: in call-ing static property/method
  (https://github.com/zendframework/zf2/issues/2843)
- 2857: Unnecessary path assembly on return in
  Zend\\Mvc\\Router\\Http\\TreeRouteStack->assemble() line 236
  (https://github.com/zendframework/zf2/issues/2857)
- 2867: Enable view sub-directories when using ModuleRouteListener
  (https://github.com/zendframework/zf2/issues/2867)
- 2872: Resolve naming conflicts in foreach statements
  (https://github.com/zendframework/zf2/issues/2872)
- 2878: Fix : change self:: with static:: in call-ing static property/method()
  in other components ( all ) (https://github.com/zendframework/zf2/issues/2878)
- 2879: remove unused const in Zend\\Barcode\\Barcode.php
  (https://github.com/zendframework/zf2/issues/2879)
- 2896: Constraints in Zend\\Db\\Metadata\\Source\\AbstractSource::getTable not
  initalised (https://github.com/zendframework/zf2/issues/2896)
- 2907: Fixed proxy adapter keys being incorrectly set due Zend\\Http\\Client
  (https://github.com/zendframework/zf2/issues/2907)
- 2909: Change format of Form element DateTime and DateTimeLocal
  (https://github.com/zendframework/zf2/issues/2909)
- 2921: Added Chinese translations for zf2 validate/captcha resources
  (https://github.com/zendframework/zf2/issues/2921)
- 2924: small speed-up of Zend\\EventManager\\EventManager::triggerListeners()
  (https://github.com/zendframework/zf2/issues/2924)
- 2929: SetCookie::getFieldValue() always uses urlencode() for cookie values,
  even in case they are already encoded
  (https://github.com/zendframework/zf2/issues/2929)
- 2930: Add minor test coverage to MvcEvent
  (https://github.com/zendframework/zf2/issues/2930)
- 2932: Sessions: SessionConfig does not allow setting non-directory save path
  (https://github.com/zendframework/zf2/issues/2932)
- 2937: preserve matched route name within route match instance while
  forwarding... (https://github.com/zendframework/zf2/issues/2937)
- 2940: change \'Cloud\\Decorator\\Tag\' to \'Cloud\\Decorator\\AbstractTag\'
  (https://github.com/zendframework/zf2/issues/2940)
- 2941: Logical operator fix : \'or\' change to \'||\' and \'and\' change to \'&&\'
  (https://github.com/zendframework/zf2/issues/2941)
- 2952: Various Zend\\Mvc\\Router\\Http routers turn + into a space in path
  segments (https://github.com/zendframework/zf2/issues/2952)
- 2957: Make Partial proxy to view render function
  (https://github.com/zendframework/zf2/issues/2957)
- 2971: Zend\\Http\\Cookie undefined self::CONTEXT_REQUEST
  (https://github.com/zendframework/zf2/issues/2971)
- 2976: Fix for #2541 (https://github.com/zendframework/zf2/issues/2976)
- 2981: Controller action HttpResponse is not used by SendResponseListener
  (https://github.com/zendframework/zf2/issues/2981)
- 2983: replaced all calls to $this->xpath with $this->getXpath() to always
  have... (https://github.com/zendframework/zf2/issues/2983)
- 2986: Add class to file missing a class (fixes #2789)
  (https://github.com/zendframework/zf2/issues/2986)
- 2987: fixed Zend\\Session\\Container::exchangeArray
  (https://github.com/zendframework/zf2/issues/2987)
- 2994: Fixes #2993 - Add missing asterisk to method docblock
  (https://github.com/zendframework/zf2/issues/2994)
- 2997: Fixing abstract factory instantiation time
  (https://github.com/zendframework/zf2/issues/2997)
- 2999: Fix for GitHub issue 2579
  (https://github.com/zendframework/zf2/issues/2999)
- 3002: update master\'s resources/ja Zend_Validate.php message
  (https://github.com/zendframework/zf2/issues/3002)
- 3003: Adding tests for zendframework/zf2#2593
  (https://github.com/zendframework/zf2/issues/3003)
- 3006: Hotfix for #2497 (https://github.com/zendframework/zf2/issues/3006)
- 3007: Fix for issue 3001 Zend\\Db\\Sql\\Predicate\\Between fails with min and max
  ... (https://github.com/zendframework/zf2/issues/3007)
- 3008: Hotfix for #2482 (https://github.com/zendframework/zf2/issues/3008)
- 3009: Hotfix for #2451 (https://github.com/zendframework/zf2/issues/3009)
- 3013: Solved Issue 2857 (https://github.com/zendframework/zf2/issues/3013)
- 3025: Removing the separator between the hidden and the visible inputs. As
  the... (https://github.com/zendframework/zf2/issues/3025)
- 3027: Reduced #calls of plugin() in PhpRenderer using a cache mechanism
  (https://github.com/zendframework/zf2/issues/3027)
- 3029: Fixed the pre-commit script, missed the fix command
  (https://github.com/zendframework/zf2/issues/3029)
- 3030: Mark module as loaded before trigginer EVENT_LOAD_MODULE
  (https://github.com/zendframework/zf2/issues/3030)
- 3031: Zend\\Db\\Sql Fix for Insert\'s Merge and Set capabilities with simlar keys
  (https://github.com/zendframework/zf2/issues/3031)',
  '2.0.0rc3' => 'Release Version 2.0.0rc3 Tag
',
  '2.0.0beta3' => 'This is the third in a series of planned beta releases. The beta release
cycle will follow the "gmail" style of betas, whereby new features will
be added in each new release, and BC will not be guaranteed; beta
releases will happen approximately every six weeks.

Once the established milestones have been reached and the featureset has
reached maturity and reasonable stability, we will freeze the API and
prepare for Release Candidate status.

NEW FEATURES IN BETA3
---------------------

- Refactored Config component (Ben Scholzen, Artur Bodera, Enrico Zimuel, Evan Coury)
  - All readers moved under Zend\\Config\\Reader
    - JSON and YAML readers removed until beta4
    - New API:
      $xml = new Zend\\Config\\Reader\\Xml();
      $config = new Zend\\Config\\Config($xml->fromFile($filename);

      or:
      $xml     = new Zend\\Config\\Reader\\Xml();
      $config = $xml->fromFile($filename, true);

      or, simpler:
      $config = Zend\\Config\\Factory::fromFile($filename);
    - All constant injection removed from readers
      - New Processor API allows processing the retrieved configuration to do
        optional injection/manipulation of configuration values.
    - Ability to import other configuration files within a configuration file
      added.
  - Factory added, to simplify retrieving configuration from any configuration
    format supported.
- New View layer (Matthew Weier O\'Phinney)
  - View layer is now:
    - Models, for aggregating and representing data to render; models may be
      nested to represent complex view hierarchies
    - Renderers, which render templates, using either variables provided or
      Models
    - Resolvers, which resolve template names to resources a renderer may
      consume
    - View, which allows attaching strategies for determining the renderer to
      use, as well as how to inject the response when done.
  - Old Zend_View is now Zend\\View\\Renderer\\PhpRenderer
    - Composes a Resolver, a PluginBroker (for helpers), a Variables container
      (for aggregating variables to pass to the view script), and a FilterChain
      (for output filtering).
    - render() now accepts View\\Models
    - allows rendering stacks of templates under the same variable scope, via
      the addTemplate() mechanism
    - moves escaping to an Escape view helper; no auto-escaping is enabled
  - MVC integration
    - Strategy listeners for:
      - Handling and returning 404 pages
      - Handling and returning error pages due to exceptions
      - RAD support for creation and injection of view models from action
        controller return values
    - Addition of a "render" event, executing after "dispatch" and before
      "finish"
- New Db layer (Ralph Schindler)
  - Complete rewrite from the ground up.
  - New architecture features low-level drivers, which also provide access to
    the PHP resource being consumed; adapters, which provide basic abstraction
    for common CRUD operations; new SQL abstraction layer, with full predicate
    support; abstraction for ResultSet\'s, with the ability to cast rows to
    specific object types; abstraction for SQL metadata; and a revised Table and
    Row Data Gateway.
- New Zend\\Service\\AgileZen component (Enrico Zimuel)
  - Support for the full AgileZen (http://www.agilezen.com) API
  - Developed for use with http://framework.zend.com/zf2/board
- PHP 5.4 support
  - A number of issues when running ZF2 under PHP 5.4 were identified and
    corrected.
- Other components that received attention:
  - Zend\\GData (Maks3w)
  - Zend\\Navigation (Kyle Spraggs, Frank Brückner)
  - Zend\\Session (Mike Willbanks)
  - Zend\\Service\\Technorati (Maks3w)
  - Zend\\Service\\StrikeIron (Maks3w)
  - Zend\\Service\\Twitter (Maks3w)
  - Zend\\Http\\Header\\Accept* (Matthew Weier O\'Phinney, Enrico Zimuel)
    - Adds support for q priority, level identifiers, and wildcard media and
      submedia types
  - Zend\\Ldap (Maks3w, Stefah Gehrig)
  - Zend\\Oauth (bakura10)
  - Zend\\Mvc and Zend\\Module (Evan Coury, many others)

Around 200 pull requests for a variety of features and bugfixes were handled
since beta2.',
  '2.0.5' => 'Zend Framework 2.0.5

- 3004: Zend\\Db unit tests fail with code coverage enabled
  (https://github.com/zendframework/zf2/issues/3004)
- 3039: combine double if into single conditional
  (https://github.com/zendframework/zf2/issues/3039)
- 3042: fix typo \'consist of\' should be \'consists of\' in singular
  (https://github.com/zendframework/zf2/issues/3042)
- 3045: Reduced the #calls of rawurlencode() using a cache mechanism
  (https://github.com/zendframework/zf2/issues/3045)
- 3048: Applying quickfix for zendframework/zf2#3004
  (https://github.com/zendframework/zf2/issues/3048)
- 3095: Process X-Forwarded-For header in correct order
  (https://github.com/zendframework/zf2/issues/3095)',
  '2.0.0rc4' => 'Zend Framework 2.0.0rc4

This is the fourth release candidate for 2.0.0. We will be releasing RCs
on a weekly basis until we feel all critical issues are addressed. At
this time, we anticipate few API changes before the stable release, and
recommend testing your production applications against it.

17 August 2012

- Zend\\Db
  - RowGateway:  delete() now works; RowGateway objects now no longer duplicates
    the content internally leading to a larger than necessary memory footprint.
  - Adapter for PDO: fixed such that all calls to rowCount() will always be an
    integer; also fixed disconnect() from unsetting property
  - Zend\\Validator\\Db: fixed such that TableIdentifier can be used to promote
    schema.table identifiers
  - Sql\\Select: added reset() API to reset parts of a Select object, also
    includes updated constants to refer to the parts by
  - Sql\\Select and others: Added subselect support in Select, In Expression and
    the processExpression() abstraction for Zend\\Db\\Sql
  - Metadata: fixed various incorrect keys when refering to contstraint data in
    metadata value objects
- Zend\\Filter
  - StringTrim filter now properly handles unicode whitespace
- Zend\\Form
  - FieldsetInterface now defines the methods allowObjectBinding() and
    allowValueBinding().
  - New interface, FieldsetPrepareAwareInterface. Collection and Fieldset both
    implement this.
    - See https://github.com/zendframework/zf2/pull/2184 for details
  - Select elements now handle options and validation more consistently with
    other multi-value elements.
- Zend\\Http
  - SSL options are now propagated to all Socket Adapter subclasses
- Zend\\InputFilter
  - Allows passing ValidatorChain and FilterChain instances to the factory
- Zend\\Log
  - Fixed xml formatter to not display empty extra information
- Zend\\Loader
  - SplAutoloader was renamed to SplAutoloaderInterface (consistency issue)
- Zend\\Mvc
  - params() helper now allows fetching full parameter containers if no
    arguments are provided to its various methods (consistency issue)
- Zend\\Paginator
  - The DbSelect adapter now works
- Zend\\View
  - ViewModel now allows unsetting variables properly
- Security
  - Fixed issues in Zend\\Dom, Zend\\Soap, Zend\\Feed, and Zend\\XmlRpc with regards
    to the way libxml2 allows xml entity expansion from DOCTYPE entities when it
    is provided.

Around 50 pull requests for a variety of features and bugfixes were handled
since RC3, as well as almost 30 documentation changes!',
);
return $tags;