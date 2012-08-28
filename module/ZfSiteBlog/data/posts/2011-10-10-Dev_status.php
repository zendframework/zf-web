<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-10-10 Dev status update');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-10-10 14:40', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-10-10 14:40', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    Status update for the weeks of 27 September - 10 October 2011.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2 id="toc_3.1">2011-09-28 IRC Meeting</h2>

<p>
We held our fourth IRC meeting on Wednesday, 28 September 2011. On the agenda
were:
</p>

<ul class="ul">
<li>
Discussion of a Doctrine Bridge
</li>
<li>
Discussion of the Standard Distribution
</li>
<li>
Roadmap
</li>
</ul>

<p>
The meeting
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-09-28+Meeting+Log">has been summarized previously</a>.
The <strong>tl;dr</strong> for those who don't want to click through on the link:
</p>

<ul class="ul">
<li>
Everyone agrees we'd like a Doctrine Bridge; it's up to somebody to get
   something concrete rolling.
</li>
<li>
The Standard Distribution was previously defined; however, additions will be
   considered on a case-by-case basis.
</li>
<li>
Expect beta 1 to drop during ZendCon.
</li>
</ul>

<h2 id="toc_3.2">Development Notes</h2>

<h3 id="toc_3.2.1">View Convenience API</h3>

<p>
After a <a href="http://zend-framework-community.634137.n4.nabble.com/ZF2-Status-Update-tp3845571p3845571.html">prolonged discussion</a>, 
I worked on a "View Convenience API". The goal was to simplify view usage and
syntax.
</p>

<p>
The end result is a syntax very much like ZF1, but more performant under the
hood, and with easier ways to get at the various helper objects. Per a
suggestion (and pull request!) from <a href="http://akrabat.com/">Rob Allen</a>, we
now extract view variables prior to rendering the view script. Since the
Variables container returns escaped versions of variables by default, this makes
for very succinct syntax. You may also access variables using property
overloading (e.g., <code>$this-&gt;foo</code>), via a <code>get()</code> method, or by accessing the
Variables container directly. Additionally, you can now access the unescaped
values using a new <code>raw()</code> method in the renderer.
</p>

<p>
Helpers can now be accessed via method overloading, as they were in ZF1. The
following will occur:
</p>

<ul class="ul">
<li>
If the helper is invocable (i.e., a functor, implementing the <code>__invoke()</code>
   magic method), it will be invoked with any arguments passed.
</li>
<li>
If not invocable, the helper instance will be returned.
</li>
</ul>

<p>
Additionally, an "escape" view helper was created. This is composed into the
Variables container by default, and allows specifying alternate callbacks and
encoding when desired.
</p>

<pre class="highlight">
&lt;?php $this->gt;headTitle($title) ?>
&lt;h2>gt;&lt;?= $title ?>&lt;/h2>
&lt;ul>gt;
&lt;?php foreach ($entries as $entry): ?>gt;
    &lt;li>gt;&lt;?= $this->escape($entry->getName()) ?>&lt;/li>
&lt;?php endforeach ?>gt;
&lt;/ul>gt;
</pre>

<p>
<strong>tl;dr:</strong> The view layer is fun again!
</p>

<h3 id="toc_3.2.2">Controller Convenience API</h3>

<p>
Following a couple
<a href="http://zend-framework-community.634137.n4.nabble.com/ActionController-convenience-API-tp3848772p3848772.html">short</a> <a href="http://zend-framework-community.634137.n4.nabble.com/Controller-convenience-API-redux-tp3873283p3873283.html">threads</a> about a controller convenience API, I
implemented a few new features in the MVC controller layer:
</p>

<ul class="ul">
<li>
Controllers may be optionally "event injectible". If they are, they will be
   injected with the <code>MvcEvent</code> used in the <code>Application</code> object. This allows it
   to be tied more closely into the full request cycle.
</li>
<li>
Controllers may be optionally "locator aware". If they implement the
   <code>LocatorAware</code> interface, they will be injected with the DI container or
   Service Locator attached to the Application. This can be used to pull out
   optional dependencies.
</li>
<li>
We've created a controller-layer plugin broker, analagous to ZF1's "action
   helper" system. The idea behind this is to provide re-usable functionality
   for controllers in a light-weight fashion. Unlike ZF1, these plugins will not
   be workflow-aware, and will only optionally be injected with the current
   controller (based on presence of a <code>setController()</code> method).
</li>
<li>
You can access controller plugins via method overloading. Unlike view
   helpers, method overloading only ever returns the plugin instance.
</li>
</ul>

<p>
So, in a nutshell:
</p>

<pre class="highlight">
class FooController extends ActionController
{
    public function processAction()
    {
        // Locator-awareness for pulling conditional functionality:
        $form = $this-&gt;getLocator()-&gt;get('foo-form');
        
        $post = $this-&gt;getRequest()-&gt;post()-&gt;toArray();
        if ($form-&gt;isValid($post)) {
            // do some processing
            // Redirection via a plugin:
            return $this-&gt;redirect()-&gt;toRoute('foo-success');
        }
    }
}
</pre>

<p>
Working controller plugins now include:
</p>

<ul class="ul">
<li>
<strong>Url</strong> (generates URLs from a configured router)
</li>
<li>
<strong>Redirect</strong> (updates the Response object with a redirect status code an Location
   header; Location URL can be either a static URL or generated from the router)
</li>
<li>
<strong>FlashMessenger</strong> (session-based flash messages)
</li>
<li>
<strong>Forward</strong> (dispatch an additional controller)
</li>
</ul>

<p>
The last plugin enables users to dispatch an additional controller -- without
requiring a dispatch loop within the <code>Application::run()</code> logic! It <em>does</em>,
however, require that the controller calling it be defined as <code>LocatorAware</code>, so
that it can obtain the configured controller instance.
</p>

<p>
<strong>tl;dr:</strong> The Controller layer is getting a lot of good functionality.
</p>

<h3 id="toc_3.2.3">Zend\Code refactoring</h3>

<p>
Ralph worked for several weeks on a refactoring of the <code>Zend\Code</code> component.
Part of this was moving <code>Reflection</code> and <code>CodeGenerator</code> under that tree, as
well as ensuring the APIs were consistent across all three components (as
<code>Zend\Code</code> also contains the <code>Scanner</code> component). This work was merged on 6
October 2011. 
</p>

<p>
Part of this update also included a comprehensive annotations parser. This
allows us to now scan docblocks for annotations and represent them as objects,
giving rise to a number of potential new workflows for ZF2.
</p>

<h3 id="toc_3.2.4">DI Updates</h3>

<p>
One key driver behind annotations support was to allow using annotations to hint
to the DI Compiler how to create definitions. Additionally, work was done to
allow creating definitions via configuration. This allows things like:
</p>

<pre class="highlight">
use Zend\Di\Definition\Annotation as Di;

class Foo
{
    /**
     * @Di\Inject()
     */
    public function setEvents(EventCollection $events)
    {
    }
}
</pre>

<p>
which will inform the DI container that this method should be injected at
construction. Additionally, you can now do things like this:
</p>

<pre class="highlight">
$config['di'] = array(
'definition' => array('class' => array(
    'Mongo' => array(
        '__construct' => array(
            'server'  => array(
                'required' => false,
                'type'     => false,
            ),
            'options' => array('required' => false),
        ),
    ),
    'MongoDB' => array(
        '__construct' => array(
            'conn' => array(
                'required' => true,
                'type'     => 'Mongo',
            ),
            'name' => array(
                'required' => true, 
                'type'     => false,
            ),
        ),
    ),
    'MongoCollection' => array(
        '__construct' => array(
            'db' => array(
                'required' => true,
                'type'     => 'MongoDB',
            ),
            'name' => array(
                'required' => true, 
                'type'     => false,
            ),
        ),
    ),
    'Blog\EntryResource' => array(
        'setCollectionClass' => array(
            'class' => array(
                'required' => false,
                'type'     => false,
            ),
        ),
    ),
)),
'instance' => array(
    'Mongo' => array('parameters' => array(
        'server'  => 'mongodb://localhost:27017',
    )),
 
    'MongoDB' => array( 'parameters' => array(
        'conn' => 'Mongo',
        'name' => 'myblogdb',
    )),
 
    'MongoCollection' => array('parameters' => array(
        'db'   => 'MongoDB',
        'name' => 'entries',
    )),
 
    'Blog\EntryResource' => array('parameters' => array(
        'dataSource' => 'CommonResource\DataSource\Mongo',
        'class'      => 'CommonResource\Resource\MongoCollection',
    )),
 
    'CommonResource\DataSource\Mongo' => array('parameters' => array(
        'connection' => 'MongoCollection',
    )),
));
</pre>

<p>
The above will ensure that we use the provided scalar values for injection in
the various constructors and setters in the definition, and still allows for
object injection when required (e.g., conn, db, dataSource, connection).
</p>

<p>
<strong>tl;dr:</strong> DI has become incredibly flexible and powerful!
</p>

<h3 id="toc_3.2.5">Cloud Infrastructure</h3>

<p>
Enrico managed to finish testing all <code>Zend\Cloud\Infrastructure</code> functionality,
with <em>both</em> online <em>and</em> offline tests! This gives us great confidence in the
components (plural, as all infrastructure services also have related components
under <code>Zend\Service</code>), and makes for a great new feature in ZF2. Enrico also
backported the Infrastructure component to ZF1's trunk, in anticipation of an
additional release on the ZF1 tree in the future.
</p>

<h3 id="toc_3.2.6">CLI Module</h3>

<p>
<a href="http://robertbasic.com/">Robert Basic</a> <a href="http://zend-framework-community.634137.n4.nabble.com/A-ZF2-Cli-module-tp3865659p3865659.html"> wrote to the list about a prototype CLI
module</a>. The basic idea is to leverage the module architecture for executing CLI
requests; routing would be performed utilizing <code>Zend\Console\Getopt</code> instead of
the MVC router. Additionally, it would provide functionality for colorizing
output. Overall, a solid beginning to a potential new console component!
</p>

<h3 id="toc_3.2.7">Presentations</h3>

<p>
Both <a href="http://akrabat.com">Rob Allen</a> and <a href="http://robertbasic.com">Robert Basic</a>
gave presentations during the weekend of 7 - 9 October 2011, with Robert Basic
giving a general overview session on ZF2 at <a href="http://webkonf.org/">Webkonf</a> and
Rob Allen giving a full day tutorial at <a href="http://phpnw.org.uk">PHPNW</a>. Rob Allen's
slides may be viewed
<a href="http://akrabat.com/wp-content/uploads/PHPNW11-ZF2-Tutorial.pdf">online</a>.
</p>

<h2 id="toc_3.3">The Future</h2>

<p>
We have an
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-10-12+Meeting+Agenda">IRC
meeting this coming Wednesday, 12 Oct 2011</a>; please post topics and/or vote on
those already posted -- and make sure you're in attendance for the discussions!
</p>

<p>
Also, we'll be tagging <strong>beta1</strong> this week, in preparation for a release during
ZendCon next week. If you have feedback on the MVC or DI, please let us know
ASAP!
</p>
EOC;
$post->setExtended($extended);

return $post;
