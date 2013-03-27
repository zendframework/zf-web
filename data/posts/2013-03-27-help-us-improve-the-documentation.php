<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('robertbasic');
$author->setName("Robert Basic");
$author->setEmail('robertbasic.com@gmail.com');
$author->setUrl('http://robertbasic.com/');

$post = new EntryEntity();
$post->setId('2013-03-27-help-us-improve-the-documentation');
$post->setTitle('Help us improve the documentation!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(false);
$post->setCreated(new DateTime('2013-03-27 10:00', new DateTimezone('Europe/Belgrade')));
$post->setUpdated(new DateTime('2013-03-27 10:00', new DateTimezone('Europe/Belgrade')));
$body =<<<'EOS'

EOS;
$post->setBody($body);

$extended =<<<'EOC'

EOC;
$post->setExtended($extended);

return $post;

