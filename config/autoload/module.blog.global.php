<?php
return array(
    'disqus' => array(
        'key'         => 'zf2blog',
        'development' => 0,
    ),
    'blog' => array(
        'options' => array(
            'author_feed_filename_template' => 'public/blog/author/%s-%s.xml',
            'author_feed_title_template'    => 'Author: %s - ZF Blog',
            'by_author_filename_template'   => 'public/blog/author/%s-p%d.html',
            'by_day_filename_template'   => 'public/blog/day/%s-p%d.html',
            'by_month_filename_template' => 'public/blog/month/%s-p%d.html',
            'by_tag_filename_template'   => 'public/blog/tag/%s-p%d.html',
            'by_year_filename_template'  => 'public/blog/year/%s-p%d.html',
            'entries_filename_template'  => 'public/blog/index-p%d.html',
            'entries_url_template'       => '/blog/index-p%d.html',
            'entries_template'           => 'blog/list',
            'entry_filename_template'    => 'public/blog/%s.html',
            'entry_link_template'        => '/blog/%s.html',
            'entry_template'             => 'blog/entry',
            'feed_author_email'          => 'zf-contributors@lists.zend.com',
            'feed_author_name'           => 'Zend Framework Development Team',
            'feed_author_uri'            => 'http://framework.zend.com/',
            'feed_filename'              => 'public/blog/feed-%s.xml',
            'feed_hostname'              => 'http://framework.zend.com',
            'feed_title'                 => 'Blog Entries - ZF Blog',
            'tag_feed_filename_template' => 'public/blog/tag/%s-%s.xml',
            'tag_feed_title_template'    => 'Tag: %s - ZF Blog',
            'tag_cloud_options'          => array('tagDecorator' => array(
                'decorator' => 'html_tag',
                'options'   => array(
                    'fontSizeUnit' => '%',
                    'minFontSize'  => 80,
                    'maxFontSize'  => 300,
                ),
            )),
        ),
        'posts_path'     => 'data/posts/',
        'view_callback'  => 'ZfSiteBlog\Module::prepareCompilerView',
        'cloud_callback' => array('ZfSiteBlog\Module', 'handleTagCloud'),
    ),
    'view_manager' => array(
        'template_map' => array(
            'blog/assets'       => 'module/ZfSiteBlog/view/zf-site-blog/assets.phtml',
            'blog/blogroll'     => 'module/ZfSiteBlog/view/zf-site-blog/blogroll.phtml',
            'blog/entry-short'  => 'module/ZfSiteBlog/view/zf-site-blog/entry-short.phtml',
            'blog/entry'        => 'module/ZfSiteBlog/view/zf-site-blog/entry.phtml',
            'blog/list'         => 'module/ZfSiteBlog/view/zf-site-blog/list.phtml',
            'blog/paginator'    => 'module/ZfSiteBlog/view/zf-site-blog/paginator.phtml',
            'blog/social-media' => 'module/ZfSiteBlog/view/zf-site-blog/social-media.phtml',
            'blog/tags'         => 'module/ZfSiteBlog/view/zf-site-blog/tags.phtml',
        ),
        'template_path_stack' => array(
            'blog' => 'module/ZfSiteBlog/view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'phly-blog' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/blog/',
                ),
                'may_terminate' => false,
                'child_routes'  => array(
                    'index' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'index.html',
                        ),
                    ),
                    'feed-atom' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'feed-atom.xml',
                        ),
                    ),
                    'feed-rss' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'feed-rss.xml',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
