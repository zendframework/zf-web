![Logo](http://framework.zend.com/images/logos/ZendFramework-logo.png)

Zend Framework Website, v2.0
============================

This is the next incarnation of the Zend Framework website, written using ZF2.

Installation
------------

First step is grabbing dependencies. Run the following:

```sh
    php composer.phar install
```

At that point, you can test with the built-in webserver of PHP 5.4:

```sh
cd public
php -S localhost:8080
```

Alternately, configure a virtual host in the webserver of your choice.

Adding a Release
----------------

To update the site to include a new release, do the following:

```sh
make all VERSION=X.Y.Z
```

This will update the homepage, changelogs, and download pages, and ensure that
the manual and apidoc version mappings are correct.

If the release date is other than the current date, you can also add a
`RELEASE_DATE` variable on the commandline, in the format "YYYY-MM-DD".

Updating the Home Page
----------------------

The template for the homepage is kept in `data/homepage.phtml`; if you need to
make any additions or changes to it, please do so in that file.

To regenerate the homepage from the template -- for instance, to bring in the
latest blog and security posts, run the following:

```sh
make homepage
```

Don't forget to commit the modified homepage view script when done!

Reference Guide
---------------

To configure the Reference Guide of ZF1 and ZF2, edit the file
``config/autoload/module.manual.global.php`` and modify the variables named
``$zf1ManualPath`` and ``$zf2ManualPath``, respectively. 

Each path is related to a specific language and version of the reference guide.
For instance, the English version of the 2.0 documentation is represented by:

```php
    'zf_document_path' => array(
        '2.0' => array (
            'en' => 'path to /zf2-documentation/docs/_build/html/'
        )
    )
```

The path of ZF2 documentation must point to the contents of a documenation build
folder, generally found in ``docs/_build/html/`` of the [zf2-documentation project](https://github.com/zendframework/zf2-documentation); 
if you use the documentation distribution archives, you would simply point to
the directory in which you unpack the archive. The configuration paths must
end with the ``/`` (slash) character.

The 2.0 documentation files are generated using the
[Sphinx](http://sphinx.pocoo.org/) project. For more information on how to
generate the ZF2 documentation, read the
[CONTRIBUTING.md](https://github.com/zendframework/zf2-documentation/blob/master/CONTRIBUTING.md)
file of the [zf2-documentation project](https://github.com/zendframework/zf2-documentation).

The path of the ZF1 documentation must point to the folder
``views/manual/$VERSION/$LANG/`` of the
``git://git.zendframework.com/zfweb-manual.git`` project, where ``$VERSION`` is
the version of Zend Framework, and ``$LANG`` is the language. As with the ZF2
configuration, the path must end with the ``/`` (slash) character.


Blog posts
----------

Want to post something on the blog?

Create a post in ``data/posts`` that returns a ``PhlyBlog\Model\EntryEntity``
(you can use an existing post as a template). Then, simply send a pull request,
and we'll review for inclusion.

To compile the blog, do the following from the root of the application:

```php
% php public/index.php blog compile -e -c -r
```

Then add and commit the new and updated files.

Generating Changelogs
---------------------

To add a new version to the changelog, execute the following:

```sh
php public/index.php changelog fetch --version=X.Y.Z
```

This will, by default, write to either `data/zf1-changelog.php` or
`data/zf2-changelog.php`. If generating a changelog for a version prior to
1.12.4, you will need to ensure your local configuration includes appropriate
JIRA credentials.

CSS
---

We use [Sass](http://sass-lang.com/) as stylesheet language and the Sass 
mixing library [Bourbon](http://bourbon.io/).

Make changes to the `.scss` files under `public/css/scss/`, and then compile 
them. To compile them, first ensure you have Sass installed:

```bash
% sudo gem install sass
```

and Bourbon:

```bash
% sudo gem install bourbon
```

Once you have, you can compile the CSS using the following:

```bash
% cd public/css
% sass scss/app.scss:app.min.css --style compressed
```

This will also generate a `public/css/.sass-cache` directory; do _not_ commit
this when you commit your changes.
