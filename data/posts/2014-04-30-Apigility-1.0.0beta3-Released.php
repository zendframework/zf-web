<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.0beta3 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-04-30 15:30', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-04-30 15:30', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>We are pleased to announce the immediate availability of Apigility 1.0.0beta3!</p>

<ul>
    <li><a href="http://apigility.org/download">http://apigility.org/download</a></li>
</ul>

<p>This is our third -- and likely last! -- beta release of Apigility! The features in this release are mainly around stabilization.</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Deployment and Console</h2>

<p>Most of the work we've performed since beta2 was on <a href="https://github.com/zfcampus/zf-deploy">zf-deploy</a>, our packaging/deployment tool. We made the following changes:</p>

<ul>
    <li>First, the tool can now be used both within an application, as well as a  standalone <a href="http://php.net/phar">phar</a> file. When used as a standalone phar  file, you can now self update it with the <code>self-update</code> command; this will  check <a href="https://packages.zendframework.com/">https://packages.zendframework.com/</a> for any new versions, and, if found,  do an &quot;in-place&quot; update of the tool. (Note: the library we use that provides  this functionality often emits PHP fatal errors; in practice, however, we've  noticed that the process works even when such errors are reported.) </li>
    <li>Second, we were not happy with the argument handling we were using, nor with  the strong coupling of console argument parsing logic with the logic consuming  the arguments. We built a small microframework around <code>Zend\Console</code>'s  <code>RouteMatcher</code> subcomponent that provides more flexibility around argument  handling, and are now shipping this in the module  <a href="https://github.com/zfcampus/zf-console">zf-console</a>.</li>
</ul>

<p>In addition to these architectural changes, we also implemented two new features in the tool:</p>

<ul>
    <li>You can specify a directory with the &quot;local&quot; configuration you want to use in  the deployment package via the <code>--configs</code> argument. </li>
    <li>You can specify a directory containing the ZPK skeleton you wish to use when  creating a ZPK (Zend Server deployment package); this directory should contain  a <code>deployment.xml</code> and any scripts you will be using.</li>
</ul>

<p>With these changes, we feel that <code>zf-deploy</code> is ready for inclusion in a stable Apigility release!</p>

<h2>Documentation Updates</h2>

<p>We've received a number of documentation improvements since beta2, and added a few documents as well.</p>

<p>In particular:</p>

<ul>
    <li>An error was spotted in the &quot;HAL for RPC services&quot; recipe that made the code  unusable. </li>
    <li>We've documented how to use the Zend Server SDK to deploy ZPK packages. </li>
    <li>We've added a recipe for adding Apigility modules to an existing Zend  Framework 2 application. </li>
    <li>We've added documentation on the new <code>zf-console</code> module.</li>
</ul>

<h2>Beta3 Updates</h2>

<p>In addition to the deployment and console tooling, we made the following changes:</p>

<ul>
    <li>We updated the <code>SendResponse</code> listener in <code>zf-api-problem</code> to merge in any  headers set in the application response object before sending the API Problem  response. This ensures that any previously set headers are also set -- solving  several issues observed when using third-party modules for Cross Origin  Resource Sharing (CORS), as well as HTTP authentication. </li>
    <li>The storage adapters we ship with <code>zf-oauth</code> now both allow you to specify  configuration via a <code>storage_settings</code> key; this change allows you to specify  custom tables for both the PDO and Mongo adapters. </li>
    <li>We've updated the &quot;api.enable&quot; service to ensure it works with current  versions of Apigility (and no longer raises errors!). </li>
    <li>We no longer display a resource class in the &quot;Source Code&quot; tab of a service if  a corresponding class does not exist (e.g., DB-Connected resource classes are  virtual services). </li>
    <li>We've added the option to recursively delete the directory for a service to  the Admin API; the Admin UI now presents a checkbox with this option. </li>
    <li>We've added the option to delete an entire API. By default, this only removes  the API's module from application configuration; however, you have the option  to recursively delete the API module as well (once again presented in the  Admin UI via a checkbox). </li>
    <li>We now generate factories for RPC controllers and REST resource classes when  creating new services. This simplifies the story for dependency injection of  these classes.</li>
</ul>

<h2>Roadmap</h2>

<p>At this time, we feel Apigility has become very stable, and that we have addressed the most pressing usability issues. We anticipate issuing a stable release next week (week of 5 May 2014).</p>
<p>As noted in previous beta announcements, reaching stability is only the first step, however! Features such as &quot;Doctrine-Connected&quot;, &quot;Mongo-Connected&quot;, and &quot;DB-Autodiscovery&quot; REST services are already either implemented or will be soon, and we will be debuting these in a 1.1 version in the very near future.</p>
EOC;
$post->setExtended($extended);

return $post;
