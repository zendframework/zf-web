Howto Write Posts for the ZF2 Blog
==================================

Create the post
---------------

* Posts are in app/modules/zf2/posts/
* Create a new file; typically, it should be a shortened version of the post
  title, and it's generally good practice to prefix with the date so they sort
  easily.
* In the file, you will create a stdClass object, with at the minimum the
  following properties:
  * title - the title of the post
  * author - an object with properties "name" and "link"
  * date - the date and time (in PST) the post will be published. Format is
    "Y-m-d H:i:s"
  * summary - A paragraph or two to display in list view and in the feed
  * content - the actual content of the post
* Both the summary and content can contain arbitrary HTML. I typically use
  nowdocs for this to prevent any interpolation.
* Write code you want syntax highlighting for using <pre> tags with a class of
  "highlight": 

    <pre class="highlight">
    $foo->bar();
    </pre>

* Return the post object at the bottom of the file.

Compiling the blog
------------------

The blog is basically static view scripts. To ensure that the feed is
up-to-date, the entries all contain the same structure, and the list views are
correctly paginate, we have a script that compiles the blog to view scripts.

The script is `app/modules/zf2/scripts/compile.php`. Invoke it as follows:

    prompt> php compile.php

The compiler takes the following options:

* *-v* turn on verbosity and log progress to STDOUT
* *-d* specify development mode; this is primarily to set a flag in the
  generated disqus markup so as to allow displaying the disqus comments and
  comment box in non-production environments.
* *-h* can be used to specify an alternate host for generated URLS; again, this
  is typically for non-production environments for testing feeds and disqus
  comments.
* *-?* will display the usage message

Once the compiler has run, it will have generated new views scripts in
`app/modules/zf2/views/scripts/blog/`. After reviewing these in a browser, add
and commit them to the repository.

