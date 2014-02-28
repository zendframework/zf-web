<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 0.9.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-02-28 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-02-28 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>Today, we're releasing version 0.9.0 of Apigility! You can grab and test it
using one of the following two methods:</p>
<ul>
    <li>Composer:
        <code>composer create-project zfcampus/zf-apigility-skeleton apigility 0.9.0</code>
    </li>
    <li>Manual download:
        <pre><code>
        wget https://github.com/zfcampus/zf-apigility-skeleton/releases/download/0.9.0/zf-apigility-skeleton-0.8.0.zip
        unzip zf-apigility-skeleton-0.9.0.zip
        </code></pre>
    </li>
</ul>

<p>This release brings in our last planned feature for the upcoming 1.0.0 stable release:
the ability to document your APIs, and then provide that documentation to your end-users!
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Documentation</h2>

<p>
    An API is useless without documentation.
</p>

<p>
    The Apigility admin UI now allows you to capture narrative documentation 
    for your services, collections, entities, and operations. You can document
    what the request and response bodies should look like. You can document
    each field you configure.
</p>

<p>
    Apigility then merges this documentation with what it knows of your services:
    what Accept headers are allowed, what Content-Types are allowed, what response
    status codes may be expected, what fields are available, whether or not 
    authorization is required, and more. The admin UI provides a visualization of
    this information for you.
</p>

<p>
    We provide several more visualizations, too.  The 
    <kbd>zf-apigility-documentation</kbd> module is enabled now by default in 
    the <kbd>zf-apigility-skeleton</kbd>, providing both JSON and HTML 
    representations of the documentation at the URI 
    <kbd>/apigility/documentation</kbd> (the representation will depend on the 
    <kbd>Accept</kbd> header value you provide -- Apigility's own content 
    negotiation at work!).
</p>

<p>
    You can also opt in to the new <kbd>zf-apigility-documentation-swagger</kbd>
    module, which will allow you to either seed an existing <a href="https://github.com/wordnik/swagger-ui">Swagger UI</a>
    installation, or, if you visit the <kbd>/apigility/swagger</kbd> URI, provide
    the Swagger UI itself.
</p>

<p>
    To see what's possible, <a href="http://apigility.org/get-started-video.html">Introduction to Documentation</a> 
    video on the Apigility website!
</p>


<h4>Adding documentation to existing Apigility installs</h4>

<p>
    If you are already using Apigility, you will need to add the new modules to
    your application. Add the following dependencies to your <kbd>composer.json</kbd>:
</p>

<ul>
    <li><kbd>"zfcampus/zf-apigility-documentation": "~1.0-dev"</kbd> (necessary 
    for any documentation visualization, other than in the admin)</li>
    <li><kbd>"zfcampus/zf-apigility-documentation-swagger": "~1.0-dev"</kbd> (if 
    you want the Swagger UI)</li>
</ul>

<p>
    After running <kbd>composer update</kbd>, add the modules to your `config/application.config.php`:
</p>

<ul>
    <li><kbd>ZF\Apigility\Documentation</kbd></li>
    <li><kbd>ZF\Apigility\Documentation\Swagger</kbd></li>
</ul>

<h2>Changelog</h2>

<p>
    This release has been a little over two months in the making, and has a ton of changes.
    The following is a list of important changes for existing users.
</p>

<ul>
<li>
<p><strong>Changes minimum supported PHP version to 5.3.23</strong>, in line with the upcoming
  ZF 2.3.0. We still recommend <strong>5.4.8</strong> for serving the admin user interface.</p>
</li>
<li>
<p>New modules, <a href="https://github.com/zfcampus/zf-apigility-documentation">zf-apigility-documentation</a> and
  <a href="https://github.com/zfcampus/zf-apigility-documentation-swagger">zf-apigility-documentation-swagger</a>,
  providing documentation visualizations of APIs created with Apigility. The
  base module provides both JSON and HTML visualizations via the URI
  <code>/apigility/documentation</code>, based on the Accept header value present.
  zf-apigility-documentation-swagger provides an additional JSON visualization
  for the mediatype <code>application/vnd.swagger+json</code>, for seeding a <a href="https://github.com/wordnik/swagger-ui">Swagger
  UI</a> installation; additionally, it
  provides the Swagger UI via <code>/apigility/swagger</code>.</p>
</li>
</ul>
<p>zf-apigility-documentation is enabled by default in zf-apigility-skeleton;
  zf-apigility-documentation-swagger is an opt-in module.</p>
<ul>
<li>
<p><strong>The <code>/admin</code> and <code>/welcome</code> routes are now removed!</strong> The admin UI now uses
  <code>/apigility/ui</code>, and the welcome screen uses <code>/apigility/welcome</code>. New routes
  for documentation are also available, as detailed above.</p>
</li>
<li>
<p>A new module was created for Apigility-specific interfaces,
  <a href="https://github.com/zfcampus/zf-apigility-documentation">zf-apigility-documentation</a>.
  The primary use case is for composition in modules that may or may not be
  consumed by Apigility (e.g., a general-purpose module that could be composed
  into many projects). The only interface currently is
  <code>ZF\Apigility\Provider\ApigilityProviderInterface</code>, which replaces
  <code>Zend\Apigility\ApigilityModuleInterface</code> (and thus prevents the necessity of
  installing all Apigility modules just to implement the interface!).</p>
</li>
<li>
<p>A new module was introduced for handling development mode, 
  <a href="https://github.com/zfcampus/zf-development-mode">zf-development-mode</a>;
  this is a fork of <a href="https://github.com/19ft/NFDevelopmentMode">NFDevelopmentMode</a>,
  which was based off the equivalent functionality in zf-apigility-skeleton's
  <code>Application</code> module. We removed the functionality from the skeleton, and
  added a dependency on the new module.</p>
</li>
<li>
<p>zf-apigility-skeleton's layout was updated to match that of the admin UI.</p>
</li>
<li>
<p>zf-apigility-admin received numerous updates:</p>
<ul>
<li>
<p>Ability to add documentation of services, fields, and operations.</p>
</li>
<li>
<p>Ability to use <a href="http://www.mongodb.org/">MongoDB</a> when configuring an
  OAuth2 authentication adapter.</p>
</li>
<li>
<p>Ability to inspect, add, configure, and delete zf-content-negotiation
  selectors.</p>
</li>
<li>
<p>Links to HTML documentation of APIs managed by the Apigility instance
  (more on this below).</p>
</li>
<li>
<p>Ability to create and manipulate filter chains for each field in a
  service.</p>
</li>
<li>
<p>(Limited) detection of whether or not an opcode cache is enabled; if
  detected, a modal dialog will be presented to the end-user detailing how
  to disable it.</p>
</li>
<li>
<p>Completely overhauled and refactored admin UI application to ease
  maintenance and feature additions. The admin UI now uses
  <a href="http://bower.io">Bower</a> for managing UI asset dependencies, and
  <a href="http://gruntjs.com">Grunt</a> for building the UI distribution. We have
  dropped ng-route for the <a href="https://github.com/angular-ui/ui-router">angular-ui
  ui-router</a>, providing us with
  more flexibility in UI implementation and layout. All services,
  controllers, and directives have been moved into their own files.</p>
</li>
<li>
<p>Countless UI/UX improvements.</p>
</li>
</ul>
</li>
<li>
<p>zf-apigility-welcome has been updated to use the Apigility "Rocket ElePHPant"
  logo for the splash screen, and to provide buttons to the HTML and Swagger
  documentation, if the appropriate modules are available.</p>
</li>
<li>
<p><strong>zf-rest and zf-rpc now each store a <code>service_name</code> key in the configuration
  for each service.</strong> While efforts have been made to ensure existing
  configuration still works, we recommend adding this key to each service. The
  value should be the short name representation for the service, usually the
  name you provided when creating the service.</p>
</li>
<li>
<p><strong>All repositories have been updated to make a clean distinction between the
  terms "Entity", "Collection", and "Resource".</strong> An "Entity" is anything
  addressable via a URI containing a unique identifier. A "Collection" is any
  URI that returns a set of entities. A "Resource" refers to a URI that may
  return collections and/or entities. As such, we have several BC breaks:</p>
<ul>
<li>
<p>The event <code>renderResource</code> is now <code>renderEntity</code>.</p>
</li>
<li>
<p>The event <code>renderCollection.resource</code> is now <code>renderCollection.entity</code>.</p>
</li>
<li>
<p><code>ZF\Hal\Resource</code> was renamed to <code>ZF\Hal\Entity</code>.</p>
</li>
<li>
<p>The subkey <code>resource</code> in the zf-mvc-auth configuration is now <code>entity</code>.</p>
</li>
<li>
<p>The subkey <code>resource_http_methods</code> in zf-rest is now
  <code>entity_http_methods</code>.</p>
</li>
<li>
<p>The subkey <code>resource_class</code> in zf-rest is now <code>entity_class</code>.</p>
</li>
<li>
<p>The subkey <code>resource_identifier_name</code> in zf-rest is now
  <code>entity_identifier_name</code>. (This change only affects those who have been
  using latest master, but have not updated since late-January 2014.)</p>
</li>
<li>
<p>The subkey <code>identifier_name</code> in zf-apigility <code>db-connected</code> configuration
  is now <code>entity_identifier_name</code>;</p>
</li>
</ul>
</li>
<li>
<p>zf-hal now properly differentiates between the identifier used in the route
  definition, and the identifier used for the entity; this allows you to use one
  value on the uri -- e.g., <code>status_id</code> -- and another in your entity class --
  e.g., <code>id</code>. zf-hal will fallback to the <code>route_identifier_name</code> if no
  <code>entity_identifier_name</code> is present.</p>
</li>
<li>
<p>zf-apigility, when detecting an input filter is present, will pull values from
  the input filter, and not use any other values even if provided in the
  request. This prevents SQL errors due to unknown columns.</p>
</li>
</ul>
<p>Additionally, zf-apigility's assets were updated, and a Grunt + Bower
  toolchain provided for keeping them up-to-date.</p>
<ul>
<li>zf-rest, when detecting an input filter is present for the current request,
  will inject it into the <code>ResourceEvent</code>, allowing developers to retrieve it
  via <code>$this-&gt;getEvent()-&gt;getInputFilter()</code>. </li>
</ul>
<p>Additionally, support for <code>patchList</code> was added to the
  <code>AbstractResourceListener</code>.</p>
<ul>
<li>zf-api-problem was updated to match <a href="http://tools.ietf.org/html/draft-nottingham-http-problem-05">Problem
  API draft 5</a>.
  This has changed the internal structure and JSON representation of problem
  results. If you were manipulating <code>ApiProblem</code> objects directly previously,
  you may need to alter your code.</li>
</ul>

<h2>Future</h2>

<p>
    At this point, we turn our attention to stabilizing Zend Framework 2.3.0, 
    on which Apigility will depend, due to features added to that upcoming
    version.
</p>

<p>
    Once Zend Framework 2.3.0 is released, we will begin the beta cycle for
    Apigility 1.0.0. During that timeframe, we will due some additional improvements
    to the UI, and work to ensure the engine is stable. Additionally, we will
    document the project, providing documentation for each module, as well as
    for how the modules work together as a whole. We hope to provide "recipes"
    for a number of common practices and development and deployment situations.
</p>

EOC;
$post->setExtended($extended);

return $post;
