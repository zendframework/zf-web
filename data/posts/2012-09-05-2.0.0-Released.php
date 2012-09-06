<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.0 STABLE Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-09-05 21:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2012-09-05 21:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.0.0 <strong>STABLE</strong>! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>
EOS;
$post->setBody($body);

$extended =<<<'EOC'

<p>
    Zend Framework 2 has been years in the making. Version 1.0.0 was released
    in July 2007 -- over 5 years ago! Since then, we've incrementally added 
    features, building on the solid base we'd created.
</p>

<p>
    A lot of code has been written on those foundations -- estimates of ZF
    downloads run anywhere from hundreds of thousands to millions, depending
    on the statistics you look at. It's been used to build everything from
    hobbyist websites to powering some of the most visited sites on the web.
    We've done a lot with Zend Framework 1 over the years.
</p>

<p>
    We also exposed a lot of cracks in the foundations. Patterns we'd taken 
    for granted, such as Singletons, reared their ugly heads and showed us
    where we'd built in inflexibility and hard-coded dependencies. We
    realized that while we'd built a number of interfaces, our code was
    making assumptions that fell outside those definitions. Some code, while
    it felt elegant, was difficult to learn, or improperly intermingled 
    different architectural concerns, creating problems where we hadn't had
    any before.
</p>

<p>
    Zend Framework 2 aims to build on our experiences with Zend Framework 1,
    to learn both from our mistakes as well as our successes.
</p>

<p>
    It's not perfect. No software project is. But I think it's qualitatively
    <em>better</em>. We finally achieved a dream that was conceived in the 0.X
    days of Zend Framework, to be able to create and consume standalone modules
    of MVC code. Thanks to the efforts of <a href="http://blog.evan.pro/">Evan
    Coury</a>, this was made possible via a new component, the <a href="/manual/2.0/en/modules/zend.module-manager.intro.html">ModuleManager</a>,
    making the process trivially easy. Another problem that plagued ZF1 was how to get 
    dependencies into controllers -- the ZF1 solution of using a singleton
    (<code>Zend_Controller_Front</code>) to get at a registry (<code>Zend_Application_Bootstrap</code>) --
    was, quite simply, a testing nightmare. Today, via <a href="/manual/2.0/en/modules/zend.service-manager.intro.html">the ServiceManager</a>,
    or, if you're gutsy, <a href="/manual/2.0/en/modules/zend.di.introduction.html">Zend\Di</a>,
    we have standard, testable solutions; we have <a href="http://ralphschindler.com/">Ralph Schindler</a>
    to thank for both. Altering the workflow of the application or a single 
    component was often difficult -- the various hooks were either not 
    plentiful enough or difficult to arrange in different configurations. 
    Thanks to the <a href="/manual/2.0/en/modules/zend.event-manager.event-manager.html">EventManager</a>,
    these use cases are easily resolved.
</p>

<p>
    The end result is that writing applications is a lot more flexible, and
    in many cases easier.
</p>

<p>
    If you've worked significantly with ZF1, ZF2 will look alien to you. That's
    okay. We'll continue to support ZF1 with bugfixes and security updates for the
    next 18 to 24 months. But start learning ZF2 -- I think once you do, you'll
    be amazed and impressed by what you can accomplish. We plan to publish a migration
    guide in the coming weeks to make your transition simpler, and to encourage
    you to start utilizing the power of ZF2 sooner.
</p>

<p>
    I'd go into detail on all the new components, but I don't want to bore you.
    You can <a href="/changelog/2.0.0">read the changelog</a>, or attend one of the
    <a href="http://www.zend.com/en/company/events/">many upcoming ZF2 webinars</a>
    at zend.com. I'll be <a href="http://www.zend.com/en/company/news/event/1112_webinar-getting-started-with-zf2">hosting 
    a ZF2 Intro next Wednesday</a> if you want some guidance.
</p>

<p>
    If you're feeling brave and want to try out ZF2 today, we offer the following
    resources:
</p>

<ul>
    <li><a href="/downloads/latest">Our downloads page</a> details how to get your hands on the source code.</li>
    <li><a href="/downloads/skeleton-app">Our skeleton application</a> is a fast way to have a working application that you can start tweaking.</li>
    <li><a href="/downloads/phpcloud">Or try out ZF2 on phpcloud</a>, which will get you up and running without needing to even start a web server!</li>
</ul>

<p>
    ZF2 has been the project of many people over the last couple years. I'd especially
    like to call out my team, and the community review team (CR Team) for their efforts:
</p>

<ul>
    <li>Ralph Schindler (Zend)</li>
    <li>Enrico Zimuel (Zend)</li>
    <li>Rob Allen (CR Team)</li>
    <li>Pádraic Brady (CR Team)</li>
    <li>Evan Coury (CR Team)</li>
    <li>Maks3w (CR Team)</li>
    <li>Ryan Mauger (CR Team)</li>
    <li>Dolf Schimmel (CR Team)</li>
    <li>Ben Scholzen (CR Team)</li>
</ul>

<p>
    Take a moment to thank them when you get a chance -- without their efforts,
    there would not be a ZF2.
</p>

<p>
    Now, let's go build amazing things!
</p>
EOC;
$post->setExtended($extended);

return $post;
