<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-08-30 Dev status update');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-08-30 15:20', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-08-30 15:20', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    Zend Framework status update for the week of 22 - 29 August 2011.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
Since the last community update, we've had a number of successes... as well as
setbacks.
</p>

<p>
First, completion of the initial HTTP component development took a bit longer
than anticipated. As a team we felt the need to ensure that we had at least the
basic documentation covered, and in doing so, uncovered additional use cases
that needed fixing. We felt this was time well-invested however, as most code
currently using <code>Zend\Http\Client</code> should work with little to no modification
from the original --- while sporting a much better design that better separates
concerns between a request object, response object, and the client invoking
them. 
</p>

<p>
This allows us to announce a new developer snapshot, 2.0.0dev4:
</p>

<ul class="ul">
    <li>Zip: <a href="http://framework.zend.com/releases/ZendFramework-2.0.0dev4/ZendFramework-2.0.0dev4.zip">http://framework.zend.com/releases/ZendFramework-2.0.0dev4/ZendFramework-2.0.0dev4.zip</a></li>
    <li>Tar: <a href="http://framework.zend.com/releases/ZendFramework-2.0.0dev4/ZendFramework-2.0.0dev4.tar.gz">http://framework.zend.com/releases/ZendFramework-2.0.0dev4/ZendFramework-2.0.0dev4.tar.gz</a></li>
</ul>

<p>
Also in this release, we were able to convert documentation to DocBook 5,
<code>Zend\Dojo</code> was brought up-to-date with changes in ZF1, and much, much more due
to the efforts of a large number of contributors who have submitted an
unprecedented number of pull requests in the past several weeks.
</p>

<p>
Second, we also had a number of infrastructure issues. Our mailing list went
black for 3-4 days, and no real solution was found. However, the pipes appear to
be fully open at this point, and we've had some great discussion over the
weekend and early this week.
</p>

<h2>RFCs</h2>

<p>
A couple of RFCs have been posted:
</p>

<ul class="ul">
    <li>
        <a href="http://framework.zend.com/wiki/pages/viewpage.action?pageId=43745438">RFC - What will the ZF2 distribution include?</a></li>
        <ul class="ul">
            <li><a href="http://zend-framework-community.634137.n4.nabble.com/RFC-What-will-the-ZF2-distribution-include-tp3763888p3763888.html">Mailing list discussion</a></li>
        </ul>
    </li>
    <li>
        <a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+ZF2+Modules">RFC - ZF2 Modules</a>
        <ul class="ul">
            <li><a href="http://zend-framework-community.634137.n4.nabble.com/RFC-ZF2-Modules-tp3776616p3776616.html">Mailing list discussion</a></li>
        </ul>
    </li>
</ul>

<p>
If you have any input, we'd appreciate having it ASAP, so that we can finalize
these and move on to defining discrete development tasks.
</p>

<h2>IRC Meeting this week</h2>

<p>
We have another IRC meeting at 17:00 UTC on 31 Aug 2011. The <a 
href="http://framework.zend.com/wiki/display/ZFDEV2/2011-08-31+Meeting+Agenda">agenda 
is on the wiki</a>, and we invite you to add/comment/vote on topics prior to 
the meeting. Votes will be closed approximately 1 hour prior to the meeting.
</p>

<h2>Current development</h2>

<p>
We're planning on working on a few items in the next week or so, roughly in
order of priority:
</p>

<ul class="ul">
<li>Creation of a dedicated branch for the MVC prototype currently in the zf-quickstart project.
</li>
<li>
Investigation of <a href="http://pear2.php.net">Pyrus</a> to see if it can meet the
   requirements set forth by the community on both distribution of the framework
   as well as potentially modules.
</li>
<li>
Investigation of <a href="http://php.net/phar">phar</a> as a foil to the above (but more
   particularly for module distribution).
</li>
<li>
Continued collaboration on the Router.
</li>
</ul>
EOC;
$post->setExtended($extended);

return $post;
