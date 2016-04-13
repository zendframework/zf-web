<?php // @codingStandardsIgnoreFile
use League\CommonMark\CommonMarkConverter;
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('https://mwop.net/');

$markdown = new CommonMarkConverter();

$post = new EntryEntity();
$post->setTitle('Zend Framework 1.12.18 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-04-13 11:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-04-13 11:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
The Zend Framework community is pleased to announce the immediate availability
of:

- Zend Framework **1.12.18**

You can download Zend Framework at:

- [http://framework.zend.com/downloads/latest#ZF1](http://framework.zend.com/downloads/latest#ZF1)

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Security Fixes

Zend Framework 1.12.18 includes a fix for [security advisory ZF2016-01](/security/advisory/ZF2016-01),
a potential insufficient entropy vulnerability in a number of methods exposed
in Zend Framework 1, including:

- `Zend_Ldap_Attribute::createPassword`
- `Zend_Form_Element_Hash::_generateHash`
- `Zend_Gdata_HttpClient::filterHttpRequest`
- `Zend_Filter_Encrypt_Mcrypt::_srand`
- `Zend_OpenId::randomBytes`

Moreover, the fix mitigates a flaw in `openssl_random_pseudo_bytes()`, ensuring
sufficient entropy will be used for any random number generated.

## Other changes

In addition to the security patch, the release includes fourteen other patches,
primarily around documentation. You can view a full list at:

- [Zend Framework 1.12.18 Changelog](/changelog/1.12.18)

Many thanks to our contributors, and particularly the maintainers who
coordinated this version: Frank Brückner, Rob Allen, and Enrico Zimuel.

EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
