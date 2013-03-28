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
<p>
    A piece of software is only as good as it's documentation is. The Zend Framework team and a dozen or so of contributors are working hard to improve the Zend Framework 2 documentation, but we still want You to help us improve it even more. Any kind of help is welcomed and greatly appreciated.
</p>

<p>
    First of all, you don't have to have a major in English literature to help us with the documentation. We, the contributors, come from all over the world and many of us use English as a second, or third language, but that does not stop us from trying to improve the docs. Neither should it stop you.
</p>

<p>
    We are not asking for much, don't be affraid. Read a chapter or two, or even just a paragraph. Point us to parts of the documentation that are confusing, unclear, not explained well enough. Would it be easier for you to understand a section if we would add a picture or two? Tell us. Do you want to see more code examples? Let us know. Pick a component that you use most often and work with the contributors to have the documentation for that component as clear as possible. Have something to add? Send a pull request.
</p>

<p>
    We all learn in different ways and what documentation might be good for one developer, it might be confusing for another. That's why we are striving to have several different ways of presenting the material: reference manual, tutorials, user guides, API docs. Let us know what works best for you and we will do our best to make it happen.
</p>

<p>
    We would like to ask you one thing, though. Any and all problems, requests, ideas you have that can help us improve the documentation, please report them on <a href="https://github.com/zendframework/zf2-documentation/issues">the issue tracker on Github</a>. Thanks!
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<p>
    If you'd like to start contributing to the documentation by submitting patches and improvements, best start is to read the <a href="https://github.com/zendframework/zf2-documentation/blob/master/CONTRIBUTING.md">contributing guide</a>.
</p>

<p>
    Just so you know that we do really work on improving the documentation, a week or so ago we held our first dochunt. It was a weekend dedicated to improving the documentation and <a href="https://gist.github.com/Bittarman/27575710b347bdb73631">as you can see from the results</a> quite a few fixes and improvements were made thanks to the contributors. In the coming weeks we will hold the next dochunt. This time we will try to strictly focus on writing new and improving existing tutorials, but any and all contributions are welcomed.
</p>

<p>
    We don't know yet when the second dochunt will be held, but we will let you know. Until then, <a href="http://framework.zend.com/manual/2.1/en/index.html">start reading through the manual</a>, look for things to improve, what's missing and <a href="https://github.com/zendframework/zf2-documentation/issues">give us feedback</a>!
</p>

<p>
    The contributors and the Zend Framework team thank you!
</p>
EOC;
$post->setExtended($extended);

return $post;

