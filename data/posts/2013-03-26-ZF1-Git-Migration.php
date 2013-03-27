<?php
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('http://mwop.net/');

$post = new EntryEntity();
$post->setId('2013-03-27-zf1-git-migration');
$post->setTitle('Zend Framework 1 is Migrating to Git!');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2013-03-27 11:45', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2013-03-27 11:45', new DateTimezone('America/Chicago')));

$body =<<<'EOS'
<p>
    Since its inception, Zend Framework 1 has used <a 
    href="http://subversion.apache.org/">Subversion</a>
    for versioning. However, as we approach its end-of-life (which will occur 
    12-18 months from the time this post is written), and as our experience
    with ZF2 processes becomes more familiar, we -- the Zend team and the Community
    Review team -- feel that we can better support ZF1 via <a href="http://github.com">GitHub</a>.
</p>

<p>
    As such, we will be migrating the ZF1 Subversion repository to GitHub this 
    week. Please read on for details!
</p>
EOS;
$post->setBody($body);

$extended =<<<'EOC'
<h2>What will happen</h2>

<h3>Subversion Access</h3>

<p>
    <strong>First: you will still have access to Subversion!</strong> 
</p>

<p>
    For versions prior to 1.12, we will continue hosting our current Subversion 
    repository, and if you are using <code>svn:externals</code> with a tag or
    branch on that repository, that will continue to work. However, the
    repository will be read-only going forward.
</p>

<p>
    The release-1.12 branch, however, will become the "master" branch on the GitHub
    repository. All future updates, including security fixes, will be made to this
    branch and this branch only; future tags will be made against GitHub. 
    However, for those of you using Subversion, all is not lost: 
    Github <a href="https://github.com/blog/966-improved-subversion-client-support">supports</a> 
    <a href="https://github.com/blog/626-announcing-svn-support">Subversion</a>!
    (That's <em>2</em> separate links!)
</p>

<p>
    If you are pointing your <code>svn:externals</code> at either trunk or the 
    release-1.12 branch, you will need to update your <code>svn:externals</code>
    to point at the new repository location. To do this, you will execute the 
    following:
</p>

<pre class="highlight">
[user@server]$ svn propedit svn:externals path/in/working-dir/to/zf1
</pre>

<p>
    This will spawn an editor. In that editor, change the URL for ZF1:
</p>

<ul>
    <li>
        "http://framework.zend.com/svn/framework/standard/trunk" becomes 
        <strong>"https://github.com/zendframework/zf1/trunk"</strong>
    </li>

    <li>
        "http://framework.zend.com/svn/framework/standard/branches/release-1.12" becomes 
        <strong>"https://github.com/zendframework/zf1/trunk"</strong>
    </li>

    <li>
        "http://framework.zend.com/svn/framework/standard/tags/release-1.12<code>.*</code>" become
        <strong>"https://github.com/zendframework/zf1/tags/release-1.12<code>.*</code>"</strong>
    </li>
</ul>

<p>
    Once you have made this change, save and exit. Then run:
</p>

<pre class="highlight">
[user@server]$ svn commit -m "update externals"
</pre>

<h3>svn:externals - Dojo</h3>

<p>
    Currently, the ZF1 repository defines a single <code>svn:externals</code> 
    property, which adds <a href="http://dojotoolkit.org/">Dojo Toolkit</a>.
</p>

<p>
    Currently, this points to version 1.5 of Dojo, as that is the last version 
    with which ZF1 maintained compatibility. We have decided with the move to
    GitHub to remove the <code>svn:externals</code> entry, and <em>not</em> add
    a git submodule; however, we <em>will</em> continue packaging Dojo 1.5 in
    any ZF1 releases.
</p>

<p>
    If you relied on the <code>svn:externals</code> definition in ZF1 in order
    to obtain Dojo, you will need to add it to your repository yourself. If you
    need to do so, point the definition at "http://svn.dojotoolkit.org/src/branches/1.5".
</p>

<h3>"Extras" Repository</h3>

<p>
    The "extras" repository, which contains the jQuery integration, Firebird DB 
    adapter, and console process forking tools, will also be migrated to GitHub,
    following the same pattern as for the ZF1 repo (i.e., release-1.12 branch
    will become the master branch on the GitHub repository, and the old 
    subversion repository will be marked read-only). You will need to update
    any <code>svn:externals</code> definitions just as you would for ZF1, with
    the following mappings:
</p>

<ul>
    <li>
        "http://framework.zend.com/svn/framework/extras/trunk" becomes 
        <strong>"https://github.com/zendframework/zf1-extras/trunk"</strong>
    </li>

    <li>
        "http://framework.zend.com/svn/framework/extras/branches/release-1.12" becomes 
        <strong>"https://github.com/zendframework/zf1-extras/trunk"</strong>
    </li>
</ul>

<h3>Issue Tracker</h3>

<p>
    Since Zend Framework 1 is in maintenance mode at this time, we are only 
    addressing critical or security issues. As such, we have decided to port 
    only open issues created since 1.12.0 was released (at the time of this 
    writing, around 66 issues) to GitHub issues.
</p>

<p>
    The JIRA instance will be marked read-only. We will likely disable it in
    the near future; if we do so, we will likely provide static pages of all
    issues for later reference. <em>(This item is still to be determined.)</em>
</p>

<p>
    Information on the new issue tracker location will be placed on the JIRA
    landing page, with links to each of the ZF1, ZF1 Extras, and ZF2 
    repositories.
</p>

<h3>Collaboration</h3>

<p>
    Once the cutover occurs, only the Zend team and the Community Review team will
    have commit rights on the GitHub repository. This means that patches will need
    to be submitted as <a href="https://help.github.com/articles/creating-a-pull-request">Pull
    Requests</a>. Once submitted, members of these teams, as well as the 
    general community, can comment and provide feedback; once consensus is reached
    that the patch is ready, it will be merged to the repository.
</p>

<p>
    If you are unfamiliar with Git and/or GitHub, we recommend the following resources:
</p>

<ul>
    <li><a href="https://help.github.com/">GitHub help site</a>, which details
    everything from setting up Git to creating and forking repositories, to collaborating.</li>

    <li><a href="http://git-scm.org">git-scm site</a>, which provides a comprehensive,
    online book detailing basics to advanced features of Git.</li>

    <li><a href="https://github.com/zendframework/zf2/blob/master/README-GIT.md">ZF2 Git Guide</a>,
    which details the typical contributor workflow; simply substitute "zf1" for "zf2" in that
    guide. (We will likely add this to ZF1 soon as well.)</li>
</ul>

<p>
    One note: at this point, we will only accept bug and security fixes; new 
    features are not currently in scope for Zend Framework 1.
</p>

<p>
    <strong>Important!</strong> You will still need to have a Contributors License Agreement (CLA)
    on file for us to accept your contributions. For this reason, we recommend setting your
    <code>user.email</code> git configuration setting to the same email used 
    for your CLA; with this information, we can easily look up your CLA status, which will
    expedite our ability to merge your pull requests/patches. To do this, simply execute the
    following from within your ZF1 checkout:
</p>

<pre class="highlight">
[user@server]$ git config user.email "your-email@example.com"
</pre>

<p>
    Do this <em>prior</em> to making any commits, to ensure that the commits in 
    your patch are attributed to that email.
</p>

<h2>Timeline</h2>

<p>
    The plan at this time is to mark the ZF1 subversion repository and issue 
    tracker read-only starting Friday, 5 April 2013.
</p>

<h2>Future</h2>

<p>
    The repository and issues migration is the first step in a series of planned 
    migrations. We also plan to eventually migrate our wiki to GitHub; this will
    allow us to offload functionality from the main ZF website, and also 
    consolidate all development-related functionality (other than the mailing 
    list) in a central location.
</p>

<p>
    If you have any concerns, please feel free to contact <a href="mailto:matthew@zend.com">myself</a>,
    <a href="/contact">my team</a>, or the <a href="mailto:zf-crteam@zend.com">Community Review Team</a>.
</p>

EOC;
$post->setExtended($extended);

return $post;
