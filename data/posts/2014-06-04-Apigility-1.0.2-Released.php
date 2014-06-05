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

<p>This is our second maintenance release of Apigility, fixing a number of issues, and providing significant improvements for file upload capabilities.</p>

EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>Upload Support</h2>

<p>Uploads were possible before this release, but were difficult to properly enable. Additionally, PATCH and PUT requests required manually handling the file uploads, as PHP does not natively support file uploads for those request methods; the Zend Framework 2 InputFilter component, because it utilizes PHP's native support for validating that an upload completed and for moving an upload file to a new location, also could not deal with these methods.</p>

<p>This release makes the following changes in order to facilitate file uploads via your Apigility API:</p>

<ul>
    <li>Content validation was altered to merge file upload data, when present, with  the submitted API fields. </li>
    <li>The Admin UI now allows you to mark a field as representing a file upload;  this ensures that content validation will work properly, as content validation  differs for file uploads. </li>
    <li>The content negotiation module now provides emulation for PHP's file upload  support when receiving PATCH and PUT requests, ensuring that the files are  uploaded in the same manner, cleaned up post-request, and passed to validation  properly. You should notice no difference between POST, PUT, or PATCH requests  when performing file uploads, whether a single or multiple files are provided.</li>
</ul>

<p>File uploads are only done using the <code>multipart/form-data</code> media type. You will need to add that media type to the content negotiation whitelist if you plan to perform file uploads.</p>

<p>If you have further questions, you can <a href="https://apigility.org/documentation/recipes/upload-files-to-api">consult the documentation</a>.</p>

<h2>Changelog</h2>

<p>While upload support is the major feature of this release, we fixed many other issues.</p>

<h3>General</h3>

<ul>
    <li><p>All repositories have updated <code>composer.json</code> files that properly define the  two branch aliases for the <code>master</code> and <code>develop</code> branches.</p></li>
    <li><p>All repositories have updated <code>README.md</code> files that provide a &quot;Requirements&quot;  section linking to the <code>composer.json</code> file.</p></li>
</ul>

<h3>zf-apigility-admin</h3>

<ul>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/181">Fixes for the &quot;Encrypt&quot; and &quot;Compress&quot; filter adapters</a>,  ensuring that these filters can be properly created and configured.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/182">Ability to specify file upload fields</a>.  A field can now be marked as representing a file upload, ensuring it can then  be validated correctly.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/171">Fix for unclosed link in authentication screen</a>,  which was preventing edits and saves of authentication details.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/184">Remove charset option for Postgres adapters</a>,  as that adapter does not support setting the character set.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/185">Added DSN to DB adapter input filter</a>,  so that edits to an existing DB adapter will save when the DSN is provided.</p></li>
    <li><p><a href="https://github.com/zfcampus/zf-apigility-admin/pull/178">Fixes to the DB-Connected service model</a>,  to ensure that update data is saved properly.</p></li>
</ul>

<h3>zf-apigility-documentation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-documentation/pull/9">Fixes HTTP status code for POST operations</a>,  to now display <code>201</code> as a potential status.</li>
</ul>

<h3>zf-apigility-skeleton</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-apigility-skeleton/pull/63">Adds composer.phar to the skeleton</a>,  since it should have always been there!</li>
</ul>

<h3>zf-content-negotiation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-content-negotiation/pull/18">Implements file uploads</a>  <a href="https://github.com/zfcampus/zf-content-negotiation/pull/20">via request body streaming</a>  for PUT and PATCH requests.</li>
</ul>

<h3>zf-content-validation</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-content-validation/pull/14">Ensures file upload data is passed to validation</a>,  which allows validating file uploads.</li>
</ul>

<h3>zf-deploy</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-deploy/pull/15">Ensures --gitignore flag is passed when copying source tree</a>.</li>
</ul>

<h3>zf-hal</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-hal/pull/35">Always store the original entity within ZF\Hal\Entity</a>,  fixing an issue where REST controllers cast entities to arrays prior to  creating the <code>ZF\Hal\Entity</code> instance, and thus causing listeners on  <code>renderEntity</code> et. al. to receive data that could never be matched.</li>
</ul>

<h3>zf-oauth2</h3>

<ul>
    <li><a href="https://github.com/zfcampus/zf-oauth2/pull/43">Pass all OAuth2 adapter options to oauth2-server-php</a>,  enabling the ability to turn on refresh token re-issue, among other things.</li>
</ul>

<h2>Roadmap</h2>

<p>Many thanks for all the great issue reports and discussions on the <a href="http://bit.ly/apigility-users">mailing list</a> and the various issue trackers!</p>

<p>We will do additional maintenance releases on an as-needed basis. The next feature release, 1.1, is in development, and includes:</p>

<ul>
    <li>Doctrine-Connected REST services </li>
    <li>Database Autodiscovery for REST services (think of this as DB-Connected that  finds all your tables and proposes field configuration for you!) </li>
    <li>Mongo-Connected REST services </li>
    <li>HTTP Caching</li>
</ul>

<p>We would appreciate any feedback you can provide, either in the mailing lists, in issues, or via comments on associated pull requests.</p>

<p>Stay tuned!</p>
EOC;
$post->setExtended($extended);

return $post;
