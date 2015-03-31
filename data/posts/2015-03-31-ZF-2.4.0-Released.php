<?php
// @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.4.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-03-31 11:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-03-31 11:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.4.0</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is the fourth feature release in the ZF2 series, and marks our first
    Long Term Support release. It provides support for PHP versions 5.3 through
    the current nightly PHP 7 builds, and will be supported with security fixes
    for the next 3 years.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Changes</h2>

<p>
    Version 2.4 has been a little more than a year in the making. As a result, there
    are a ton of new features and fixes; more than 300 issues and patches were 
    addressed for this specific release!
</p>

<p>
    Below is a set of notable backwards compatibility breaks and new features.
</p>

<h3>BC Breaks</h3>

<p>
    Backwards Compatibility (BC) breaks occur when a signature changes or 
    behavior changes. In many cases, these will not affect most users. However,
    we call each of them out as a reference, as you may have edge cases or
    use cases that may have depended on the previous behavior.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/4122">#4122</a> and  <a href="https://github.com/zendframework/zf2/pull/6613">#6613</a> deprecate  <code>Zend\EventManager\EventManager::triggerUntil()</code> and alias it to  <code>trigger()</code>. As <code>triggerUntil()</code> now emits a deprecation notice, you may  need to update your code; this can be done by changing the method name to  <code>trigger()</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6073">#6073</a>  <code>Zend\Mvc\Controller\Plugin\FlashMessenger::addMessage()</code>, <code>hasMessages()</code>,  <code>getMessages()</code>, <code>clearMessages()</code>, <code>hasCurrentMessages()</code>,  <code>getCurrentMessages()</code>, and <code>clearCurrentMessages()</code> each now take one  additional optional argument, <code>$namespace</code>. This will only affect those  extending the class and overriding those methods, and the default value  retains existing default behavior. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6108">#6108</a> Enables exception  trace reporting by default in <code>Zend\Test</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6154">#6154</a>  <code>Zend\InputFilter\BaseInputFilter::isValid()</code> now has one optional argument,  <code>$context</code>, allowing passing the context of validation along to the input  filter during validation. This allows individual inputs to test against other  inputs as part of validation. This change should only affect those overriding  the <code>isValid()</code> method in an extension of <code>BaseInputFilter</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6151">#6151</a> updates  <code>Zend\Filter\Word\SeparatorToCamelCase</code> to properly identify non-whitespace  characters following the separator. This should not affect most use cases. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6464">#6464</a> modifies the  <code>autocomplete</code> form attribute to be a string value instead of a boolean value  in order to follow the WHATWG HTML 5 specification. If you relied on the  &quot;boolean&quot; strings &quot;on&quot; or &quot;off&quot;, you may need to update your client-side code. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6552">#6552</a> modifies the  <code>ModuleManager</code>'s config caching to eliminate the &quot;double-dot&quot; (<code>..</code>) that  occurs in cache file names if no cache key or an empty cache key is provided.  If you use config caching, you will need to remove your cache files before  deployment to ensure all works correctly. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6560">#6560</a> modifies the  <code>Zend\Cache\Storage\Adapter\AbstractAdapter::getItem()</code> method to ensure it  <em>always</em> returns null in the case of a cache miss; previously, in some  adapters, it would return another value, and, because <code>$success === null</code> is  the check for a cache miss, register a false positive. You may have been  mistakenly relying on this previously; test carefully. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6572">#6572</a> removes  <code>Zend\Mvc\Service\ViewJsonRendererFactory</code> and  <code>Zend\Mvc\Service\ViewFeedRendererFactory</code>, as the classes each instantiates  have empty constructors and can be referenced as <code>ServiceManager</code> invokables.  This change should not affect anyone. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6581">#6581</a>  <code>Zend\Validator\Between</code> now raises an exception during instantiation if  either the <code>min</code> or <code>max</code> options are missing in the provided <code>$options</code>  array. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6809">#6809</a> modifies the behavior  of <code>Zend\Paginator\Paginator::$firstItemCount</code> to equal <code>0</code> if no items are  present; previously, it returned <code>1</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6817">#6817</a> modifies the behavior  of <code>Zend\Paginator\Paginator::getItems()</code> to ensure it always returns an  array. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7181">#7181</a> removes code that was  checking for cache tags in <code>Zend\Paginator\Paginator</code>'s caching support, as  not all cache adapters support tags. This likely will not affect users. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7242">#7242</a> adds an optional <code>$hops</code>  parameter to <code>Zend\Mvc\Controller\Plugin\FlashMessenger::addMessage()</code>,  allowing you to specify the number of hops for the specific message. If you  were extending the plugin previously and overriding this method, you will have  to update your signature. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7255">#7255</a> adds the parameter  <code>$data</code> to <code>Zend\Mvc\Controller\AbstractRestfulController::deleteList()</code>. If  you were extending this class previously and overriding this method, you will  need to update your signature. The <code>$data</code> parameter provides the deserialized  request body content. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7267">#7267</a> modifies  <code>Zend\Cache\Storage\Adapter\DbaOptions::setHandler()</code> to raise an exception  if the <code>inifile</code> handler is specified, as this handler is (a) not performant  for cache purposes, and (b) may or may not work reliably for write operations  based on the version of libdb used.</li>
</ul>

<h3>Features</h3>

<p>
    Zend Framework 2.4.0 also has a ton of new features for you to consider and 
    utilize in your applications. Below is a breakdown by component.
</p>

<h4>Zend\Authentication</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6080">#6080</a> Adds  <code>Zend\Authentication\AuthenticationServiceInterface</code> to allow substitutions. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7310">#7310</a> Adds  <code>Zend\Authentication\Adapter\Callback</code>, allowing usage of arbitrary PHP  callables for authentication purposes. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7334">#7334</a> Adds bcrypt support  for HTTP Basic authentication.</li>
</ul>

<h4>Zend\Cache</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6078">#6078</a> Adds a MongoDB cache  adapter.</li>
</ul>

<h4>Zend\Code</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6339">#6339</a> Adds scanning and  generator support for PHP traits.</li>
</ul>

<h4>Zend\Console</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6646">#6646</a> Adds a password prompt  to the <code>Console</code> component. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7091">#7091</a> Adds a checkbox prompt  to the <code>Console</code> component.</li>
</ul>

<h4>Zend\Crypt</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6438">#6438</a> Adds  <code>Zend\Crypt\FileCipher</code> for purposes of encrypting or decrypting a file  using a symmetric cipher. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7141">#7141</a> Adds  <code>Zend\Crypt\Password\BcryptSha</code> to allow safely pre-hashing long (&gt; 72  byte) passwords; this functionality is opt-in. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7334">#7334</a> Adds bcrypt support  for Apache password hashing.</li>
</ul>

<h4>Zend\Db</h4>

<p>
    In addition to the following list of features, this component received many 
    pull requests that improve code quality by abstracting common code, 
    easing maintenance and tightening security.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/5142">#5142</a> Implements combine  operators in order to allow creation of SQL statements with multiple unions  and subselects. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/5505">#5505</a> Support for nested  transactions. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6740">#6740</a> Adds the ability to  use aliased tables to <code>Zend\Db\TableGateway</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6800">#6800</a> Adds the ability to  add native predicates to queries, while respecting combine order. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6890">#6890</a> Adds the ability to  specify an alternate adapter/platform when generating a SQL statement. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6931">#6931</a> Adds constants for  specifying left and right outer joins to <code>Zend\Db\Sql\Select</code>.</li>
</ul>

<h4>Zend\Di</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6746">#6746</a> and  <a href="https://github.com/zendframework/zf2/pull/6747">#6747</a> provide performance  optimizations for the component.</li>
</ul>

<h4>Zend\Feed</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/7125">#7125</a> adds  <code>Zend\Feed\Reader\ReaderImportInterface</code>, defining the methods <code>import()</code>,  <code>importRemoteFeed()</code>, <code>importString()</code>, and <code>importFile()</code> common to the  concrete <code>Reader</code> implementation. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7254">#7254</a> Adds  <code>Zend\Feed\Reader\StandaloneExtensionManager</code>, which provides a  <code>Zend\Feed\Reader\ExtensionManagerInterface</code> implementation with no  dependencies. This is now used as the default extension manager.</li>
</ul>

<h4>Zend\Filter</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6545">#6545</a> Adds  <code>Zend\Filter\UpperCaseWords</code>, which will essentially invoke <code>ucwords()</code> on the  provided value, or, if an alternate encoding is used, utilize  <code>mb_convert_case()</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6758">#6758</a> Adds the method  <code>getAdapterInstance()</code> to <code>Zend\Filter\Encrypt</code> to allow retrieving the  attached encryption adapter. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6962">#6962</a> Adds  <code>Zend\Filter\Blacklist</code> and <code>Zend\Filter\Whitelist</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7104">#7104</a> Adds  <code>Zend\Filter\DataUnitFormatter</code>, which will binary and decimal numbers to  data units (e.g., <code>5ki</code>, <code>6G</code>, etc.). </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7277">#7277</a> Adds  <code>Zend\Filter\DateSelect</code>, <code>Zend\Filter\DateTimeSelect</code>, and  <code>Zend\Filter\MonthSelect</code>, and modifies the corresponding <code>Zend\Form</code> elements  to use the appropriate filter.</li>
</ul>

<h4>Zend\Form</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6271">#6271</a> Ensures all Form view  helpers allow the full spectrum of HTML5 attributes. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6656">#6656</a> Adds a <code>getElements()</code>  method to each of <code>DateSelect</code> and <code>MonthSelect</code> to return a list of each  composed <code>Select</code> element (as each composes multiple elements). </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6754">#6754</a> Adds a new  <code>preserveDefinedOrder</code> flag for <code>Zend\Form\Annotation\AnnotationBuilder</code>; if  <code>true</code>, elements will be created and listed in the order in which they were  defined in the class parsed. <a href="https://github.com/zendframework/zf2/pull/7276">#7276</a>  adds support for the <code>preserve_defined_order</code> configuration flag to the  <code>FormAnnotationBuilderFactory</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6783">#6783</a> Adds  <code>@ContinueIfEmpty</code> as an available form annotation. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7171">#7171</a> modifies  <code>Zend\Form\Form::prepare()</code> to reset (i.e., clear) password values, ensuring  they are not displayed in rendered forms. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7181">#7181</a> updates  <code>Zend\Form\View\Helper\FormButton</code> to translate the button content.</li>
</ul>

<h4>Zend\Http</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6571">#6571</a> Adds the ability to  (optionally) pass HTTP client configuration to <code>Zend\Http\ClientStatic</code>'s  <code>get()</code> and <code>post()</code> methods. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7121">#7121</a> Adds the ability to  specify the cURL <code>sslverifypeer</code> option when using the cURL adapter. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7181">#7181</a> Adds detection of SSL  on reverse proxies. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7259">#7259</a> Modifies  <code>Zend\Http\Response::setStatusCode()</code> such that it clears the reason phrase;  this is done to ensure the status code and reason phrase are kept in sync. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7329">#7329</a> Adds support for  Digest authentication with cURL adapters.</li>
</ul>

<h4>Zend\InputFilter</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6431">#6431</a> Adds the ability to  merge input filters to <code>Zend\InputFilter\BaseInputFilter</code>, via a new <code>merge()</code>  method. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7247">#7247</a> Adds  <code>Zend\InputFilter\InputFilterAbstractServiceFactory</code>, allowing  configuration-driven creation of named input filter instances using the  top-level <code>input_filter_specs</code> configuration key.</li>
</ul>

<h4>Zend\Log</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6058">#6058</a> Adds a <code>Timestamp</code> log  filter. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6138">#6138</a> Adds a <code>ReferenceId</code>  processor. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7294">#7294</a> Adds  <code>Zend\Log\Writer\Mail</code> for sending log messages via email.</li>
</ul>

<h4>Zend\Mail</h4>
<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6570">#6570</a> Adds the ability to  set a transport envelope on the SMTP transport.</li>
</ul>

<h4>Zend\Mvc</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6246">#6246</a> Adds a  <code>TranslatorPluginManager</code> to allow modules to register their own translation  plugins. This adds a new <code>translator_plugins</code> top-level configuration key,  using the standard service manager configuration scheme. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6615">#6615</a>  <code>Zend\Mvc\Controller\AbstractController</code> now also registers any implemented  interfaces as identifiers with the composed <code>EventManager</code> instance. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6951">#6951</a> Adds the ability to  override <code>display_exceptions</code> and <code>display_not_found_reason</code> specifically for  the console view manager. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7240">#7240</a> Adds  <code>Zend\Mvc\HttpMethodListener</code>, which is also now registered by default; you  can specify via the <code>http_methods_listener</code> top-level configuration key an  array of HTTP methods your application will allow, and this listener will  return early if the current method is not present in that list. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7336">#7336</a> Adds the ability to  specify that the controller in the route match should be used instead of the  controller class name for purposes of creating the template string.</li>
</ul>

<h4>Zend\Navigation</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/7245">#7245</a> Adds  <code>Zend\Navigation\NavigationAbstractServiceFactory</code>, allowing  configuration-driven creation of named <code>Navigation</code> instances using the  <code>navigation</code> top-level configuration key.</li>
</ul>

<h4>Zend\Paginator</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/5518">#5518</a> Allow specifying a  custom query for determining the item count in the <code>DbSelect</code> adapter. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7122">#7122</a> Adds  <code>Zend\Paginator\PaginatorIterator</code>, to allow iterating an entire paginated set  at once.</li>
</ul>

<h4>Zend\Permissions</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/7327">#7327</a> Adds  <code>Zend\Permissions\Rbac\Assertion\Callback</code>, allowing usage of arbitrary PHP  callables for providing RBAC assertions. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7328">#7328</a> Adds  <code>Zend\Permissions\Acl\Assertion\Callback</code>, allowing usage of arbitrary PHP  callables for providing ACL assertions.</li>
</ul>

<h4>Zend\ServiceManager</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6175">#6175</a> adds  <code>Zend\ServiceManager\MutableCreationOptionsTrait</code> to simplify implementing  <code>MutableCreationOptionsInterface</code>.</li>
</ul>

<h4>Zend\Stdlib</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6091">#6091</a> Adds  <code>Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy</code>, which allows  creating a map of <code>object property =&gt; serialized property</code> names. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6194">#6194</a> Adds  <code>Zend\Stdlib\Hydrator\Strategy\StrategyChain</code>, to allow chaining multiple  strategies together to mutate a value during hydration and/or extraction; this  is similar to filter chains, only for hydrators. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6227">#6227</a> Adds  <code>Zend\Stdlib\Hydrator\Strategy\ExplodeStrategy</code>, which can explode a value  using a provided delimiter on hydration, and implode with the delimter on  extraction. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6289">#6289</a> Adds  <code>Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy</code>, which will  hydrate a formatted date-time string to a <code>DateTime</code> instance, or serialize a  <code>DateTime</code> value to the given string format. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6367">#6367</a> Adds  <code>Zend\Stdlib\Hydrator\Strategy\CompositeNamingStrategy</code>, which allows  providing a set of naming strategies to compare a key against, with a default  naming strategy to use if unmatched. The main purpose is to be able to use a  single strategy for multiple properties. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6523">#6523</a> Adds  <code>Zend\Stdlib\Hydrator\Strategy\BooleanStrategy</code>, providing a way to specify  &quot;boolean&quot; strings or integers that can be cast to booleans, and vice versa on  extraction. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6894">#6894</a> Adds  <code>Zend\Stdlib\Hydrator\DelegatingHydrator</code>. This hydrator will attempt to  find a hydrator registered in the <code>HydratorPluginManager</code> using the name of  the class to be hydrated or extracted. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6899">#6899</a> and  <a href="https://github.com/zendframework/zf2/pull/6903">#6903</a> add the ability to  remove and/or replace keys, respectively, when calling  <code>Zend\Stdlib\ArrayUtils::merge()</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/7315">#7315</a> adds  <code>Zend\Stdlib\ArrayUtils::filter()</code>, which is a shim for supporting PHP's  <code>array_filter()</code> function, and, specifically, the 3rd <code>$flag</code> argument added  in PHP 5.6 for filtering on key or both key and value.</li>
</ul>

<h4>Zend\Uri</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6886">#6886</a> Adds the ability to  specify the <code>user-info</code> for a URI as a single string, while ensuring that both  <code>user</code> and <code>password</code> are still accessible.</li>
</ul>

<h4>Zend\Validator</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6496">#6496</a> Implements a  <code>PriorityQueue</code> for <code>Zend\Validator\ValidatorChain</code>, adding an optional  <code>$priority</code> argument to each of <code>attach()</code>, <code>addValidator()</code>, and  <code>attachByName()</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6496">#6496</a> Modifies  <code>Zend\Validator\ValidatorChain::attach()</code> to allow accepting a PHP callable as  the validator argument, omitting the need to wrap it in a <code>Zend\Validator\Callback</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6678">#6678</a> Adds  <code>Zend\Validator\Timezone</code>.</li>
</ul>

<h4>Zend\View</h4>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6196">#6196</a> Adds  <code>Zend\View\Resolver\RelativeFallbackResolver</code>, which allows resolving a  template based on the location of the template requesting the render. As an  example, if you call <code>$this-&gt;render('foo');</code> from within a template referenced  as <code>foo-bar/baz-bat/quz</code>, this new resolver <em>can</em> resolve it to  <code>foo-bar/baz-bat/foo</code>. </li>
    <li><a href="https://github.com/zendframework/zf2/pull/6709">#6709</a> Adds  <code>Zend\View\Helper\HtmlTag</code>, for generating the opening and closing <code>&lt;html&gt;</code>  tag, along with attributes and namespaces.</li>
</ul>

<h4>Misc</h4>

<p>
    Some changes are not specific to a given component; these are listed here.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6903">#6903</a> Adds the ability to  specify extensions to scan when using <code>bin/templatemap_generator.php</code>. </li>
    <li>Updated to PHPUnit 4 and php-cs-fixer 1. </li>
    <li>Updated the framework and all components to specify  <a href="http://www.php-fig.org/psr/psr-4/">PSR-4</a> autoloading rules (instead of  PSR-0, which is now deprecated). </li>
    <li>Added PHP 7 nightly as an optional build target; tests currently pass.</li>
</ul>

<h3>Changelog</h3>

<p>
    The above are only selected changes and features. For the full changelog:
</p>

<ul>
    <li><a href="/changelog/2.4.0">http://framework.zend.com/changelog/2.4.0</a></li>
</ul>

<h2>Long Term Support</h2>

<p>
    As noted previously, 2.4.0 marks our first Long Term Support release. We will release
    security fixes and critical bug fixes on that release branch for a duration 
    of 3 years, ending on 31 March 2018.
</p>

<p>
    You can opt-in to the LTS version by pinning your <kbd>zendframework/zendframework</kbd>
    <a href="https://getcomposer.org">Composer</a> requirement to the version <kbd>~2.4.0</kbd>.
    We will shortly be providing a 2.4.0 tag of the <a href="https://github.com/zendframework/ZendSkeletonApplication">
    skeleton application</a> that provides this requirement.
</p>

<p>
    <a href="/long-term-support">Visit our Long Term Support information page</a> for more information.
</p>

<h2>Milestones</h2>

<p>
    As noted previously, ZF 2.4.0 marks our first Long Term Support release; the reason for this
    is because we're <a href="/blog/announcing-the-zend-framework-3-roadmap.html">shifting gears
    towards Zend Framework 3</a> development.
</p>

<p>
    In the next week, we'll be releasing <a href="https://apigility.org/">Apigility 1.1</a>. Once
    complete, we will be starting the process of splitting all components into their own
    repositories, to be developed on their own life cycles. Once that process is complete,
    we'll tag each at 2.5.0, and modify the primary Zend Framework repository to act primarily
    as a metapackage that pulls in each component.
</p>

<p>
    Add to that a new HTTP layer and middleware, and we'll have a Zend Framework 3.0 to announce
    later this year!
</p>

<h2>Thank You!</h2>

<p>
    This release could not have happened without the enormous efforts of our developer
    community. Literally hundreds of patches, from coding standards fixes to full-blown features,
    documentation, and clarifications, were incorporated in the last several months.
</p>

<p>
    In particular, three individuals had a huge impact on this release:
</p>

<ul>
    <li><a href="https://ocramius.github.io">Marco Pivetta</a>, who, all told, should
        really be dubbed the release master for 2.4, having merged the majority of
        patches.</li>
    <li><a href="http://adam.lundrigan.ca">Adam Lundrigan</a>, who did thorough and
        insightful reviews of a huge number of patches and features.</li>
    <li><a href="https://github.com/samsonasik">Abdul Malik Ikhsan</a>, who acts as
        our coding standards "police", reviewing almost every single patch and
        offering specific fixes to assist newcomers with getting patches to 
        pass our quality assurance tools.</li>
</ul>

<p>
    Please find time to find these individuals and thank them for their efforts!
</p>

EOC;
$post->setExtended($extended);

return $post;
