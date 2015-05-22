<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Announcing the Zend Framework 3 Roadmap');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-01-21 11:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-01-21 11:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
  The most often-asked questions we get around the Zend Framework project
  include: Where is Zend Framework heading? When will Zend Framework 3 be
  released? What changes and enhancements should we expect?
</p>

<p>
  Since inception, our goal for Zend Framework has been to further the art of
  PHP and ensure our users concentrate on the business logic of their
  application rather than wasting time reinventing the plumbing. The plumbing is
  Zend Framework’s job. We have continued to evolve ZF with best-in-class web
  development practices, and have innovated in areas where we saw gaps; as an
  example, we observed developers struggling with API development, which led us
  to create the Apigility project on top of ZF2.
</p>

<p>
  We have built an incredibly powerful framework with Zend Framework 2 that met
  its key goals of flexibility, consistency and testability. However, the world
  has changed since ZF2 was released, and the project needs to move with the
  times. With that in mind, we have gathered feedback from our users and core
  contributors to map the path forward.
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
  Zend Framework 3 will be an evolution from ZF2, concentrating on simplicity,
  reusability, and performance.
</p>

<p>What’s involved?</p>

<ul>
  <li><strong>Separating components into individual, versioned
    projects.</strong> This enables broader re-use and higher velocity of
    innovation.</li>
  <li>Strong emphasis on <strong>HTTP messages</strong>, with Matthew
    leading the <a href="http://www.php-fig.org/psr/psr-7/">PSR-7
    specification</a>.</li>
  <li><strong>Updating our existing full-stack MVC framework</strong> to depend
    on the newly independent components for better reuse and simplicity. ZF2 MVC
    applications will have a documented upgrade path to ZF3 requiring minimal
    changes.</li>
  <li><strong>Embracing <a 
    href="https://mwop.net/blog/2015-01-08-on-http-middleware-and-psr-7.html">middleware</a></strong> 
    runtime patterns as a lighter weight <em>alternative</em> to the enterprise 
    MVC framework stack.</li>
  <li>Enabling Apigility to work as a middleware stack, for better performance
    and simplicity, with the same streamlined, powerful user experience.</li>
  <li><strong>Optimizing for PHP 7</strong>, but <strong>supporting PHP
    5.5</strong> onwards.</li>
</ul>

<p>
  We have already done a lot of thinking (and coding!) in this direction, and we
  intend to release ZF3 in Q3 2015 &mdash; yes, this year!
</p>

<p>
  As a community project, we need everyone's help to make our plans a reality.
  Please join the effort and help us create Zend Framework 3! You can do so in
  the <a href="http://framework.zend.com/archives/subscribe/">zf-contributors
  mailing list</a>, or via the #zftalk.dev Freenode IRC channel.
</p>

<p>
  We are very excited about the changes to come, and hope you are as well!
</p>

<center>
  <p><b>&mdash; The ZF Team &mdash;</b></p>
</center>

<p>
  P.S. We will be posting some additional, more detailed thoughts regarding our
  observations, the statement of direction, timelines, and technology choices we
  are making in more detailed, follow-up posts. Keep an eye on our blog for
  these updates.
</p>
EOC;
$post->setExtended($extended);

return $post;
