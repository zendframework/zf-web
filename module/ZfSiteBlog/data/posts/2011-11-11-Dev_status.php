<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-11-11 Dev status update');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-11-11 12:10', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-11-11 12:10', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
We've been busy since the last update!
</p>

<p>
The last update was during the busy-ness of <a href="http://zendcon.com/">ZendCon</a>, where we <a href="http://framework.zend.com/zf2/blog/entry/Zend-Framework-2-0-0beta1-Released">announced the first beta release</a> of ZF2. The release was met with a lot of enthusiasm, and we've seen increased usage and testing of ZF2 in the weeks following.
</p>

<p>
Since then, let's recap what's been going on in ZF2 development.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2 id="toc_4.1">IRC Meetings</h2>

<h3 id="toc_4.1.1">26 October 2011 IRC Meeting</h3>

<p>
We held an IRC meeting the Wednesday immediately following ZendCon. During the
meeting, we discussed three items: a nascent ACL/RBAC RFC, differences between
RFCs and proposals, and module distribution and installation.
</p>

<p>
The conclusions were:
</p>

<ul class="ul">
<li>
Not enough details as to whether we need to refactor ACL, other than to take
   advantage of some SPL interfaces and classes. Somebody needs to spearhead
   this. Additionally, if those changes are made, the few calls for an RBAC
   component may be moot.
</li>
<li>
RFCs are for architectural changes or to discuss refactors/rewrites of
   <em>existing</em> components; proposals are for <em>new</em> components. Consensus is that
   we need more action and visibility from the CR-Team, and those on the team
   that were present took notes and followed up with a meeting.
</li>
<li>
Basically, the Modules distribution/instalation RFC is on hold until some
   other intitiatives (such as the CLI RFC) are finalized.
</li>
</ul>

<p>
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-10-26+Meeting+Log">Read the full log.</a>
</p>

<h3 id="toc_4.1.2">9 November 2011 IRC Meeting</h3>

<p>
Two weeks later (this week!) we had another IRC meeting, covering three separate
RFCs: Mail, Log, and CLI.
</p>

<p>
Both the Mail and Log RFCs were approved for development, with some
questions/changes/additions/etc. highlighted during the meeting.
</p>

<p>
The CLI RFC is still somewhat rough and needs additional detail, but is headed
in the right direction. 
</p>

<p>
<a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-11-09+Meeting+Log">Read the full log.</a>
</p>

<h2 id="toc_4.2">RFCs</h2>

<p>
Three new RFCs were added (and discussed):
</p>

<ul class="ul">
<li>
<a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+Log+refactoring">Zend\Log refactoring</a>
</li>
<li>
<a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+Mail+refactoring">Zend\Mail refactoring</a>
</li>
<li>
<a href="http://framework.zend.com/wiki/display/ZFDEV2/RFC+-+CLI">Console refactoring/CLI component/Tool refactoring</a>
</li>
</ul>

<p>
As noted in the section on IRC meetings, the Log and Mail RFCs have been
approved for development, and are on target for our second beta release. The CLI
RFC is still being revised, but is on target for a potential beta3 release.
</p>

<h2 id="toc_4.3">Development</h2>

<p>
Most development has centered on revisions due to feedback on beta1. In
particular, some new ideas have been fleshed out to simplify the module manager,
while simultaneously making it more flexible and easier to accomplish
initialization tasks (such as retrieving autoloading and configuration
artifacts, registering events, etc). You can <a href="https://gist.github.com/1348598">view a sample here</a>. Additionally, Ralph has been working to accommodate a
number of additional DI use cases identified by users testing the new MVC.
</p>

<p>
Matthew has removed all ZF1 MVC components and pushed them into a new module,
under "modules/ZendFramework1Mvc". As part of that work, he also identified all
components that had dependencies on the old MVC system (particularly the front
controller), and refactored them to remove those dependencies and support
dependency injection. This work is on the current master.
</p>

<p>
Enrico has been working on adapting the latest version of the Windows Azure SDK
to ZF2, as well as addressing it in the Zend\Cloud\Infrastructure component. We
should see this work hitting master early next week.
</p>

<p>
With the Mail and Log RFCs now ratified, development on these will progress, and
we should see fresh code for these components next week.
</p>

<p>
Finally, we announced that ZF2 contributions no longer require a CLA, effective 
immediately. Since the announcement, we've seen quite a number of new pull 
requests from new contributors, and we expect this trend to continue.
</p>

<h2 id="toc_4.4">Fin</h2>

<p>
These are exciting times for Zend Framework 2 development, and we encourage you
to get involved!
</p>
EOC;
$post->setExtended($extended);

return $post;

