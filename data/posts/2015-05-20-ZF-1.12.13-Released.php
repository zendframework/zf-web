<?php // @codingStandardsIgnoreFile
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.13 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2015-05-20 11:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2015-05-20 11:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of:
</p>

<ul>
    <li>Zend Framework <strong>1.12.13</strong></li>
</ul>

<p>You can download Zend Framework at:</p>

<ul>
    <li>
        <a href="/downloads/latest#ZF1">http://framework.zend.com/downloads/latest#ZF1</a>
    </li>
</ul>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Regression Fix</h2>

<p>
  Version 1.12.12 included a fix for <a href="/security/advisory/ZF2015-04">ZF2015-04</a>,
  which details message splitting vulnerabilities in <code>Zend_Mail</code> and <code>Zend_Http</code>.
  The fix, however, was slightly too strict, and did not allow integer or float values as HTTP header
  values. In most cases, this is not an issue, but several key headers such as <code>Content-Length</code>
  are typically provided as integers. As such, many components such as <code>Zend_XmlRpc</code>,
  <code>Zend_OpenId</code>, and a variety of <code>Zend_Service</code> components were affected.
</p>

<p>
  Version 1.12.13 contains a fix for this issue. If you use <code>Zend_Http</code>, or one of the
  various <code>Zend_Service</code>, <code>Zend_OpenId</code>, <code>Zend_Oauth</code> components,
  we highly recommend upgrading immediately.
</p>
EOC;
$post->setExtended($extended);

return $post;
