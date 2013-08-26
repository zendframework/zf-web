<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Zend Framework 2.2.4 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-08-26 11:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-08-26 11:45', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>
    The Zend Framework community is pleased to announce the immediate availability
    of Zend Framework 2.2.4! Packages and installation instructions are
    available at:
</p>

<ul>
    <li>
        <a href="/downloads/latest">http://framework.zend.com/downloads/latest</a>
    </li>
</ul>

<p>
    This release fixes a regression found in the Form component released with version
    2.2.3; if you use that component, we urge you to upgrade to 2.2.4.
</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'

<h2>Regressions</h2>

<p>
    <a href="/blog/zend-framework-2-2-3-released.html">Version 2.2.3</a> introduced a
    regression in the Form component, as a side-effect of fixing another issue.
    The <code>preferFormInputFilter</code> flag was originally created to allow 
    developers to choose whether they wanted to prefer the input filter they
    explicitly composed in the form to have priority, or use the input filter 
    settings the form aggregated from default elements instead. Interestingly,
    the form component essentially enforced the latter situation (prefering what
    the form aggregated), making the flag have no semantic meaning.
</p>

<p>
    A side effect of this, however, led to a regression in the InputFilter 
    component. Starting sometime in the 2.2 series, the behavior of input
    merging was changed to merge the old input into the new. In 2.2.3, we
    corrected this behavior -- but it broke the default merging order in the
    Form component. On inspection, we discovered that the fix to the 
    InputFilter essentially gave semantic meaning back to the 
    <code>preferFormInputFilter</code> flag -- but that the default behavior -- 
    which was to prefer what the form aggregates -- was now flip-flopped.
</p>

<p>
    The fix in 2.2.4 is to enable the <code>preferFormInputFilter</code> flag
    by default, thus restoring the previous expected behavior. Additionally,
    we now provide the ability to set this flag via form options or the form
    factory.
</p>

<p>
    If you use the Form component, we urge you to upgrade to 2.2.4 immediately.
</p>

<h2>Changelog</h2>

<p>
    To see the full changelog, visit:
</p>

<ul>
    <li><a href="/changelog/2.2.4">http://framework.zend.com/changelog/2.2.4</a></li>
</ul>

<h2>Thank You!</h2>

<p>
    Many thanks to Michaël Gallego and Michael Gooden for helping me troubleshoot
    the form issues!
</p>

<h2>Roadmap</h2>

<p>
    Maintenance releases happen monthly on the third Wednesday.
</p>

EOC;
$post->setExtended($extended);

return $post;
