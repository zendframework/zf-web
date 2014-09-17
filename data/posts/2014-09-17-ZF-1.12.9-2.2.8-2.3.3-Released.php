<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.9, 2.2.8, and 2.3.3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-09-17 10:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-09-17 10:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.9</strong></li>
    <li>Zend Framework <strong>2.2.8</strong></li>
    <li>Zend Framework <strong>2.3.3</strong></li>
</ul>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    These are security releases; we strongly encourage users to upgrade.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Security Fixes</h2>

<p>
    Two new security advisories have been made:
</p>

<ul>
    <li><a href="/security/advisory/ZF2014-05">ZF2014-05</a>, which mititages
        null byte poisoning of the password provided for LDAP authentication,
        thus prevening unauthorized LDAP binding. This corrects for unpatched
        versions of PHP (versions 5.5.11 and below, 5.4.27 and below, and any
        prior releases).
    </li>

    <li><a href="/security/advisory/ZF2014-06">ZF2014-06</a>, which mitigates
        null byte poisoning of quoted SQL values provided to the sqlsrv extension,
        thus preventing a potential SQL injection vector.
    </li>
</ul>

<p>
    For more information, follow the links above; if you use either of the components
    affected, please upgrade as soon as possible.
</p>

<h2>Thank You!</h2>

<p>
    Thank you to the two reporters of the security issues, Matthew Daley (LDAP
    vulnerability) and Jonas Sandström (sqlsrv vulnerability).
</p>

EOC;
$post->setExtended($extended);

return $post;
