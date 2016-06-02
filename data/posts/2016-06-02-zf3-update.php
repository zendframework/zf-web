<?php // @codingStandardsIgnoreFile
use League\CommonMark\CommonMarkConverter;
use PhlyBlog\AuthorEntity;
use PhlyBlog\EntryEntity;

$author = new AuthorEntity();
$author->setId('matthew');
$author->setName("Matthew Weier O'Phinney");
$author->setEmail('matthew@zend.com');
$author->setUrl('https://mwop.net/');

$markdown = new CommonMarkConverter();

$post = new EntryEntity();
$post->setId('2016-06-02-zf3-update');
$post->setTitle('Zend Framework 3 Update for 2016-06-02');
$post->setAuthor($author);
$post->setDraft(false);
$post->setPublic(true);
$post->setCreated(new DateTime('2016-06-02 16:15', new DateTimezone('America/Chicago')));
$post->setUpdated(new DateTime('2016-06-02 16:15', new DateTimezone('America/Chicago')));
$body =<<<'EOS'
This is an installment in an ongoing series of posts on ZF3 development status.
Since the last status update:

- ~130 pull requests merged, and ~100 issues closed.
- Over 30 component releases.
- Completion of the component documentation migration.
- Tagging of zend-mvc 3.0.
- Completion of the new skeleton application and related installers.

EOS;
$post->setBody($markdown->convertToHtml($body));

$extended =<<<'EOC'
## Documentation

Since the last update, we managed to complete the migration of documentation to
components, as well as publish documentation for all components!

You can view a list of all documented components via GitHub Pages:

- [https://zendframework.github.io/](https://zendframework.github.io/)

Each component contains a link in the topnav to scroll in the component list,
allowing you to navigate to other components.

Please help us thank [Frank Brückner](http://www.froschdesignstudio.de) for the
enormous amount of assistance he provided driving this milestone to completion!

## zend-mvc 3.0 stability

After copious testing with the skeleton application (more on that below), and
prepping components such as zend-test to work with it, we decided that zend-mvc
was ready to tag with a 3.0 stable version!

For those not following previous updates, the main goals of the zend-mvc v3 effort were:

- De-couple from other components. Many components were listed as development
  requirements and suggestions due to the fact that zend-mvc contained
  zend-servicemanager integrations for them. We have moved those integrations
  into the components themselves.
- Reduce dependencies to exactly what's needed for a basic zend-mvc application:
  - EventManager
  - HTTP
  - ModuleManager
  - Router
  - ServiceManager
  - Standard Library
  - View
- Split optional integrations into their own packages. These included:
  - Console integration (now provided via zend-mvc-console)
  - i18n integration (now provided via zend-mvc-i18n)
  - Several plugins had requirements on additional libraries, including:
    - PRG (uses zend-session)
    - FilePRG (uses zend-form and zend-session)
    - FlashMessenger (uses zend-session)
    - Identity (uses zend-authentication)

During the process, we were able to remove around 75% of the code, making the
component much smaller, more maintainable, and more focused.

Once zend-mvc was tagged 3.0, we quickly followed up with a zend-test 3.0
release, and stable releases of zend-mvc-console, zend-mvc-i18n, and the
various zend-mvc-plugin packages.

## Skeleton application

We'd begun refactoring the skeleton application previously, and were able to
complete the work in the past couple weeks. The new skeleton:

- Migrates to PSR-4 directory layout for the shipped `Application` module.
- Relies on Composer for all autoloading, including the `Application` module.
- Removes all translations. These were of dubious use, and were quite difficult
  to maintain.
- Depends only on zend-mvc, zend-component-installer (which automates injecting
  components and modules into application configuration during installation), and
  zend-skeleton-installer (more on this below).
- We removed almost 8000 lines of code, adding only 800!

zend-skeleton-installer is a new Composer plugin that prompts the user during installation to:

- Decide if they want a minimal install, or want to add optional packages.
- Prompts for a number of common optional packages, including caching, logging,
  console integration, i18n, etc.
- When installation is complete, *it removes itself from the project!*

Matthew plans to blog on the code behind zend-skeleton-installer in the near future.

You can test out the new skeleton using the following:

```bash
$ composer create-project "zendframework/skeleton-application:dev-develop" zend-project
```

The above will use the new develop branch, and create a project in the
directory `zend-project`.

Finally, we added both an updated `Vagrantfile` and Docker support to the
skeleton, allowing you to start developing in a consistent, de-coupled
environment immediately.

For Vagrant, after you've installed, execute:

```bash
$ vagrant up
```

For Docker, you will need to use [docker-compose](https://docs.docker.com/compose/);
once you have that available, execute:

```bash
$ docker-compose up -d --build
```

With each, we bind your host port 8080 to the container's port 80, allowing you to visit
it at http://localhost:8080/

We're excited about the new skeleton, and look forward to getting your feedback on it!

## Final milestones

We have a few last milestones before we're ready to announce the completion of the
Zend Framework 3 initiatives.

First, because PHP 5.5 support ends at the end of June, we will be releasing a new minor
version of all components setting the minimum supported PHP version to 5.6. (Many already
have such versions in place.)

Second, now that the skeleton application is ready, we will be migrating our tutorials
to a new repository, converting them to Markdown and MkDocs, and updating them to follow
the new skeleton and component changes.

Finally, we will be deciding what the zendframework/zendframework package will look like
for a version 3 tag. In large part, it becomes unnecessary, as we can ship even the
skeleton with a minimal set of components; however, for those who want "everything at
once", keeping it around as a metapackage may have value. We'll be announcing
the plans for it soon.

## Until next time

If you want to help:

- Test the new skeleton (see the directions above) and provide feedback.
- Search for [help wanted](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22help+wanted%22+)
  or [EasyFix](https://github.com/issues?utf8=%E2%9C%93&q=is%3Aissue+org%3Azendframework+is%3Aopen+label%3A%22EasyFix%22+)
  issues (most of the latter are documentation).

Many thanks to all the contributors who have provided feedback, patches, reviews,
or releases since the last update!
EOC;
$post->setExtended($markdown->convertToHtml($extended));

return $post;
