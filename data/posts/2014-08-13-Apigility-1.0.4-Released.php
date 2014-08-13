<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-08-13 16:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-08-13 16:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<h1>Apigility 1.0.4</h1>

<p>We are pleased to announce the immediate availability of Apigility 1.0.4!</p>

<ul>
    <li><a href="https://apigility.org/download">https://apigility.org/download</a></li>
</ul>

<p>This is our fourth maintenance release of Apigility.</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>IBM i Support</h2>

<p>This release contains a number of fixes to ensure the ability to use Apigility on IBM i. Among them:</p>

<ul>
    <li>We are pinning support to Zend Framework 2.3.2 and above, which contains updates supporting DB2:<br /></li>
    <li>Full transaction support.<br /></li>
    <li>Fixed LIMIT support, allowing for paginated DB result sets.<br /></li>
    <li>Fixes to database-backed authentication<br /></li>
    <li>The ability to specify database driver options via the Admin UI. Most DB2<br /> connections need additional driver options specified, and you can now do so via the UI.</li>
</ul>

<h2>UI Improvements</h2>

<p>One lingering issue we've had reported is an error when creating APIs: the UI reports an error, but the API has been created. We made several patches that, in aggregate, should resolve these issues going forward:</p>

<ul>
    <li>We discovered that our promise chains in the Admin UI were not optimally constructed, and could potentially raise errors under the appropriate conditions; these have been fixed.<br /></li>
    <li>We introduced comprehensive cache control headers to prevent client-side caching of Admin API calls.<br /></li>
    <li>We introduced a timeout between successful completion of API creation and deletion calls, and subsequent fetching of the API list from the Admin API. In working with <a href="https://github.com/jguittard">Julien Guittard</a>, we were able to find an optimal timeout that resolves the issue.</li>
</ul>

<p>Additionally, for those users using Apache to serve the Admin UI and Admin API, we have stopped using backslashes in URI identifiers (Apache rejects URI-encoded slashes by default).</p>

<p>Other fixes were also made that are detailed under the &quot;zf-apigility-admin&quot; header below.</p>

<h2>Documentation fixes</h2>

<p>zf-apigility-documentation was not using the correct configuration key to discover input filters, which meant it was not reporting fields at all. This had further implications for zf-apigility-documentation-swagger, which was then unable to expose models based on those fields. This situation is now resolved.</p>

<h2>Collections</h2>

<p>While Apigility has supported retrieving collections in REST services, creating, replacing, updating, or deleting them has been an exercise left to the developer previously. With this release, field definitions can now be used to validate the items passed to collections, giving collections first-class support.</p>

<h2>Console</h2>

<p>zf-console was extensively updated, with many contributions and ideas from Zend's <a href="https://github.com/slaff">Slavey Karadzhov</a>. These include:</p>

<ul>
    <li>Simplification of mapping the command name to the route. By default the command name is considered the first argument of the route now.<br /></li>
    <li>Command handlers may now be specified in the configuration via the <code>handler</code> key for a command.<br /></li>
    <li>A number of useful CLI-specific filters are now provided, including an <code>Explode</code> filter (split comma or other delimited arguments to an array), a <code>QueryString</code> filter (specify arguments in query string format), and a <code>Json</code> filter (specify arguments in JSON).<br /></li>
    <li>Better error handling and error reporting.<br /></li>
    <li>The ability to generate autocompletion scripts for your CLI commands.</li>
</ul>

<p>zf-console is shaping up as a capable microframework for CLI commands!</p>

<h2>Thank You!</h2>

<p>Many thanks to everyone who contributed fixes, big or small, towards this release!</p>

<h2>Issues closed:</h2>

<h3>zf-apigility-admin</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/220">Timeout delay upon API creation and deletion</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/219">Introduced timeouts to API create/delete actions</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/218">Disable HTTP caching for Admin API</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/215">url-encoded backslashes cause issues in Apache</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/214">File permissions upon resources files creation</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/213">Revise promise chains</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/212">Allow defining DB adapter driver options</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/211">Resolves #210 by correcting the dead link</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/205">Undefined index: input_filter_specs</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/204">OAuth2 Mongo Adapter cannot be created successfully</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/196">zf-hal option 'render_collections' can break Apigility admin</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/190">Feature request: Ability to disable pagination from admin ui</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/issues/175">Creating new API fails with &quot;API not found&quot;</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-admin/pull/172">Can't Edit OAuth Adapter</a></li>
</ul>

<h3>zf-apigility-documentation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-documentation/pull/13">Fixed usage of configuration-driven creation of input filters</a></li>
</ul>

<h3>zf-apigility-documentation-swagger</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-documentation-swagger/pull/6">Use service name instead of api name to describe endpoint</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-documentation-swagger/pull/5">Add dependency</a></li>
</ul>

<h3>zf-apigility-skeleton</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-skeleton/pull/76">Bump ZF2 version requirement</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-skeleton/pull/73">Prefix config glob path</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-skeleton/issues/71">Apache configuration</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-apigility-skeleton/pull/67">Ensure default Apache site is disabled</a></li>
</ul>

<h3>zf-console</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-console/pull/11">Added out-of-the-box autocompletion help for all applications based on zf-console</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-console/pull/9">Better error handling</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-console/pull/8">Useful filters</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-console/pull/7">Allow setting handler in route configuration</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-console/pull/5">Simplify mapping the command name to the route</a></li>
</ul>

<h3>zf-content-validation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-content-validation/pull/20">Bug: Validation bypassed when POST is empty</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-content-validation/pull/19">isCollection() method returning true for entities</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-content-validation/pull/3">Problems concerning validating collections</a></li>
</ul>

<h3>zf-deploy</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-deploy/pull/27">Remove include of application configuration</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-deploy/pull/26">Cannot validate deployment.xml if zfdeploy.phar is in a folder with spaces</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-deploy/pull/22">Updated to use features from latest master of zf-console</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-deploy/pull/21">Execute composer.phar with the PHP binary running zf-deploy</a></li>
</ul>

<h3>zf-hal</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-hal/issues/51">422 status when my entity has no identifier on creation</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/50">Possible issue on HAL collection first link of the paginator.</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/48">Allow -1 for page size</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/47">Links in metadata map are no longer honored</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/45">Update Hal Plugin to support entities with $id = 0</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/39">Can't return a collection object with embedded entities when content negotiation is set to Json</a></li>
</ul>

<h3>zf-mvc-auth</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-mvc-auth/pull/36">deny_by_default inverts permission rules</a><li>
    <li><a href="https://github.com/zfcampus/zf-mvc-auth/pull/35">Support for OAuth2 Token in Query String / POST Body</a></li>
</ul>

<h3>zf-oauth2</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-oauth2/pull/58">Use content negotiation in AuthController</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-oauth2/pull/56">Ensure bodyParams is an array before creating request</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-oauth2/pull/54">Update PdoAdapter.php</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-oauth2/pull/48">refactored the class to support better reuse when extending the class</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-oauth2/issues/46">Separate MongoClient creation from MongoAdapter factory</a></li>
</ul>

<h3>zf-rest:</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-rest/pull/43">Allow returning entities without identifiers from creation routines</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-rest/pull/39">Allow Header tied to 'id' param.</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-rest/pull/38">Can't attach to a resource's event using the SharedEventManager</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-rest/pull/36">Update RestController to handle entities with $id = 0</a><br /></li>
    <li><a href="https://github.com/zfcampus/zf-rest/pull/31">Can't return a collection object with embedded entities when content negotiation is set to Json</a></li>
</ul>

EOC;
$post->setExtended($extended);

return $post;
