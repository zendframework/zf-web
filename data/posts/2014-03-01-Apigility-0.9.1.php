<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 0.9.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-03-01 12:20', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-03-01 12:20', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>Today, we're releasing version 0.9.1 of Apigility! You can grab and test it
using one of the following two methods:</p>
<ul>
    <li>Composer:
        <code>composer create-project zfcampus/zf-apigility-skeleton apigility 0.9.1</code>
    </li>
    <li>Manual download:
        <pre><code>
        wget https://github.com/zfcampus/zf-apigility-skeleton/releases/download/0.9.0/zf-apigility-skeleton-0.9.1.zip
        unzip zf-apigility-skeleton-0.9.1.zip
        </code></pre>
    </li>
</ul>

<p>This release is a maintenance release, fixing two critical issues reported against 0.9.0</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Fixes</h2>

<ul>
    <li>
        <p><a href="https://github.com/zfcampus/zf-oauth2/issues/27">zfcampus/zfoauth2#27</a>
        reported an inability to save OAuth2 adapter details from the Apigility admin
        UI. These are now corrected.</p>
    </li>

    <li>
        <p><a href="https://groups.google.com/a/zend.com/d/msgid/apigility-users/b7723f69-e4cc-4619-84d8-c3dd8c1f93a5%40zend.com">A report on the apigility-users mailing
        list</a>
        indicated that authorizations performed against REST entities were not working
        correctly. This was due to an incomplete change from "resource" to "entity"
        (as noted in the 0.9.0 release notes); the situation is now corrected.</p>
    </li>
</ul>

<h2>Future</h2>

<p>
    At this point, we turn our attention to stabilizing Zend Framework 2.3.0, 
    on which Apigility will depend, due to features added to that upcoming
    version.
</p>

<p>
    Once Zend Framework 2.3.0 is released, we will begin the beta cycle for
    Apigility 1.0.0. During that timeframe, we will due some additional improvements
    to the UI, and work to ensure the engine is stable. Additionally, we will
    document the project, providing documentation for each module, as well as
    for how the modules work together as a whole. We hope to provide "recipes"
    for a number of common practices and development and deployment situations.
</p>

EOC;
$post->setExtended($extended);

return $post;
