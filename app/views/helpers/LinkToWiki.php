<?php
/**
 * Link to a ZF wiki page
 */
class Zend_View_Helper_LinkToWiki
{
    private static $_pages = array('subversion'     => 'x/IgE',
                                   'lists'          => 'x/GgE#ContributingtoZendFramework-Subscribetotheappropriatemailinglists',
                                   'listsSubscribe' => 'x/GgE#ContributingtoZendFramework-Subscribetotheappropriatemailinglists',
                                   'listsArchive'   => 'display/ZFMLGEN/Home',
                                   'tutorials'      => 'x/q',
                                   'documentation'  => 'display/ZFDOCDEV/Home',
                                   'infrastructure' => 'x/og',
                                   'developer'      => 'display/ZFDEV/Home',
                                   'translator'     => 'x/uAY',
                                  );

    /**
     * Link to a ZF wiki page
     *
     * @param  string $key      Key for URL in $_pages array
     * @param  string $title    Title text (optional)
     * @return string           <a href="">...</a>
     */
    public function linkToWiki($key, $title = null)
    {
        $url = 'http://framework.zend.com/wiki/';
        if (isset(self::$_pages[$key])) {
            $url .= self::$_pages[$key];
        }

        $title = isset($title) ? $title : $url;

        return "<a href=\"$url\">$title</a>";
    }
}
