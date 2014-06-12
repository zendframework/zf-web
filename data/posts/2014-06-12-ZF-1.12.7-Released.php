<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.7 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-06-12 15:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-06-12 15:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 1.12.7:
</p>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

<p>
    This release contains an important security fix in 
    <code>Zend_Db_Select</code>; we strongly encourage users of this component to 
    upgrade.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fixes</h2>

<p>
    One new security advisory has been made, and has been patched in 1.12.7:
</p>

<p>
    <a href="/security/advisory/ZF2014-04">ZF2014-04</a>, which mitigates 
    a potential SQL Injection (SQLi) vector when usiing ORDER BY clauses in
    <kbd>Zend_Db_Select</kbd>; SQL function calls were improperly detected, rendering
    ORDER clauses such as <kbd>MD5(1);drop table foo</kbd> unfiltered. The
    logic has been updated to prevent SQLi vectors, and users of this functionality
    are strongly encouraged to upgrade immediately.
</ul>

<p>
    For more information, follow the link above; if you use the component
    affected, please upgrade as soon as possible.
</p>

<h2>Important Changes</h2>

<p>
    In addition to the security fix above, a number of other important changes
    were made, including:
</p>

<ul>
    <li>Support for PHPUnit 4 and 4.1, both within the Zend Framework test suite
        and inside the <kbd>Zend_Test_PHPUnit</kbd> component.</li>
    <li>Backported support from ZF2 for recursive page removal within
        <kbd>Zend_Navigation</kbd>.</li>
    <li>Support within the <kbd>Hostname</kbd> validator for the newly released
        IANA top level domains.</li>
    <li>Forward-compatibility changes were made to ensure Zend Framework 1 will run on
        the upcoming PHP 5.6.</li>
</ul>

<p>
    For the complete list of changes, <a href="/changelog/1.12.7">read the changelog</a>.
</p>

<h2>Thank You!</h2>

<p>
    As always, I'd like to thank the many contributors who made this
    release possible, particularly Cassiano Dal Pizzol and Lars Kneschke for
    reporting the security vulnerability, and Enrico Zimuel for patching it.
</p>
EOC;
$post->setExtended($extended);

return $post;
