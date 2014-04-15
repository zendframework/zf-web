<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.7 and 2.3.1 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-04-15 15:05', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-04-15 15:05', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>2.2.7</strong></li>
    <li>Zend Framework <strong>2.3.1</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest#ZF2">http://framework.zend.com/downloads/latest#ZF2</a>
    </li>
</ul>

<p>
    While these are scheduled maintenance releases, they also contain important security
    fixes; we strongly encourage users to upgrade.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fixes</h2>

<p>
    One new security advisory has been made, and has been patched in both 2.2.7
    and 2.3.1.
</p>

<p>
    <a href="/security/advisory/ZF2014-03">ZF2014-03</a>, which mitigates 
    potential cross site scripting (XSS) vectors in multiple view helpers due 
    to inappropriate HTML attribute escaping. Many view helpers were using the 
    <kbd>escapeHtml()</kbd> view helper in order to escape HTML attributes. 
    This release patches them to use the <kbd>escapeHtmlAttr()</kbd> view 
    helper in these situations.  If you use form or navigation view helpers, or 
    "HTML element" view helpers (such as <kbd>gravatar()</kbd>, 
    <kbd>htmlFlash()</kbd>, <kbd>htmlPage()</kbd>, or 
    <kbd>htmlQuicktime()</kbd>), we recommend upgrading immediately.
</ul>

<p>
    For more information, follow the links above; if you use any of the components
    affected, please upgrade as soon as possible.
</p>

<h2>2.3.1</h2>

<p>
    In addition to the security fixes listed above, <strong>2.3.1</strong> 
    contains more than 80 bugfixes. In particular, a number of improvements were
    made to the behavior of nested form fieldsets and collection input filters 
    (which often go hand-in-hand).
</p>

<p>
    For the complete list of changes, <a href="/changelog/2.3.1">read the changelog</a>.
</p>

<h2>Thank You!</h2>

<p>
    As always, I'd like to thank the many contributors who made these
    releases possible! In particular, I'd like to thank the team at 
    <a href="https://roave.com">Roave</a>, who both reported and patched the
    ZF2014-03 security issue.
</p>

<h2>Roadmap</h2>

<p>
    Zend Framework 2 maintenance releases will happen bi-monthly, with the
    next one scheduled for mid-June, 2014. Releases may occur more frequently
    if security issues are reported.
</p>

EOC;
$post->setExtended($extended);

return $post;
