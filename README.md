This is the github repository of the Zend Framework web site

    http://framework.zend.com/

The source code of the web site is not complete. We removed some parts like the
manuals (see below), but most of the web site is here; the ZF2 module is fully
included (http://framework.zend.com/zf2).

This web site uses the libraries ZF 1.11 and ZF 2.0.0beta3.

To run this web site on your local machine you have to configure the following
files:

- document_root/index.php 
  - add your ZF 1.11 path, on line 11

- Copy app/etc/config.ini.dist to app/etc/config.ini, and update as follows:
  - Be sure that the cache.baseDir folder is writable from the web server.
  - Be sure to read the section entitled "Documentation" and follow the
    instructions there.
  - Update the following with valid credentials/API keys:
    - jira.credentials.username/jira.credentials.password -- should be a valid
      user/pass pair for the ZF JIRA instance.
    - service.akismet.key should be a valid Akismet API key
    - recaptcha.pubkey/recaptcha.privkey should be your public/private keypair
      for ReCaptcha
  - You may want to disable caching: 
    resources.cachemanager.content.frontend.options.caching  = 0
    resources.cachemanager.search.frontend.options.caching   = 0
    resources.cachemanager.zfstatus.frontend.options.caching = 0
  - To get the "pretty" font headers, you should enable the dynamic header
    learning:
    dynamicheader.options.learning = 1

- Create a symlink from "working" to a directory containing a ZF checkout.

- app/jobs/agilezen.php (configure only if you want to update the kanban data)
   - add your ZF 2.0.0beta3 path, on line 19
   - add the API key of AgileZen, on line 30;

- app/jobs/invite_agile.php (configure only if you want to activate the invitation
  of AgileZen in the kanban system)
   - add your ZF 2.0.0beta3 path, on line 19;
   - add the API key of AgileZen, on line 31;
   - add the path to the log file, on line 60.

## Documentation

The online manual utilizes two key resources that are regenerated and/or
updated on new ZF releases. To allow them to be released on a separate timeline
from the main website, we keep these in a separate repository:

    git clone git://git.zendframework.com/zfweb-manual.git

The structure of this repository is as follows:

    indexes/
        X.Y/ (one directory per minor version of the framework)
    views/
        manual/
            X.Y/ (one directory per minor version of the framework)
                de/
                en/
                fr/
                ja/
                ru/
                zh/

The "indexes" directory contains the generated Lucene index for each version of
the manual, and for each language offerred. The "views" directory contains view
scripts consumed by the website.

After cloning the repository, you will need to update your app/etc/config.ini as
follows:

    ; This should point to the "views" directory of your checkout
    manual.views        =   "/var/local/framework/manual/views"

    ; This should point to the "indexes" directory of your checkout
    index.manual.path        = "/var/local/framework/manual/indexes"

Make sure the "views" directory is readable by the web server. The "indexes"
directory should both be readable and writable by the web server (as
Zend_Search_Lucene requires the ability to write read locks in that directory).

## Release Tarballs

The ReleaseModel links to releases in the document_root/releases directory --
which is deliberately empty in this archive. Currently, these are kept in a
separate SVN repository, and a working checkout of the respository is symlinked
to that path. Each release is in a directory named "ZendFramework-X.Y.Z", and
contains tarballs and zipballs of the full release, minimal release, API
documentation, and compiled manual for each language.

We plan to offer the releases as a repository at a future date, potentially as
a git submodule.

## API Documentation

Currently, built API documentation is available on the site. In production, we
have symlinks of each version pointing to a location with built documentation.
We plan to offer a separate git repository at a later date with built
documentation that we will incorporate via a git submodule.

