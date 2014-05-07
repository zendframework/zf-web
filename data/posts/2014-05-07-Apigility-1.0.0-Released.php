<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.0 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-05-07 10:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-05-07 10:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<img src="/images/ag-hero.png" style="display: block; margin-left: auto; margin-right: auto; max-width: 100%" alt="Apigility">

<p>We're happy to announce the immediate availability of Apigility 1.0.0!</p>

<ul>
    <li><a href="http://apigility.org/download">http://apigility.org/download</a></li>
</ul>

<p>Apigility is the world's easiest way to create robust, well-formed, documented, and secure APIs.</p>

<p>We've noticed that web development has fundamentally shifted over the past few years. Server side applications that return heavy loads of HTML markup are waning; instead APIs are driving the web. APIs are being used to enable inter-division data exchange. APIs are being used as the building blocks for web applications -- allowing user interface experts the flexibility to change the look and feel of a website with no intervention from the server-side application developers. APIs are driving and empowering the mobile web, allowing creation of both native mobile applications and mobile web applications.</p>

<p>Apigility exists to allow <em>you</em> to expose <em>your</em> business logic as an API.</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Opinionated</h2>

<p>APIs are not just about tossing around some JSON or XML. They require a ton of architectural decisions every step of the way as you implement the API:</p>

<ul>
    <li>How will you handle HTTP method negotiation?<br /></li>
    <li>How will you handle content negotiation?<br /></li>
    <li>How will you handle authentication?<br /></li>
    <li>How will you handle authorization?<br /></li>
    <li>How will you handle input validation?<br /></li>
    <li>What representation format will you use?<br /></li>
    <li>How will you represent errors?<br /></li>
    <li>How will you document your API?</li>
</ul>

<p>Most of these questions have no single, correct answer. Many standards, proposals, and drafts exist for all aspects of APIs. As a developer, you have to wade through them and choose which you will use, and how you will put them together.</p>

<p>In short, when developing an API, you often spend an equal or greater amount of time developing the architecture for the API as you would writing the code you want to expose in the first place.</p>

<p>Apigility is opinionated <em>for you</em>, and provides a flexible and robust engine that answers these questions:</p>

<ul>
    <li><a href="http://tools.ietf.org/html/draft-kelly-json-hal-06">application/hal+json</a> is<br /> used for the representation format (but you can add your own representations<br /> if you really want).<br /></li>
    <li><a href="http://tools.ietf.org/html/draft-nottingham-http-problem-06">application/problem+json</a><br /> is used for representing errors.<br /></li>
    <li>HTTP method negotiation is handled early, returning appropriate response<br /> status codes and headers when invalid methods are detected. Support for<br /> <code>OPTIONS</code> requests is built in.<br /></li>
    <li>Content negotiation is handled early, returning appropriate response<br /> status codes and headers when we cannot return an appropriate representation,<br /> or cannot understand the data provided to the application.<br /></li>
    <li>Authentication is handled early, returning appropriate response<br /> status codes and headers when invalid credentials are detected. We provide<br /> HTTP Basic, HTTP Digest, and OAuth2 support out-of-the-box, but provide a<br /> flexible, event-driven system for implementing or integrating your own<br /> authentication systems.<br /></li>
    <li>Authorization is handled early, returning appropriate response<br /> status codes and headers when access is not allowed. We provide an ACL-based<br /> system that can be easily extended to provide user-specific permissions as<br /> well as an event-driven system for implementing or integrating your own<br /> authorization systems if needed.<br /></li>
    <li>Input validation is handled early, returning appropriate response status codes<br /> and error messages when invalid data is detected.<br /></li>
    <li>Documentation is provided out of the box. We provide HTML, JSON, and<br /> <a href="https://helloreverb.com/developers/swagger">Swagger</a> representations; we<br /> anticipate adding more in the future. Documentation is generated automatically<br /> from your API configuration, and you are able to add descriptions and more<br /> detail as desired.</li>
</ul>

<p>We make decisions so you don't have to. However, you'll note that we've created flexibility so that if <em>you</em> have an opinion, you can integrate it!</p>

<h2>Interface with your API</h2>

<p>Apigility is not just an engine. Apigility also provides a web-based Admin UI to allow you to quickly create and modify your API and services, set up authentication, create authorization rules, set up validations for incoming data, and write document.</p>

<img src="/images/apigility-1.0.0-dashboard.png" style="display: block; margin-left: auto; margin-right: auto; max-width: 100%" alt="Apigility Dashboard">

<p>The Admin UI is built using <a href="https://angularjs.org/">AngularJS</a>, and is backed by... an API! (We're taking the &quot;API First&quot; mantra very seriously!)</p>

<h2>Get Started in Seconds</h2>

<p>You can install Apigility in seconds; execute the following command in your shell:</p>

<pre class="console"><code>$ curl -sS http://apigility.org/install | php</code></pre>

<p>or, if you don't have <a href="http://curl.haxx.se/">curl</a> installed, use the PHP interpreter itself:</p>

<pre><code>$ php -r &quot;readfile(&#39;http://apigility.org/install&#39;);&quot; | php</code></pre>

<h2>Deploy Anywhere</h2>

<p>Apigility is built on top of Zend Framework 2, and has tools to ensure that the Admin UI is only present in development mode. As such, you can deploy Apigility anywhere you normally deploy PHP applications: a local server, your web host provider, or the cloud.</p>

<p>We provide a tool, <a href="https://github.com/zfcampus/zf-deploy">ZFDeploy</a>, to aid in creating deployment packages, including Zend Server ZPK files.</p>

<h2>Modular</h2>

<p>Apigility consists of more than a dozen Zend Framework 2 modules, each doing one specific task in the workflow. As such, you can mix and match these modules in your own ZF2 applications, or even <a href="http://apigility.org/documentation/recipes/apigility-in-an-existing-zf2-application">add Apigility to an existing application</a>.</p>

<h2>The Future</h2>

<p>Reaching 1.0 is a huge milestone -- but it's not the end of the road!</p>

<p>We already have a number of great features waiting in the wings for a 1.1 release: Doctrine-Connected services, Database Autodiscovery, Mongo-Connected services, and HTTP Caching.</p>

<p>What will <em>you</em> build today?</p>
EOC;
$post->setExtended($extended);

return $post;
