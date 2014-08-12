<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.3.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-08-11 12:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-08-11 12:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.3.2!
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This is the second maintenance release in the 2.3 series, and resolves more than 100 issues.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Notable Changes</h2>

<p>
    The following changes are noted as being fixes that may have potential implications
    for existing applications.
</p>

<ul>
    <li><a href="https://github.com/zendframework/zf2/pull/6295">#6295</a>
        introduces a slight change to how <kbd>Zend\Form\Fieldset</kbd> handles disabled 
        values. Previously, they were represented in the form, and still 
        processed on submit, which allowed the possibility of changing the 
        value. This pull request modifies the behavior to extract the original 
        value from any bound data if present and use that value instead, which 
        is the correct behavior.
    </li>

    <li><a href="https://github.com/zendframework/zf2/pull/6423">#6423</a>
        modifies the behavior of <kbd>Zend\Validator\File\UploadFile</kbd> to only 
        return the <kbd>FILE_NOT_FOUND</kbd> error if upload was successful; previously, 
        it incorrectly would report this error even if an error occurred during 
        upload.
    </li>
</ul>

<p>For the full changelog, visit:</p>

<ul>
    <li><a href="/changelog/2.3.2">http://framework.zend.com/changelog/2.3.2</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    A big thank you to the host of contributors who helped make this
    maintenance release a reality!
</p>

EOC;
$post->setExtended($extended);

return $post;
