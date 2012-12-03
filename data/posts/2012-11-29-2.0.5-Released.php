<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.0.5 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2012-11-25 16:00', new DateTimezone('Europe/Paris')));
$post->setUpdated(new DateTime('2012-11-25 16:00', new DateTimezone('Europe/Paris')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.0.5! Packages and installation instructions are
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

<h2>Security Announcement</h2>

<p>
    This release is a security release, and contains fixes to both the 
    <code>Zend\Session\Validator\RemoteAddr</code> and <code>Zend\View\Helper\ServerUrl</code>
    classes. If you are using either, we recommend upgrading immediately. For more
    information, please read the <a href="/security/advisory/ZF2012-04">ZF2012-04 advisory details</a>.
    Thanks goes to Fabien Potencier for alerting us of the issues and working with us
    on appropriate fixes.
</p>


<h2>Changelog</h2>

<p>
    In addition to the security fixes mentioned above, this release included 
    five other patches, mostly trivial. The full list is as follows:
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/issues/3004">3004: Zend\Db unit tests fail with code coverage enabled</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3039">3039: combine double if into single conditional</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3042">3042: fix typo 'consist of' should be 'consists of' in singular</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3045">3045: Reduced the #calls of rawurlencode() using a cache mechanism</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3048">3048: Applying quickfix for zendframework/zf2#3004</a></li>
    <li><a href="https://github.com/zendframework/zf2/issues/3095">3095: Process X-Forwarded-For header in correct order</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to all contributors to this release! 
</p>

<h2>Reminder</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday. Additionally, 
    we have the next minor release, 2.1.0, slated for sometime next month.
</p>

EOC;
$post->setExtended($extended);

return $post;
