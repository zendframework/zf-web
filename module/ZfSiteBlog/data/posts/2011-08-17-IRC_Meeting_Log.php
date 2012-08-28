<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('2011-08-17 IRC Meeting Log');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2011-08-17 13:50', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2011-08-17 13:50', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    Today we held the first Zend Framework community IRC meeting. Find out what
    we discussed, and where you can read the transcript.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
    The meeting 
    was held on Freenode IRC in the #zf2-meeting channel, and moderated by 
    Pieter Kokx. While sometimes boisterous, sometimes off-topic, it definitely
    helped build some community spirit as well as consensus surrounding the 
    direction of ZF2.
</p>

<p>
    Specifically, we discussed:
</p>

<ul class="ul">
    <li>Format of meetings and how agendas will be created.</li>
    <li>The proposal process.</li>
    <li>Milestones towards ZF2's completion.</li>
</ul>

<p>
    The full transcript, as well as a summary of decisions, can be found on the
    <a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-08-17+Meeting+Log">ZF2 
    wiki</a>.
</p>
EOC;
$post->setExtended($extended);

return $post;

