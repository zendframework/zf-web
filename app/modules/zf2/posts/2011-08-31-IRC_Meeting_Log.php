<?php
$post = new stdClass;
$post->title  = '2011-08-31 IRC Meeting Log';
$post->author = (object) array(
    'name' => "Matthew Weier O'Phinney",
    'link' => 'http://weierophinney.net/matthew/',
);
$post->date = '2011-08-31 14:50';
$post->summary =<<<'EOS'
<p>
    Today we held our second Zend Framework community IRC meeting. Find out what
    we discussed, and where you can read the transcript.
</p>
EOS;

$post->content =<<<'EOC'
<p>
    Today we held our second Zend Framework community IRC meeting. The meeting 
    was held on Freenode IRC in the #zf2-meeting channel, and moderated by 
    Pieter Kokx. The meeting ran long (15 minutes over), had several heated 
    exchanges, but gained general consensus on a variety of topics.
</p>

<p>
    Specifically, we discussed:
</p>

<ul class="ul">
    <li>Modules - defining what "module" means, and differentiating it from 
        "component", as well as defining these terms in relation to "library" 
        and "application".
    </li>
    <li>Distribution categories - what broad categories we'll define as "buckets"
        for components, and which categories will be considered necessary for the 
        "standard distribution" -- which defines what needs to be done to be 
        considered stable.
    </li>
    <li>Timeframes for potential releases.</li>
</ul>

<p>
    The full transcript, as well as a summary of decisions, can be found on the
    <a href="http://framework.zend.com/wiki/display/ZFDEV2/2011-08-31+Meeting+Log">ZF2 
    wiki</a>.
</p>
EOC;

return $post;
