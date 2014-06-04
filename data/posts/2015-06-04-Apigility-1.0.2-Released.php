<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setTitle('Apigility 1.0.2 Released!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2014-06-04 13:00', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2014-06-04 13:00', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
<p>We are pleased to announce the immediate availability of Apigility 1.0.2!</p>

<ul>
    <li><a href="https://apigility.org/download">https://apigility.org/download</a></li>
</ul>

<p>This is our second maintenance release of Apigility, fixing a number of issues,<br />and providing significant improvements for file upload capabilities.</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Upload Support</h2>

<p>Uploads were possible before this release, but were difficult to properly<br />enable. Additionally, PATCH and PUT requests required manually handling the file<br />uploads, as PHP does not natively support file uploads for those request<br />methods; the Zend Framework 2 InputFilter component, because it utilizes<br />PHP's native support for validating that an upload completed and for moving an<br />upload file to a new location, also could not deal with these methods.</p>

<p>This release makes the following changes in order to facilitate file uploads via<br />your Apigility API:</p>

<ul>
    <li>Content validation was altered to merge file upload data, when present, with<br /> the submitted API fields.<br /></li>
    <li>The Admin UI now allows you to mark a field as representing a file upload;<br /> this ensures that content validation will work properly, as content validation<br /> differs for file uploads.<br /></li>
    <li>The content negotiation module now provides emulation for PHP's file upload<br /> support when receiving PATCH and PUT requests, ensuring that the files are<br /> uploaded in the same manner, cleaned up post-request, and passed to validation<br /> properly. You should notice no difference between POST, PUT, or PATCH requests<br /> when performing file uploads, whether a single or multiple files are provided.</li>
</ul>

<p>File uploads are only done using the <code>multipart/form-data</code> media type. You will<br />need to add that media type to the content negotiation whitelist if you plan to<br />perform file uploads.</p>

<p>If you have further questions, you can <a href="https://apigility.org/documentation//recipes/upload-files-to-api">consult the<br />documentation</a>.</p>

<h2>Changelog</h2>

<p>While upload support is the major feature of this release, we fixed many other<br />issues.</p>

<h3>General</h3>

<ul>
    <li><p>All repositories have updated <code>composer.json</code> files that properly define the<br /> two branch aliases for the <code>master</code> and <code>develop</code> branches.</p></li>
    <li><p>All repositories have updated <code>README.md</code> files that provide a &quot;Requirements&quot;<br /> section linking to the <code>composer.json</code> file.</p></li>
</ul>

<h3>zf-apigility-admin</h3>

<ul>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/181">Fixes for the &quot;Encrypt&quot; and &quot;Compress&quot; filter adapters</a>,<br /> ensuring that these filters can be properly created and configured.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/182">Ability to specify file upload fields</a>.<br /> A field can now be marked as representing a file upload, ensuring it can then<br /> be validated correctly.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/171">Fix for unclosed link in authentication screen</a>,<br /> which was preventing edits and saves of authentication details.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/184">Remove charset option for Postgres adapters</a>,<br /> as that adapter does not support setting the character set.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/185">Added DSN to DB adapter input filter</a>,<br /> so that edits to an existing DB adapter will save when the DSN is provided.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/178">Fixes to the DB-Connected service model</a>,<br /> to ensure that update data is saved properly.</p></li>
</ul>

<h3>zf-apigility-documentation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-documentation/pull/9">Fixes HTTP status code for POST operations</a>,<br /> to now display <code>201</code> as a potential status.</li>
</ul>

<h3>zf-apigility-skeleton</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-skeleton/pull/63">Adds composer.phar to the skeleton</a>,<br /> since it should have always been there!</li>
</ul>

<h3>zf-content-negotiation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-content-negotiation/pull/18">Implements file uploads</a><br /> <a href="https://github.com/zfcampus/zf-content-negotiation/pull/20">via request body streaming</a><br /> for PUT and PATCH requests.</li>
</ul>

<h3>zf-content-validation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-content-validation/pull/14">Ensures file upload data is passed to validation</a>,<br /> which allows validating file uploads.</li>
</ul>

<h3>zf-deploy</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-deploy/pull/15">Ensures --gitignore flag is passed when copying source tree</a>.</li>
</ul>

<h3>zf-hal</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/35">Always store the original entity within ZF\Hal\Entity</a>,<br /> fixing an issue where REST controllers cast entities to arrays prior to<br /> creating the <code>ZF\Hal\Entity</code> instance, and thus causing listeners on<br /> <code>renderEntity</code> et. al. to receive data that could never be matched.</li>
</ul>

<h3>zf-oauth2</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-oauth2/pull/43">Pass all OAuth2 adapter options to oauth2-server-php</a>,<br /> enabling the ability to turn on refresh token re-issue, among other things.</li>
</ul>

<h2>Roadmap</h2>

<p>Many thanks for all the great issue reports and discussions on the <a href="http://bit.ly/apigility-users">mailing<br />list</a> and the various issue trackers!</p>

<p>We will do additional maintenance releases on an as-needed basis. The next<br />feature release, 1.1, is in development, and includes:</p>

<ul>
    <li>Doctrine-Connected REST services<br /></li>
    <li>Database Autodiscovery for REST services (think of this as DB-Connected that<br /> finds all your tables and proposes field configuration for you!)<br /></li>
    <li>Mongo-Connected REST services<br /></li>
    <li>HTTP Caching</li>
</ul>

<p>We would appreciate any feedback you can provide, either in the mailing lists,<br />in issues, or via comments on associated pull requests.</p>

<p>Stay tuned!</p>
EOC;
$post->setExtended($extended);

return $post;
