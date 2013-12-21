<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 0.8.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-12-20 17:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-12-20 17:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>Today, we're releasing version 0.8.0 of Apigility! You can grab and test it
using one of the following two methods:</p>
<ul>
<li>Composer:
    <code>composer create-project zfcampus/zf-apigility-skeleton apigility</code></li>
<li>Manual download:
<pre><code>
wget https://github.com/zfcampus/zf-apigility-skeleton/releases/download/0.8.0/zf-apigility-skeleton-0.8.0.zip
unzip zf-apigility-skeleton-0.8.0.zip</li>
</code></pre>
</ul>
<p>We never announced our 0.7.0 version (though we showed off the features at
several conferences this past month), so there's a ton to announce!</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Authentication and Authorization</h2>
<p>Authentication and Authorization were the number one requested feature after our
original release announcement at ZendCon 2013. We managed to get
this in place in time for ZendCon 2013 Europe, and the features include:</p>
<ul>
<li>Ability to use HTTP Basic or Digest authentication, or OAuth2.</li>
<li>Ability to create authorization rules per HTTP method, per service in your
  API.</li>
</ul>
<p>We are leveraging Zend Framework 2's <code>Zend\Authentication</code> library for HTTP
authentication, and Brent Shaffer's <a href="https://github.com/bshaffer/oauth2-server-php">oauth2-server-php</a>
library for OAuth2.</p>
<p>Apigility allows you to define one authentication scheme per application. You
can set the authentication details on the main dashboard, under the heading
"Authentication":</p>
<p style="text-align:center"><img style="border: 1px solid black" width="640" alt="Authentication choices" src="/images/ag/authentication-buttons.png"></p>
<p style="text-align:center"><img style="border: 1px solid black" width="640" alt="Authentication form" src="/images/ag/authentication-form.png"></p>
<p style="text-align:center"><img style="border: 1px solid black" width="640" alt="Authentication view" src="/images/ag/authentication-view.png"></p>
<p>To use HTTP Basic authentication, you will need to create a <code>htpasswd</code> file,
using Apache's <code>htpasswd</code> utility. When you add HTTP Basic authentication to
your application, you will specify the location of the <code>htpasswd</code> file.</p>
<p>For HTTP Digest authentication, you will need to create a file with lines in the
following format:</p>
<p><code>&lt;username&gt;:&lt;realm&gt;:&lt;credentials&gt;</code></p>
<p>The <code>credentials</code> field must be an MD5 hash of the password that will be
accepted. When adding the HTTP Digest authentication to your application, you
will specify the location of this file, and will also need to specify the
appropriate <code>realm</code>, <code>nonce\_timeout</code> (number of seconds the credentials remain
valid), and, optionally, a list of <code>digest_domains</code> (the domains for which the
same authentication information is valid).</p>
<p>To use OAuth2 authentication, you will need to setup a database, and add clients
and optionally users (users are only necessary if using the <code>password</code> grant
type). <a href="https://github.com/zfcampus/zf-oauth2">See the zf-oauth2 README</a> for
details on how to setup the database and seed it.</p>
<p>Once you have authentication setup, you can start setting up authorization
restrictions on your API. To do this, navigate to the "Authorization" menu item
in any API:</p>
<p style="text-align:center"><img style="border: 1px solid black" width="640" alt="Authorization" src="/images/ag/authorization.png"></p>
<p>By default, APIs created with Apigility are public. To require authorization on
specific services or specific HTTP methods of services, check the appropriate
boxes and save your changes. From that point forward, authentication will be
required for those actions!</p>
<p>For more information, <a href="http://bit.ly/ag-auth">view the demo video</a></p>
<h2>Cross Origin Resource Sharing</h2>
<p>In order to operate a web-based application that interact with your APIs, you
may need to investigate Cross Origin Resource Sharing (CORS). CORS describes
HTTP requests for HTTP resources made from a different domain than the resource
exists on. As an example, if your API is at <code>http://example.com/api/</code>, but you
want to request it from <code>http://my-uber-cool-app.com/</code>, CORS is in effect.</p>
<p>If a browser attempts to create an <code>XMLHttpRequest</code> to a different domain than
the current page, then it will detect a CORS request. At that point, the browser
will ask the server with the resource if the request is allowed; if the
originating server does not reply with the appropriate headers, the browser will
not submit the original request, and the <code>XMLHttpRequest</code> will fail.</p>
<p>Apigility does not deal with CORS by default, but the 0.8.0 release includes
changes that ensure that CORS requests <em>can</em> be honored if you are using a CORS
plugin. We have tested against the <a href="https://github.com/zf-fr/zfr-cors">ZfrCors ZF2
module</a>, and it works seamlessly with
Apigility at this point.</p>
<p>We highly recommend the combination of Apigility and ZfrCors when building
web-based JavaScript applications that will operate on separate domains from
your APIs.</p>
<h2>Validation</h2>
<p>Another aspect of API security is validating the incoming input. Ideally, you
should reject anything that does not validate outright, and as early as
possible.</p>
<p>0.8.0 adds a new module,
<a href="https://github.com/zfcampus/zf-content-validation">zf-content-validation</a>,
which provides a validation engine based on Zend Framework's <code>InputFilter</code>
component. This component, when a request method that contains incoming data
occurs, checks to see if the matched service has a corresponding input filter,
and, if so, attempts to validate the incoming data against it. If the validation
fails, an error response is immediately returned.</p>
<p>In the Apigility Admin UI, each service now has an "Inputs" tab that allows you
to define the input filter. In this tab, you define inputs, which correspond to
each field of data you will be expecting. For each input, you can define one or
more validators, along with any configuration you want for each. In this case, a
picture is probably more sufficient:</p>
<p style="text-align:center"><img style="border: 1px solid black" width="640" alt="Validation" src="/images/ag/inputfilter.png"></p>
<p>For more information, <a href="http://bit.ly/ag-validation">view the demo video</a>.</p>
<h2>Future</h2>
<p>At this point, we're wrapping up the featureset for a stable version of
Apigility. The last milestones we have include:</p>
<ul>
<li>a module for generating API documentation. At this time, we are favoring <a href="http://apiblueprint.org/">API
  Blueprint</a> as the markup is trivial to generate from
  our configuration, and, being markdown-derived, relatively easy to edit and
  expand once the initial generation is complete.</li>
<li>some cleanup of the UI, including some long-overdue refactoring and formalized
  testing.</li>
<li>documentation of the various components, as well as tutorials on how they all
  fit together.</li>
</ul>
<p>We hope to complete the API documentation milestone in the first weeks of 2014.
At that point, we will start the beta release cycle, spending that time to do
the UI refactoring and project documentation. Once those are complete, we'll
finally issue a stable release; we're aiming for late February 2014 at this
time.</p>

EOC;
$post->setExtended($extended);

return $post;
