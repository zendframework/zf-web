<?php
$post = new stdClass;
$post->title  = '2011-08-17 IRC Meeting Log';
$post->author = (object) array(
    'name' => "Matthew Weier O'Phinney",
    'link' => 'http://weierophinney.net/matthew/',
);
$post->date = '2011-08-17 13:50';
$post->summary =<<<'EOS'
<p>
    Today we held the first Zend Framework community IRC meeting. Find out what
    we discussed, and where you can read the transcript.
</p>
EOS;

$post->content =<<<'EOC'
<p>
    Today we held our first Zend Framework community IRC meeting. The meeting 
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

return $post;

