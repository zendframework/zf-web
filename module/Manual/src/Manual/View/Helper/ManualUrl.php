<?php

namespace Manual\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ManualUrl extends AbstractHelper
{
    public function __invoke($page, $lang = null, $version = null)
    {
        $urlHelper = $this->view->plugin('url');
        $params    = array(
            'page' => $page,
        );
        if ($lang) {
            $params['lang'] = $lang;
        }
        if ($version) {
            $params['version'] = $version;
        }

        $url = $urlHelper('manual', $params);
        return str_replace('%2F', '/', $url);
    }
}
