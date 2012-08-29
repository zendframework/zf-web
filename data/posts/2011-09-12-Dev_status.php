<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-09-12 Dev status update');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-09-12 15:20', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-09-12 15:20', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    Zend Framework status update for the weeks of 30 August - 12 September 2011.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
Much has happened since our last update.
</p>

<h2 id="toc_3.1">2011-08-31 IRC Meeting</h2>

<p>
First, we held our
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-08-31+Meeting+Log"> second IRC
meeting</a> on Wednesday, 31 August 2011. The intended purpose of the meeting was
to discuss and vote on two RFCs,
<a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+ZF2+Modules">Modules</a> and
<a href="http://framework.zend.com/wiki/pages/viewpage.action?pageId=43745438">Distribution</a>.
Discussion was heated for much of the meeting, but a number of ideas were
clarified and ratified during the process. In particular, we gained consensus
surrounding the difference between components and modules, and started a
conversation surrounding what we may include in modules in the future.
Visit the
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-08-31+Meeting+Log">meeting log</a>
for more details.
</p>

<h2 id="toc_3.2">MVC Prototyping</h2>

<p>
Following the meeting, I created and published the first "official" MVC
prototype in a <a href="https://github.com/weierophinney/zf2/tree/prototype/mvc-module">branch of my repository</a>.
The prototype was created as a module (under "modules/Zf2Mvc") in order to also
prototype one suggested format for developing modules under ZF2. We had
<a href="http://zend-framework-community.634137.n4.nabble.com/ZF2-MVC-Prototype-tp3792474p3792474.html">two</a>
<a href="http://zend-framework-community.634137.n4.nabble.com/Updated-prototype-plus-quickstart-tp3797750p3797750.html">threads</a>
in the mailing list detailing and discussing the prototype. At the time of this
writing, all major feedback has been incorporated.
</p>

<p>
Building on top of the MVC prototype, I then created a new branch of the
<a href="https://github.com/weierophinney/zf-quickstart">zf-quickstart</a> project that
utilizes the new prototype, which also resulted in a 
<a href="http://zend-framework-community.634137.n4.nabble.com/Updated-prototype-plus-quickstart-tp3797750p3797750.html">fair bit of discussion</a>.
</p>

<p>
In IRC, <a href="http://akrabat.com">Rob Allen</a> and <a href="http://evan.pro">Evan Coury</a> took the
prototype and quickstart as a starting point for a "Module Manager" component
that could discover module configuration, autoloading, etc. Evan quickly
developed a new module, "Zf2Module," for exactly this purpose (code is
<a href="https://github.com/EvanDotPro/zf2/tree/prototype/mvc-module/modules/Zf2Module">on GitHub</a>.
After a few revisions, he created a <a href="https://github.com/EvanDotPro/zf2-sandbox">"sandbox" project</a>
that illustrates how one might start a project and gradually add modules to it
in order to enable new features. Included in the project is a basic homepage and
error page as a discrete module, based on the quickstart; a "user" module for
simple user authentication and registration; and the guest book from the
quickstart. 
</p>

<p>
<strong>The tl;dr</strong>: Lots of momentum on the MVC front, with viable MVC and module
system prototypes.
</p>
 
<h2 id="toc_3.3">Options, configuration, and what? Oh, My!</h2>

<p>
Early last week, <a href="http://ralphschindler.com">Ralph</a> wrote up 
<a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+Object+instantiation+and+configuration">a proposal for configuration</a> in ZF2,
and
<a href="http://zend-framework-community.634137.n4.nabble.com/RFC-ZF2-Object-Instantiation-And-Configuration-tp3794736p3794736.html">opened a thread in the mailing list</a>.
The response was fairly heated, and resulted in <a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+better+configuration+for+components">a counter-proposal</a> within an
hour or two by Artur Bodera, followed by
<a href="http://zend-framework-community.634137.n4.nabble.com/RFC-object-based-configuration-for-components-was-unified-API-tp3797085p3797085.html">more discussion on the list</a>, 
and then <a href="http://zend-framework-community.634137.n4.nabble.com/ZF2-Option-Arrays-vs-Parameter-Objects-tp3796184p3796184.html">even more discussion</a>.
</p>

<p>
After the dust settled, the basic consensus appears to be:
</p>

<ul class="ul">
<li>
Denote hard dependencies in the constructor (if no sane default is likely)
</li>
<li>
Aggregate "soft" dependencies (i.e., optional configuration) as
   component-specific "configuration objects", which will allow:
</li>
<ul class="ul">
<li>
Moving option validation into configuration objects
</li>
<li>
Resulting in fewer necessary internal variables (pull these options from
      the config object)
</li>
<li>
Re-use of configuration objects with many instances
</li>
<li>
Ability to create config object extensions with application-specific
      defaults
</li>
<li>
Better hinting for IDEs
</li>
<li>
Potentially easier to document options
</li>
</ul>
</ul>

<p>
However, we still need to vote on this topic. (Hint: I'll be proposing it for
this week's IRC meeting.)
</p>

<h2 id="toc_3.4">Pull Requests, Sweet Pull Requests</h2>

<p>
I managed to merge in something like 50 new pull requests in the past week. Keep
'em coming!
</p>

<h2 id="toc_3.5">The Future</h2>

<p>
We've been reviewing options for distributing ZF and potentially modules. At
this time, candidates include <a href="http://pear.php.net">PEAR</a>, <a href="http://pear2.php.net">
Pyrus</a>, <a href="http://packagist.org">Composer</a>, or going home-grown. We've been
reviewing our options, and doing some prototyping, and hope to have a
recommendation this week.
</p>

<p>
I've been working on some proposals surrounding the View layer, and getting some
initial feedback from interested parties before posting some RFCs; they should
be posted early this week, however.
</p>

<p>
There are new RFCs and discussions erupting daily on the mailing list and in
the #zftalk.2 IRC channel on Freenode; I encourage you to subscribe to the
former and join in the latter so that you can participate.
</p>
EOC;
$post->setExtended($extended);

return $post;
