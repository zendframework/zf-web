<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.0beta1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-03-23 10:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-03-23 10:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    We are pleased to announce the immediate availability
    of Apigility 1.0.0beta1!
</p>

<ul>
    <li>
        <a href="http://apigility.org/download">http://apigility.org/download</a>
    </li>
</ul>

<p>
    This is our first beta release of Apigility, marking its initial API 
    stability, and providing a solid preview of what to expect for the first 
    stable release.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>What is Apigility?</h2>

<p>
    Apigility is the world's easiest way to create and provide secure, well-formed
    APIs.
</p>

<p>
    Apigility provides tools for describing and documenting your APIs, both
    RESTful and RPC. You can indicate the URL that provides a service, what
    HTTP methods are allowed, what representations (e.g., JSON, HTML, XML)
    can be provided, how many items to present per page of a collection, and
    more.
</p>

<p>
    We make choices so <em>you</em> don't have to. We have standardized on 
    JSON for RPC services, and <a href="http://tools.ietf.org/html/draft-kelly-json-hal-06">Hypermedia Application Language (HAL)</a>, 
    using the JSON variant, for REST services. We provide robust error handling,
    using <a href="http://tools.ietf.org/html/draft-nottingham-http-problem-06">Problem Details for HTTP APIs (API Problem)</a>.
    HTTP method negotiation and content negotiation are built in, ensuring that
    problems are reported early and provide detail on how to submit correct
    requests.
</p>

<p>
    You can document what fields can be submitted, and configure how those
    fields will be validated. You can indicate what services require an
    authenticated user - or even restrict usage based on the HTTP method!
    You can configure how users can authenticate, and we provide HTTP Basic,
    HTTP Digest, and OAuth2 authentication out-of-the-box.
</p>

<p>
    An API is only as useful as its documentation. Apigility lets you document
    every service, every HTTP method, and even differentiate between collections
    and entities. We provide both HTML and JSON documentation by default, and
    have a separate <a href="http://swagger.wordnik.com/">Swagger UI</a> 
    implementation you can opt-in to if desired. Alternately, you can write your
    own module for exporting the documentation in your own custom format -
    we hope to provide both API Blueprint and RAML in the future!
</p>

<p>
    You can use the full Apigility skeleton to create APIs, and the Admin UI
    for manipulating them. Alternately, you can opt-in to just the modules you
    are interested in, and configure them by hand for optimal control over how
    they all work and interact.
</p>

<p>
    In short, Apigility is the most powerful tool you can use for creating
    robust APIs.
</p>

<h2>What has changed for beta1?</h2>

<p>
    In the three weeks since we released 0.9.1, we've been quite busy. Among other
    things, we worked hard to stabilize and release Zend Framework 2.3.0, which
    allows us to now pin Apigility to a stable version of the framework. This has
    reduced the package size from over 100MB to around 20MB - a reduction of 80%!
</p>

<p>
    Additionally, we've worked hard to fix a number of lingering issues in an
    effort to stabilize the Apigility engine and streamline the Admin UI 
    experience. The following is a list of changes.
</p>

<h3>New Features</h3>

<p>
    All Apigility modules were updated to use a <a href="https://github.com/php-fig/fig-standards/blob/master/proposed/psr-4-autoloader/psr-4-autoloader.md">PSR-4</a>
    structure and autoloader. This flattens the packages significantly, and also
    allows simplification of the PHPUnit test runner. A PSR-4 variant of the ZF2
    <code>StandardAutoloader</code>, <code>ZF\Apigility\Autoloader</code>, was created to provide true
    PSR-4 autoloading, including the ability to have underscores (<code>_</code>) in class
    names, which has been a common feature request. ZF2 <code>Module</code> classes created for
    new API modules now use the new autoloader for loading classes inside the
    module.
</p>

<p>
    All modules were added to <a href="https://travis-ci.org/">Travis-CI</a>, giving us continuous integration going
    forward.
</p>

<p>Additionally, the following features were added:</p>

<ul>
<li><a href="https://github.com/zfcampus/zf-content-validation/issues/8">zfcampus/zf-content-validation#8</a> adds the ability to provide HTTP method-specific input
  filters. This feature is not yet integrated into the Apigility Admin UI, but
  can be configured manually. To do so, add method/input filter service name
  pairs for the given controller service name; if no method-specific input
  filter exists, zf-content-validation will fallback to the <code>input_filter</code> key,
  if defined. As an example:
<pre><code>
    'zf-content-validation' => array(
        'Example\V1\Rest\Status\Controller' => array(
            // This is the fallback input filter, and the one the UI
            // can define and manipulate:
            'input_filter' => 'Example\V1\Rest\Status\Validator',
            // This one will be used on POST requests only:
            'POST' => 'Example\V1\Rest\Status\NewStatusValidator',
        ),
    ),
</code></pre>
</li>

<li><a href="https://github.com/zfcampus/zf-mvc-auth/issues/20">zfcampus/zf-mvc-auth#20</a>
  provides a patch that injects the <code>MvcEvent</code> with a new key,
  <code>ZF\MvcAuth\Identity</code>. You can pull the discovered identity from this event
  parameter now. Additionally, in REST resources, calling <code>$this->getIdentity()</code>
  will retrieve the identity.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/124">zfcampus/zf-apigility-admin#124</a> and
  <a href="https://github.com/zfcampus/zf-apigility-admin/issues/129">zfcampus/zf-apigility-admin#129</a>
  provide initial input filters for all Apigility Admin API services, as well as
  UI integration for reporting errors. All validation errors are caught and
  reported in a single dialog within the form that raises them.
</li>

<li>The "edit settings" screen for REST services now allows editing the entity
  class and collection class names.
</li>

<li>The "API Overview" page now links services to their overviews. The service
  description is displayed beneath each service; if not yet defined, a link to
  the "edit documentation" tab for the service is provided.
</li>

<li>A new modal will be displayed to users of the Apigility Admin UI if the API
  detects that the filesystem is not writable. The modal details what changes
  need to be made in order for the UI and API to work correctly.
</li>

<li><a href="https://github.com/zfcampus/zf-oauth2/issue/30">zfcampus/zf-oauth2#30</a> splits
  out initialization of the <code>oauth2-server-php</code> server from the <code>zf-oauth2</code>
  controller, allowing the ability to replace it, write a delegator for it, etc.
</li>
</ul>

<h3>Breaking Changes</h3>

<ul>
<li><a href="https://github.com/zfcampus/zf-content-validation/issues/10">zfcampus/zf-content-validation#10</a>
  changes the key used by the <code>InputFilterAbstractServiceFactory</code> from
  <code>input_filters</code> to <code>input_filter_specs</code>. This is due to the fact that ZF 2.3.0
  introduces an <code>InputFilterManager</code>, which is already consuming the key
  <code>input_filters</code>. Wrapped in this change is the fact that the
  <code>InputFilterAbstractServiceFactory</code> is now registered as an abstract service
  factory with the <code>InputFilterManager</code>, instead of with the application service
  manager instance.
  <br /><br />
  For those updating their Apigility libraries to 1.0.0beta1, edit your
  <code>module.config.php</code> files to rename the <code>input_filters</code> key to
  <code>input_filter_specs</code>.
</li>

<li>The <code>zf-configuration</code> controller <code>ZF\Configuration\Controller</code> was moved into
  <code>zf-apigility-admin</code>. This URI for the service remains the same, but the
  controller itself has moved. (This change was done to consolidate all Admin
  API controllers in the same module, as well as to reduce the dependencies
  needed in the <code>zf-configuration</code> component.)
</li>
</ul>

<h3>Fixes</h3>

<ul>
<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/115">zfcampus/zf-apigility-admin#115</a> - Ensures
  that non-SQLite PDO OAuth2 adapters may be provided without error.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/117">zfcampus/zf-apigility-admin#117</a> - Ensure
  that the <code>route_match</code> is passed to the API when saving an RPC service.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/118">zfcampus/zf-apigility-admin#118</a> - Ensure
  that the Content Negotiation <code>selector</code> is passed to the API correctly when
  saving an RPC service.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/120">zfcampus/zf-apigility-admin#120</a> - Remove
  duplicate call to initialize the <code>ServerUrl</code> helper.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/122">zfcampus/zf-apigility-admin#122</a> and
  <a href="https://github.com/zfcampus/zf-apigility-admin/issues/123">zfcampus/zf-apigility-admin#123</a> - Add checks
  for array keys before accessing them when building the documentation graph for
  a given service operation.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/126">zfcampus/zf-apigility-admin#126</a> - Updates 
  the admin to pass the <code>X-UA-Compatible</code> meta tag in order to provide Internet
  Explorer compatibility.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/132">zfcampus/zf-apigility-admin#132</a> - Ensures
  that authorization data is fetched each time a new service is created, a
  service is updated, or a service is deleted, ensuring the table reflects the
  current list of available services and HTTP methods.
</li>

<li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/133">zfcampus/zf-apigility-admin#133</a> - Updates
  the "angular-flash" functionality to anchor flash messages to the bottom of
  the window. Additionally, any error flash messages now have a "close" button,
  requiring user intervention for dismissal.
</li>

<li>Many fixes were made to the UI to improve performance, remove UI refresh
  errors, provide more consistent color schemes, ensure tabs stay focussed
  between state transitions, etc.
</li>

<li>The Apigility Admin API was updated to break the authentication service into
  more granular sub-services, one for each type of authentication supported.
  This simplifies validation, and allows for future expansion.
</li>

<li>Work was done to ensure opcode cache detection is as solid as possible. We now
  properly distinguish between APC and APCu, allowing the latter to be enabled
  when using the Admin API.
</li>

<li><code>zf-apigility-documentation</code> was not correctly aggregating RPC documentation;
  this has been fixed.
</li>

<li>We reviewed the various events triggered to ensure that they were happening in
  the correct order, which we defined as:
  <ul>
    <li>Authentication</li>
    <li>HTTP method negotiation (is the method called allowed for the service?)</li>
    <li>Authorization (is the discovered identity allowed to perform the requested action?)</li>
    <li>Content Negotiation (determine incoming Content-Type and marshal data from request body; determine if Accept and/or Content-Type are valid for the request)</li>
    <li>Content Validation</li>
  </ul>

  Several event listener priorities were updated to fit the above requirements.
  A new listener, <code>ZF\Rest\Listener\OptionsListener</code>, was introduced to handle
  HTTP method negotiation for REST services, and is registered at the same
  priority as the RPC <code>OptionsListener</code> (which previously existed).
</li>

<li><code>zf-configuration</code> was updated to never write configuration using short-array
  notation; this was done to ensure compatibility of generated configuration
  with PHP 5.3 (as developers may use the admin API via 5.4, but deploy to 5.3).
</li>

<h2>Roadmap</h2>

<p>
    We're excited to get a stable release of Apigility as soon as we possibly
    can. To that end, we plan to do a beta release weekly until it's ready.
    <strong>During that time, we will be working primarily on documentation and critical
    bugfixes</strong>. We hope to have a stable release within a month.
</p>

<p>
    Reaching stability is only the first step, however! We already have contributors
    making significant headway on features such as "Doctrine-Connected" and
    "Mongo-Connected" REST services, and we will be debuting these in a 1.1 version
    not long after we reach version 1.0. Stay tuned!
</p>
EOC;
$post->setExtended($extended);

return $post;
