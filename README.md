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

This will, by default, write to `data/zf1-changelog.php`.
