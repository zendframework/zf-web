Zend Framework Website, v2.0
============================

This is the next incarnation of the Zend Framework website, written using ZF2.

Installation
------------

First step is grabbing dependencies. Run the following:

    php composer.phar install

At that point, you can test with the built-in webserver of PHP 5.4:

    cd public
    php -S localhost:8080

Alternately, configure a virtual host in the webserver of your choice.

Reference Guide
---------------

To configure the Reference Guide of ZF1 and ZF2 you have to edit the file
``/module/Manual/config/module.config.php`` and modify the paths of the section
``zf_document_path``.

Each path is related to a specific language of the reference guide. For instance,
the English version of the 2.0 documentation is represented by:

    'zf_document_path' => array(
        '2.0' => array (
            'en' => 'path to /zf2-documentation/docs/_build/html/'
        )
    )

The path of ZF2 documentation must point to the folder ``docs/_build/html/`` of the
[zf2-documentation project](https://github.com/zendframework/zf2-documentation). All the paths must be end with the ``/`` (slash).

The 2.0 document files are generated using the [Sphinx](http://sphinx.pocoo.org/) project. To have more info
on how to generate the ZF2 documentation read the [CONTRIBUTE.md](https://github.com/zendframework/zf2-documentation/blob/master/CONTRIBUTE.md)
file of the [zf2-documentation project](https://github.com/zendframework/zf2-documentation).

The path of the ZF1 documentation must point to the folder ``views/manual/$VER/$LANG/``
of the ``git://git.zendframework.com/zfweb-manual.git`` project, where ``$VER`` is the version
of Zend Framework and ``$LANG`` is the language. All the paths must be end with the ``/`` (slash).


Blog posts
----------

Want to post something on the blog?

Create a post in ``data/posts`` that returns a ``PhlyBlog\Model\EntryEntity``
(you can use an existing post as a template). Then, simply send a pull request,
and we'll review for inclusion.

To compile the blog, do the following from the root of the application:

    ./console PhlyBlog:compile -e -c -r

Then add and commit the new and updated files.

Generating Changelogs
---------------------

To generate the ZF1 changelog, execute the following:

    ./console Changelog:fetchZf1

This will, by default, write to `data/zf1-changelog.php`. You will need to
ensure your local configuration includes appropriate JIRA credentials.

To generate the ZF2 changelog, execute the following:

    ./console Changelog:fetchZf2

This will, by default, write to `data/zf2-changelog.php`.
