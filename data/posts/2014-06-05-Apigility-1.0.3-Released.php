<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-06-05 11:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-06-05 11:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>We are pleased to announce the immediate availability of Apigility 1.0.3!</p>

<ul>
    <li><a href="https://apigility.org/download">https://apigility.org/download</a></li>
</ul>

<p>This is our third maintenance release of Apigility, and the first containing security fixes; read on for more information.</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Security Fixes</h2>

<p>We were notified by <a href="https://github.com/stefanotorresi">Stefano Torresi</a> of a potential security vector in <code>ZF\Apigility\DbConnectedResource</code>. While the <code>create()</code> method of that class pulls data from the composed input filter, if present, the <code>patch()</code> and <code>update()</code> methods were not, making it possible for an attacker to send unwanted data to the API service.</p>

<p>We have updated the class to pull from the composed input filter, if present, for each of the <code>create()</code>, <code>patch()</code>, and <code>update()</code> methods.</p>

<p>If you use DB-Connected REST resources in Apigility, we <strong>strongly</strong> recommend updating immediately. You can do so by running <code>composer update</code> inside your application.</p>

<p><a href="/security/advisory/AG2014-01">Read the security advisory for more details.</a></p>

<h2>Deployment Fixes</h2>

<p>We were notified that the logic for finding a Composer executable in <a href="https://github.com/zfcampus/zf-deploy">zf-deploy</a> would fail in some situations. A fix was contributed that better resolves the path to the executable, particularly in situations where a <code>composer.phar</code> must first be downloaded.</p>

<p>Additionally, in reviewing this fix, <a href="https://twitter.com/ezimuel">Enrico Zimuel</a> noted that we needed test coverage for most of the ZFDeploy functionality; we now have a comprehensive set of tests, all passing!</p>

<h2>Documentation Fixes</h2>

<p><a href="https://github.com/kaloyan-raev">Kaloyan Raev</a> noted that the HTML documentation does not render properly under Internet Explorer, due to the order in which media type selectors are matched. A swap in the order fixes the situation, and continues to work as expected under other browsers.</p>

<h2>Thank You!</h2>

<p>Many thanks to Stefano Torresi for working with us on the DB-Connected security issue, to Kaloyan Raev for his assistance with zf-deploy and the documentation, and to Enrico Zimuel, for the huge amount of testing he added to zf-deploy!</p>
EOC;
$post->setExtended($extended);

return $post;
