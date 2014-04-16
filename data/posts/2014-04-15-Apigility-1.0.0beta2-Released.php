<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.0beta2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-04-16 13:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-04-16 13:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>We are pleased to announce the immediate availability of Apigility 1.0.0beta2!</p>

<ul>
    <li><a href="http://apigility.org/download">http://apigility.org/download</a></li>
</ul>

<p>This is our second beta release of Apigility, and introduces extensive 
documentation, Admin UI performance improvements, and deployment tools!</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Documentation</h2>

<p>The primary goal of the beta phase for the Zend Framework team is documenting the project. We've made enormous headway at this point, but we'll let <strong>you</strong> be the judge of that; <a href="http://apigility.org/documentation">visit the documentation yourself</a>!</p>

<p>Among topics covered:</p>

<ul>
    <li>A &quot;Getting Started&quot; guide, and a companion, &quot;REST Service Tutorial&quot;. </li>
    <li>An API Primer </li>
    <li>Chapters on Authentication/Authorization, Content Validation, and the  Documentation features. </li>
    <li>A module-by-module reference guide, detailing configuration options.</li>
</ul>

<h2>New Features</h2>

<p>While the beta cycle is primarily around stabilizing the API and Admin UI, we decided one new feature warranted inclusion in version 1: a packaging/deployment tool, <a href="https://github.com/zfcampus/zf-deploy">zf-deploy</a>.</p>

<p>This tool allows you to create packages from your Apigility -- or any ZF2 application -- for deployment. Formats supported include zip, tar, tgz, and zpk (the Zend Server deployment package format). We plan to integrate support for deploying zpk packages soon as well.</p>

<h2>Beta2 Updates</h2>

<p>Polishing, polishing, polishing was our mantra for beta2. This included incorporating user feedback, but also scrutinizing the UI and code for consistency issues.</p>

<h3>UI Updates</h3>

<p>Following beta1, we had a number of complaints about UI responsiveness, particularly around the &quot;Fields&quot; screen. We did some analysis of the UI, and a lot of work around dynamically loading and unloading DOM in the admin based on what should be visible. As a result, we were able to significantly improve responsiveness. There may be more work to do, but early reports indicate that the changes make the Admin UI usable in situations that previously crashed the browser.</p>

<p>In addition to the performance improvements, we made the following updates:</p>

<ul>
    <li><p>On the &quot;Authorizations&quot; screen for each API, if no authentication is currently  configured, we display a message to this effect, and link to the  authentication screen. Unfortunately, in beta1, that link was invalid; we've  fixed this.</p></li>
    <li><p>The &quot;Fields&quot; tab received a slight overhaul. We noticed that items with  toggles displayed &quot;Yes/No&quot; terminology, but &quot;On/Off&quot; for the actual form  input; these now use &quot;Yes/No&quot; verbiage consistently. The &quot;Help&quot; screen could  not be dismissed with the <code>&lt;Esc&gt;</code> key; it now can. Previously, when hitting  <code>&lt;Enter&gt;</code> from the &quot;Create New Field&quot; text input, it would raise the &quot;Help&quot;  screen; it now properly creates the new field. The &quot;Description&quot; field was  moved to the first option displayed for each field, to promote documentation  of fields. We also added a &quot;Validation Failure Message&quot; field to allow  specifying a unified error message on failed validation (vs. one or more per  validator); we also ensured that &quot;blanking&quot; out the data in this field will  remove any such message previously set. Finally, filters are now listed before  validators, to signal the order in which validation operations occur  (filtering/normalization occurs before validation).</p></li>
    <li><p>The &quot;Source Code&quot; tab was not properly generating links for files; we've fixed  this in beta2.</p></li>
</ul>

<h3>Engine Updates</h3>

<p>A few improvements were made to the API engine itself:</p>

<ul>
    <li><p>The <code>UnauthorizedListener</code> registered by the <code>zf-apigility</code> module was not  registering headers set by the <code>zf-mvc-auth</code> module, meaning that the  <code>WWW-Authenticate</code> header was not propagating. This has been corrected.</p></li>
    <li><p>We modified <code>ZF\ContentNegotiation\JsonModel</code> to check for <code>json_encode()</code>  errors, and to raise an exception when one is detected. This prevents  situations where an empty response is returned on inability to serialize to  JSON.</p></li>
    <li><p><code>zf-apigility-documentation-swagger</code> was not returning a <code>Content-Type</code> header  value of <code>application/vnd.swagger+json</code>; it now does.</p></li>
    <li><p>We fixed the bcrypt cost in <code>zf-oauth2</code> to use the defaults from <code>Zend\Crypt</code>.</p></li>
    <li><p>We updated the OAuth2 database schema in <code>zf-oauth2</code> to match that of the  upstream <a href="https://github.com/bshaffer/oauth2-server-php">oauth2-server-php package</a>.</p></li>
    <li><p>We now inject the <code>ZF\Rest\ResourceEvent</code> with the current MVC request object;  you can retrieve it from within your resource class using  <code>$this-&gt;getEvent()-&gt;getRequest()</code>. This will give you access to HTTP request  headers, query string arguments, etc.</p></li>
    <li><p>We no longer allow multiple &quot;self&quot; relational links in <code>zf-hal</code>.</p></li>
    <li><p>When specifying route parameters for a <code>zf-hal</code> metadata map, you can now use  a PHP callable as the value; <code>zf-hal</code> will invoke that callable with the  object for which a link is being generated in order to get the value for that  route parameter. This is particularly useful for deterimining identifiers for  parent resources.</p></li>
    <li><p>We moved the <code>zf-apiglity-welcome</code> requirement to be a development-only  requirement.</p></li>
</ul>

<h2>Roadmap</h2>

<p>We're excited to get a stable release of Apigility as soon as we possibly can. We feel that both the engine and Admin UI have stabilized significantly, and are targetting a stable release by the end of this month. <strong>During that time, we will be working primarily on additional documentation and critical bugfixes.</strong></p>

<p>As noted in the beta1 announcement, reaching stability is only the first step, however! We already have contributors making significant headway on features such as &quot;Doctrine-Connected&quot;, &quot;Mongo-Connected&quot;, and &quot;DB-Autodiscovery&quot; REST services, and we will be debuting these in a 1.1 version not long after we reach version 1.0. Stay tuned!</p>
EOC;
$post->setExtended($extended);

return $post;
