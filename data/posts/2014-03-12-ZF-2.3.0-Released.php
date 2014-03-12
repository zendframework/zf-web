<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.3.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-03-12 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-03-12 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.3.0!
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is our first minor release in 10 months, providing the first new features
    since May of 2013.
</p>

<p>
    Among those features, we've updated our minimum supported PHP version to 5.3.23,
    fixed a large number of issues with how form collections work, improved performance
    of the service manager, and much, much more.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>New minimum supported PHP version</h2>

<p>This release ups the minimum required PHP version from 5.3.3 to <strong>5.3.23</strong>.
Making this change affords the following:</p>

<ul>
    <li>
        <p>5.3.9 and up have a fix that allows a class to implement multiple interfaces
        that define the same method, so long as the signatures are compatible. Prior
        to that version, doing so raised a fatal error. This change is necessary in
        order to solve a problem with separated interface usage in the framework.</p>
    </li>
    <li>
        <p>5.3.23 contains a fix for <a href="https://bugs.php.net/bug.php?id=52861">PHP bug #62672</a>.
        Adopting this version or greater will allow us to (eventually) remove polyfill
        support that works around the symptoms of that issue.</p>
    </li>
</ul>

<h2>New Additions / Improvements</h2>

<p>
    More than 230 pull requests and issues were closed for this release -- far 
    too many to list individually. That said, there are quite a few incremental improvements
    that will be of interest to Zend Framework 2 users. Below is a list broken down
    by component.
</p>

<h3>Zend\Authentication</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4815">#4815</a> promotes <code>Zend\AuthenticationService\Adapter\Http</code>'s
  <code>_challengeClient()</code> method to public visibility, and renames it to
  <code>challengeClient()</code>; the old method remains as a proxy to the new one. This
  allows implementors to issue the HTTP credential challenge manually.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5901">#5901</a> adds an <code>AuthenticationServiceInterface</code>, to allow
  alternate implementations.</p>
</li>
</ul>

<h3>Zend\Cache</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4512">#4512</a> introduces a <code>BlackHole</code> cache storage adapter; this
  adapter is useful during development, when you do not want cache operations to
  have effect, but need to test that a system using caching works.</p>
</li>
<li>
<p><code>Zend\Cache\Storage\Adapter\Apc</code> now supports "check and set" operations, per
  <a href="https://github.com/zendframework/zf2/issues/4844">#4844</a>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5829">#5829</a> adds a new cache adapter, <code>Memcache</code> (not to be
  confused with <code>Memcached</code>), for use with <code>ext/memcache</code>.</p>
</li>
</ul>

<h3>Zend\Code</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4989">#4989</a> adds the ability to identify PHP traits in the
  <code>TokenArrayScanner</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/6262">#6262</a> adds a <code>getPrototype()</code> method to <code>MethodReflection</code>;
  this returns a structured array detailing the namespace, class, visibility,
  and arguments (including names, default values, and types) for the method.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5400">#5400</a> adds the capability for the <code>PropertyScanner</code> to
  determine the PHP type of a given object property, via the new method
  <code>getValueType()</code>.</p>
</li>
</ul>

<h3>Zend\Config</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4824">#4824</a> adds a <code>JavaProperties</code> configuration reader.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4860">#4860</a> provides an abstract factory for retrieving named
  top-level configuration keys from the <code>Config</code> service. As an example, if you
  have a key <code>zf-apigility</code>, you can now retrieve it from the service manager
  using <code>config-zf-apigility</code> or <code>zf-apigility-config</code>. Namespaces are also
  often-used for top-level keys, and notations such as <code>ZF\Apigility\Config</code> may
  be used, too.</p>
</li>
<li>
<p>A number of improvements were made to the <code>PhpArray</code> config writer to make the
  output it generates more readable, as well as more consistent with the values
  being passed as input. These include consistent 4-space indentation; putting
  the opening <code>array</code> declarations on the same line as <code>=&gt;</code> operators; ensuring
  boolean values are written as booleans; ensuring strings are written with
  proper, and readable, escapeing; allowing writing arrays using PHP 5.4
  short-array syntax; and making attempts to replace paths using <code>__DIR__</code>
  notation when possible.</p>
</li>
</ul>

<h3>Zend\Console</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4449">#4449</a> moves the console routing logic out of <code>Zend\Mvc</code> and
  into <code>Zend\Console\ConsoleRouteMatcher</code>. This allows re-use of the
  <code>Zend\Console</code> component in a standalone fashion.
  <code>Zend\Mvc\Router\Console\Simple</code> was refactored to consume a
  <code>ConsoleRouteMatcher</code> instance internally.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4606">#4606</a> adds support for <code>Zend\Console</code> to detect the console
  encoding, and use that when emitting text.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5711">#5711</a> implements the <code>writeTextBlock()</code> method in the
  <code>AbstractAdapter</code>, allowing the ability to specify a block size and text to
  wrap within that block when generating console output.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5720">#5720</a> fixes console routing to ensure CamelCase values in
  routes will be treated as literals, and ALLCAPS can be used to define value
  parameters.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5713">#5713</a> adds the ability to specify option callback hooks in
  <code>Zend\Console\Getopt</code>. As examples:</p>
</li>
</ul>

<pre><code>
$opts-&gt;setOptionCallback('apple' function ($value, $opts) {
      echo "You want a $value apple!\n";
});
</code></pre>

<p>Essentially, once <code>parse()</code> is called, if the specified option was provided,
  the callback will be triggered. Returning a boolean <code>false</code> will cause cause
  <code>parse()</code> to invalidate usage, raising an exception.</p>

<h3>Zend\Crypt</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5024">#5024</a> removes the <code>KEY_DERIV_HMAC</code> constant, and allows the
  ability to specify alternate PBKDF2 hashing algorithms within the
  <code>Zend\Crypt\BlockCipher</code> class.</li>
</ul>

<h3>Zend\Db</h3>
<ul>
<li><code>Zend\Db\Sql</code> with MySQL can utilize a <code>Select</code> object containing an <code>OFFSET</code> without <code>LIMIT</code></li>
<li><code>Zend\Db\Sql</code>'s <code>In</code> predicate now supports subselects</li>
<li><code>Zend\Db\Sql</code> now has a <code>NotIn</code> predicate.</li>
<li>A method <code>inTransaction()</code> has been added to all <code>Zend\Db\Adapter</code> drivers</li>
<li><code>Zend\Db\Sql\Select</code>'s <code>from()</code> can be a subselect</li>
<li><code>Zend\Db\Sql\Insert</code> can use a Select object as the value source <code>(INSERT INTO ... SELECT)</code></li>
<li><code>Zend\Db\Adapter</code> PDO now accepts a charset when creating the DSN</li>
</ul>

<h3>Zend\Dom</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5356">#5356</a> provides a backwards-compatible rewrite of the
  <code>Zend\Dom\Query</code> component and logic. It presents a new class,
  <code>Zend\Dom\Document</code>, along with a subcomponent of the same name containing new
  <code>Query</code> and <code>Nodelist</code> classes. Usage becomes:</li>
</ul>

<pre><code>
use ZendDomDocument;
$document = new Document($htmlXmlOrFile, $docType, $encoding);
foreach (DocumentQuery($expression, $document, $xpathOrCssQueryType) as $match) {
    // do something with matching DOMNode
}
// More concretely:
$document = new Document($someHtml, 'DOC_HTML', 'utf-8');
foreach (DocumentQuery('img.current', $document, 'TYPE_CSS') as $match) {
    $source = $document-&gt;attributes-&gt;getNamedItem('src');
}
</pre></code>

<p><code>Zend\Dom\Query</code> and <code>Zend\Dom\Css2Xpath</code> have been deprecated in favor of the
  new API. <code>Zend\Test\PHPUnit</code> still needs to be updated to use the new API,
  however.</p>

<h3>Zend\EventManager</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5283">#5283</a> deprecates the <code>ProvidesEvents</code> trait in favor of the
  <code>EventManagerAwareTrait</code>; the latter is named after the interface it
  implements, and includes the now standard <code>Trait</code> suffix.</li>
</ul>

<h3>Zend\Filter</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5436">#5436</a> refactors <code>Zend\Filter</code> to ensure consistency
  throughout the component. Filters now never trigger errors or throw
  exceptions; if a filter cannot handle an incoming input, it will return it
  unmodified.</li>
</ul>

<h3>Zend\Form</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4400">#4400</a> allows you to pass the string name of the element you
  want to create as the second argument when using
  <code>Zend\Form\FormElementManager::get()</code> - instead of requiring that you pass it
  in as <code>array('name' =&gt; 'name value')</code>.</p>
</li>
<li>
<p>The <code>Zend\Form</code> component has had a number of improvements surrounding HTML
  escaping and form labels. Among these is the addition of
  <code>LabelAwareInterface</code>, which defines methods for an element or fieldset
  to provide a label, label attributes, and label options (one of which is the
  option <code>disable_html_escape</code>, allowing developers to provide markup within the
  label text). Many efforts have been made to keep this functionality backwards
  compatible, while simultaneously ensuring that proper defaults are provided.</p>
</li>
<li>
<p>Numerous improvements were made to how form Collections are managed, including
  improvements to counts, managing input filters, handling nested sets, binding
  objects, and more.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5918">#5918</a> ensures that multiple CSRF elements on the same page
  with the same name should not conflict, and still validate.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4846">#4846</a> adds the ability to disable the <code>InArray</code> validator
  when defining a <code>MultiCheckbox</code> form element.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4884">#4884</a> provides the ability to replace elements within a form
  collection.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4927">#4927</a> adds the ability to provide a <code>Traversable</code> value to a
  nested fieldset in a form.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4971">#4971</a> updates the form factory to allow specifying <code>null</code>
  configuration values. This allows one module to override and cancel the
  setting of another when desired.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5420">#5420</a> adds the ability to compose <code>Zend\Form</code> collections via
  annotations.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5456">#5456</a> adds the ability for annotations to provide input
  filter specifications when provided on an object representing a fieldset.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5562">#5562</a> adds the <code>unsetValueOption()</code> method to <code>Select</code> and
  <code>MultiCheckbox</code> element types.</p>
</li>
</ul>

<h3>Zend\Http</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4950">#4950</a> adds <code>match()</code> capabilities to the <code>ContentType</code>
   header class, similar to the implementation for <code>Accept</code> header instances.
   This allows matching incoming data against a mimetype in order to perform
   content negotiation.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5029">#5029</a> adds a new header class for <code>Origin</code> headers.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5316">#5316</a> adds a new header class for <code>Content-Security-Policy</code>
  headers.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5732">#5732</a> adds the ability to set custom HTTP response status
  codes via a new <code>Response</code> method, <code>setCustomStatusCode()</code>.</p>
</li>
</ul>

<h3>Zend\I18n</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4510">#4510</a> introduces <code>Zend\I18n\Filter\NumberParse</code>, which will
  filter a string parseable by PHP's built-in <code>NumberFormatter</code> to a number.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5034">#5034</a> makes the <code>PhoneNumber</code> validator <code>Locale</code>-aware.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5108">#5108</a> introduces a <code>TranslatorInterface</code>, defining the
  methods <code>translate()</code> and <code>translatePlural()</code>. This will allow for alternate
  implementations, but also for other components to create equivalent,
  component-specific interfaces, and thus reduce dependencies.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5825">#5825</a> adds a new translation loader, <code>PhpMemoryArray</code>. It
  behaves like the <code>PhpArray</code> loader, but instead of accepting a file that
  returns an array, it accepts an array of translations directly. This allows
  specifying translations as part of configuration, or via a caching system.</p>
</li>
</ul>

<h3>Zend\InputFilter</h3>
<ul>
<li>A number of updates were made regarding how collection input filters work to
  ensure they are more consistent, and operate according to user expectations
  with regard to empty sets, nested sets, etc.</li>
</ul>

<h3>Zend\Json</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5933">#5933</a> provides the ability to use arbitrary response codes
  with <code>Zend\Json\Server</code>. </li>
</ul>

<h3>Zend\Loader</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5783">#5783</a> fixes the <code>StandardAutoloader</code> such that if a
  namespace matches, but no matching class is found, it will continue to loop
  through any other namespaces present. This fixes a situation whereby a map for
  a subnamespace may be registered later than the parent; prior to the change,
  the subnamespace would never be matched.</li>
</ul>

<h3>Zend\Log</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4455">#4455</a> adds new service providers for <code>Zend\Log</code>: <code>log_writers</code> and <code>log_processors</code>. These allow you to provide custom log writer and processor services for use with the <code>Zend\Log\LoggerAbstractServiceFactory</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4742">#4742</a> provides a new interface,
  <code>Zend\Log\LoggerAwareInterface</code>, for hinting that an object composes, or can
  compose, a <code>Zend\Log\Logger</code> instance. A corresponding PHP Trait is also
  provided.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5875">#5875</a> adds a <code>registerFatalErrorShutdownFunction()</code> method to
  <code>Zend\Log\Logger</code>, to handle logging fatal runtime errors.</p>
</li>
</ul>

<h3>Zend\Mail</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5261">#5261</a> adds a new <code>NullTransport</code> to <code>Zend\Mail</code>, providing a
  no-op mail transport. This can be useful in non-production environments, or
  when needing to selectively disable mail sending capabilities without altering
  code.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5470">#5470</a> adds <code>Zend\Mail\Transport\Factory</code>, for simplifying
  creation of a mail transport via configuration.</p>
</li>
</ul>

<h3>Zend\Mvc</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4849">#4849</a> updates <code>Zend\Mvc\Application::run()</code> such that it now
  always returns the <code>Application</code> instance. If an event returns a response
  object, it is always pushed into the <code>Application</code> instance now so that it may
  be retrieved after <code>run()</code> has finished executing.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4962">#4962</a> modifies the various MVC factories to reference the
  service <code>ControllerManager</code> instead of <code>ControllerLoader</code> (which is a legacy
  name from early beta releases); <code>ControllerManager</code> was made an alias of
  <code>ControllerLoader</code>. This change future-proofs the MVC. If you are using
  <code>ControllerLoader</code> in your own code, we encourage you to change those
  references to <code>ControllerManager</code> (though <code>ControllerLoader</code> will continue to
  work for the foreseeable future).</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5108">#5108</a> introduces a <code>DummyTranslator</code>, which will be used if
  <code>ext/intl</code> is not present, or if the developer wishes to disable translation
  (e.g., validators compose a translator by default, but quite often the
  validation messages do not need to be translated); translation can be disabled
  by setting the <code>translator</code> configuration key to a boolean <code>false</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5469">#5469</a> adds a new <code>AbstractConsoleController</code>, and logic in
  the <code>ControllerManager</code> for injecting the <code>ConsoleAdapter</code> object into such
  controllers. This abstract class tests if the incoming request is a console
  request, and raises an exception if not; it also provides a <code>getConsole()</code>
  method for access to the composed <code>ConsoleAdapter</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5612">#5612</a> updates <code>Zend\Mvc\Application::init()</code> to allow
  listeners specified in the configuration passed to the method to override
  those discovered during bootstrapping; in essence, application-level
  configuration should have more specificity than module-level configuration.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5670">#5670</a> provides the ability to create a <code>controller_map</code>
  within <code>view_manager</code> configuration. This map allows you to do the following:</p>
</li>
<li>
<p>Indicate modules that include subnamespaces in their name to include all
    namespace segments in the template name: <code>Xerkus\FooModule =&gt;
    xerkux/foo-module/</code> via the configuration <code>Xerkus\FooModule =&gt; true</code>.</p>
</li>
<li>Map a specific template prefix to a given module: <code>ZfcUser =&gt;
    'zf-commons/zfc-user</code>.</li>
</ul>
<p>This change is opt-in, and thus backwards compatible.</p>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5759">#5759</a> adds a new method to the <code>FlashMessenger</code>,
  <code>renderCurrent()</code>, allowing you to render flash messages sent in the current
  request (using the same API as <code>renderMessages()</code>).</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5897">#5897</a> adds a <code>fromJsonRawBody()</code> method to the <code>Params</code>
  plugin, allowing the ability to decode and retrieve parameters passed via the
  request body as JSON.</p>
</li>
</ul>

<h3>Zend\Navigation</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5080">#5080</a> fixes the <code>Breadcrumb</code> view helper such that it will
  now pass the specified separator.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5803">#5803</a> hides sub menus when all pages in the sub menu are
  currently hidden.</p>
</li>
</ul>

<h3>Zend\Paginator</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4427">#4427</a> adds the ability to provide <code>$group</code> and <code>$having</code> clauses to a <code>DbTableGateway</code> <code>Zend\Paginator</code> adapter.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5272">#5272</a> adds a new <code>Callback</code> pagination adapter; the new
  adapter accepts two callbacks, one for returning the items, another for
  returning the count. The items callback will receive the requested offset and
  number of items per page as arguments: <code>function ($offset, $itemsPerPage)</code>.</p>
</li>
</ul>

<h3>Zend\Permissions\Acl</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/5628">#5628</a> adds a new <code>AssertionAggregate</code>, which enables two
  concepts: the ability to chain multiple assertions, as well as the ability to
  use named assertions as plugins. (The change also creates a
  <code>Zend\Permissions\Acl\Assertion\AssertionManager</code>, which is a plugin manager
  implementation).</li>
</ul>

<h3>Zend\ServiceManager</h3>
<ul>
<li>A number of performance improvements were made to how abstract factories are
  processed and invoked.</li>
</ul>

<h3>Zend\Session</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/4995">#4995</a> adds the ability to specify session validators in
  configuration consumed by the <code>SessionManagerFactory</code>.</li>
</ul>

<h3>Zend\Soap</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5792">#5792</a> adds a "debug mode" to <code>Zend\Soap\Server</code>. When
  enabled, any exception thrown is treated as a <code>Fault</code> response (vs. only those
  whitelisted).</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5810">#5810</a> adds a <code>getException()</code> method to <code>Zend\Soap\Server</code>,
  allowing you to retrieve the exception that caused a fault response (e.g., to
  log it).</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5811">#5811</a> creates a public <code>getSoap()</code> method in
  <code>Zend\Soap\Server</code> to allow you to access the composed <code>SoapServer</code> instance.
  This allows you to use <code>setReturnResponse()</code> and still return fault responses
  (which must be triggered by the <code>SoapServer</code> instance directly.)</p>
</li>
</ul>

<h3>Zend\Stdlib</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4534">#4534</a> introduces a <code>JsonSerializable</code> polyfill, to provide
  support for that built-in PHP interface on PHP versions prior to 5.4.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4751">#4751</a> provides a new interface,
  <code>Zend\Stdlib\Hydrator\HydratorAwareInterface</code>, for hinting that an object
  composes, or can compose, a <code>Zend\Stdlib\Hydrator\HydratorInterface</code> instance.
  A corresponding PHP Trait is also provided.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4908">#4908</a> segregates <code>Zend\Stdlib\Hydrator\HydratorInterface</code>
  into two separate interfaces, <code>Zend\Stdlib\Extractor\ExtractionInterface</code> and
  <code>Zend\Stdlib\Hydrator\HydrationInterfac</code>. The original interface has been
  modified to extend both of the new interfaces. This allows developers to
  implement one or the other behavior, based on the needs of the application.
  (As an example, if an application only needs to extract data for
  serialization, it could typehint on
  <code>Zend\Stdlib\Extractor\ExtractionInterface</code> only.)</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5364">#5364</a> adds a new subcomponent to hydrators, <code>NamingStrategy</code>.
  A <code>NamingStrategy</code> can be used by hydrators to determine the name to use for
  keys and properties when extracting and hydrating.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5365">#5365</a> adds <code>Zend\Stdlib\Guard</code>, which provides traits for
  performing common argument type validations. For example, an object composing the
  <code>ArrayOrTraversableGuardTrait</code> could call
  <code>$this-&gt;guardForArrayOrTraversable($arg)</code> in order to validate <code>$arg</code> is an
  array or <code>Traversable</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5380">#5380</a> adds context support to hydrator strategies, allowing
  them to receive the object being extracted or the array being hydrated when
  performing their logic.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5702">#5702</a> moves <code>Zend\Mvc\Router\PriorityList</code> into
  <code>Zend\Stdlib</code>, as it has general-purpose use cases. The former class was
  modified to extend the latter.</p>
</li>
</ul>

<h3>Zend\Test</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4946">#4946</a> adds two new methods to the
  <code>AbstractControllerTestCase</code>, <code>assertTemplateName()</code> and
  <code>assertNotTemplateName()</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5649">#5649</a> adds the <code>assertResponseReasonPhrase()</code> assertion.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5730">#5730</a> adds the ability to allow session persistence when
  performing multiple dispatches.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5731">#5731</a> adds a new argument to <code>dispatch()</code>,
  <code>$isXmlHttpRequest</code>; when boolean <code>true</code>, this adds an <code>X-Requested-With:
  XMLHttpRequest</code> header to the request object.</p>
</li>
</ul>

<h3>Zend\Validator</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4940">#4940</a> adds a new validator, <code>Bitwise</code>, for performing bitwise
  validation operations.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5664">#5664</a> removes the translation of validator message <em>keys</em>.
  While this is a backwards-incompatible change, this capability should never
  have been present, and removing it fixes a number of posted issues, as well as
  improves performance when retrieving validation error messages. A related
  change, <a href="https://github.com/zendframework/zf2/issues/5666">#5666</a>, removes translation of validation error
  messages from <code>Zend\Form\View\Helper\FormElementErrors</code>, as translation
  happens within the validators themselves; this prevents double translation,
  and, again, improves performance.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5780">#5780</a> adds the ability to set the "break chain on failure"
  flag via a configuration option; this allows setting the flag when using the
  <code>attachByName()</code> method of the <code>ValidatorChain</code>.</p>
</li>
</ul>

<h3>Zend\Version</h3>
<ul>
<li><a href="https://github.com/zendframework/zf2/issues/4625">#4625</a> adds the ability to pass a <code>Zend\Http\Client</code> to
  <code>Zend\Version\Version::getLatest()</code>, which should solve situations where
  <code>allow_url_fopen</code> is disabled.</li>
</ul>

<h3>Zend\View</h3>
<ul>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/4679">#4679</a> provides the ability to specify Internet Explorer
  conditional stylesheets in the <code>HeadLink</code> and <code>HeadStyle</code> view helpers,
  conditional metadata in the <code>HeadMeta</code> view helper, and conditional scripts in
  the <code>HeadScript</code> view helper.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5255">#5255</a> adds the ability to retrieve child view models based on
  what variable they registered to capture to in the parent; this is implemented
  via a new interface, <code>Zend\View\Model\RetrievableChildrenInterface</code>, which
  defines the method <code>getChildrenByCaptureTo()</code>.</p>
</li>
<li>
<p><a href="https://github.com/zendframework/zf2/issues/5266">#5266</a> attempts to make calls to <code>PhpRenderer::render()</code>
  slightly more robust by checking the return value from <code>include</code>ing a view
  script, and raising an exception when the <code>include</code> fails.</p>
</li>
</ul>

<h2>Thank You!</h2>

<p>
    A big thank you to the dozens upon dozens of contributors who helped make this
    new feature release a reality! This was truly a community-driven effort, and 
    would not have been possible without the contributions of each and every one
    of you.
</p>

<h2>Roadmap</h2>

<p>
    At this time, I am proposing a bi-monthly maintenance release schedule; however,
    we will often release an initial ".1" maintenance version sooner. After that, however,
    we will schedule maintenance releases every 2 months.
</p>

<p>
    For minor (feature) releases, I am proposing every six months, giving us a
    September 2014 release date for 2.4.0.
</p>

<p>
    If you have opinions on the release schedule, I invite you to voice them on our
    <a href="/archives">mailing lists</a>.
</p>

EOC;
$post->setExtended($extended);

return $post;
