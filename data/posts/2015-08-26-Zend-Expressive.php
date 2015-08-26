<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Announcing Expressive');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-08-26 13:25', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-08-26 13:25', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    We are pleased to announce the immediate availability of a new project, 
    <a href="https://github.com/zendframework/zend-expressive">Expressive</a>!
</p>

<p>
    Expressive allows you to write <a href="http://www.php-fig.org/psr/psr-7/">PSR-7</a>
    <a href="https://github.com/zendframework/zend-stratigility/blob/master/doc/book/middleware.md">middleware</a>
    applications for the web. It is a simple micro-framework built on top of
    <a href="https://github.com/zendframework/zend-stratigility">Stratigility</a>, providing:
</p>

<ul>
    <li>Dynamic routing</li>
    <li>Dependency injection via container-interop</li>
    <li>Templating</li>
    <li>Error Handling</li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Installation and Quick Start</h2>

<p>
    Expressive can get you up and running with an application in minutes.
</p>

<p>
    To install, use <a href="https://getcomposer.org">Composer</a>:
</p>

<pre><code class="lang-bash">
$ composer require zendframework/zend-expressive aura/router zendframework/zend-servicemanager
</code></pre>

<p>
    From there to "hello, world,", all you now need is a single file:
</p>

<pre><code class="lang-php">
// In index.php
use Zend\Expressive\AppFactory;

require 'vendor/autoload.php';

$app = AppFactory::create();
$app->route('/', function ($request, $response, $next) {
    $response->getBody()->write('Hello, world!');
    return $response;
});
$app->run();
</code></pre>

<p>
    From there, fire up the built-in web server:
</p>

<pre><code class="lang-bash">
$ php -S 0.0.0.0:8080 -t .
</code></pre>

<p>
    Browse to localhost:8080, and you should see it running!
</p>

<p>
    Visit <a href="http://zend-expressive.readthedocs.org/en/stable/quick-start/">our documentation for the full quick start</a>.
</p>

<h2>Breaking out of the box</h2>

<p>
    A huge part of the PHP Renaissance has been due to the advent of
    <a href="https://getcomposer.org">Composer</a>, which has simplified dependency
    management, and led to tens of thousands of standalone libraries and packages.
    As such, frameworks, while still popular, are often being eschewed for
    homemade, application-specific frameworks made of commodity components.
    Frameworks simply cannot ignore this trend, and decoupling should become
    the norm going forward.
</p>

<p>
    With <a href="https://apigility.org">Apigility</a>, the Zend Framework team
    began using third party software as part of the solutions it provides. With
    Expressive, we took that even further: we provide abstractions for routing
    and templating capabilities, but largely rely on third-party libraries for
    the recommended implementations.
</p>

<p>
    Expressive features integrations with:
</p>

<ul>
    <li>Aura.Router</li>
    <li>FastRoute</li>
    <li>Pimple</li>
    <li>Plates</li>
    <li>Twig</li>
</ul>

<p>
    as well as related Zend Framework components. In most cases, integrations
    were developed for third party libraries <em>before</em> we wrote integrations
    with Zend Framework components!
</p>

<p>
    As such, Expressive is a small, single-purpose component that can integrate
    other components to create a custom middleware runtime for your applications.
</p>

<h2>Where does this fit with Zend Framework?</h2>

<p>
    We feel that PSR-7 opens new paradigms for both interoperability as well as
    for application design. Middleware offers a pattern for re-use and
    composability that is often far simpler to understand, and which often
    allows building complex applications from smaller pieces. As such, we want
    to provide an easy way to build middleware-based applications <em>now</em>.
</p>

<p>
    We will, however, continue to ship Zend Framework and its full-stack MVC.
    Many complex applications can benefit from the highly flexible structure it
    provides, and we plan to continue supporting those users well into the
    future. We also plan to add capabilities (quite soon!) for executing PSR-7
    based middleware from within Zend Framework applications; this provides
    migration paths in both directions for developers.
</p>

<h2>More Information and Roadmap</h2>

<p>
    Expressive is open source software, and we're trying to follow the mantra of
    "release early, release often." As such, our initial stable tag is at 0.1.0,
    and we're requesting that you start playing with it and letting us know what
    works and what doesn't. You can report issues on the <a href="https://github.com/zendframework/zend-expressive/issues">issue tracker</a>.
</p>

<p>
    One big push for us has been to document everything we can; you can currently
    <a href="http://zend-expressive.readthedocs.org/en/stable/">browse our documentation on ReadTheDocs</a>.
    If you have questions, changes, or additions you feel should be made, documentation
    is part of the code repository itself, and issues can be raised just as they can
    for code.
</p>

<p>
    While this is an initial offering, we've put a lot of thought into the various
    features and abstractions, and feel it is essentially feature complete. We do,
    however, have a bucket list of additional features we wish to support before
    we go stable:
</p>

<ul>
    <li>A skeleton application. Ideally, we would like Composer hooks that can
        ask which implementations for routing, container, and/or templating
        are desired. If you know how to do this, we'd love your help!</li>
    <li>Session encryption.</li>
    <li>HTTP Caching support.</li>
    <li>User authentication (via OAuth2 and/or other social auth mechanisms).</li>
</ul>

<p>
    Additionally, in the coming weeks, we'll be expanding our
    <a href="https://github.com/zendframework/zend-psr7bridge">PSR-7 &lt;-&gt; zend-http bridge</a>,
    and creating an alternate, PSR-7 middleware dispatcher that can be used
    with the ZF2 MVC.
</p>

<p>
    We welcome any assistance you as contributors can offer in these initiatives!
</p>

EOC;
$post->setExtended($extended);

return $post;
