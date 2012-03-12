<?php
class Zf2_View_Helper_Tags extends Zend_View_Helper_Abstract
{
    public function tags($tags)
    {
        if (!is_array($tags) || empty($tags)) {
            return '';
        }

        $view = $this->view;
        $tags = array_map(function ($tag) use ($view) {
            return sprintf('<li>%s</li>', $view->escape($tag));
        }, $tags);

        $string = sprintf('<ul class="tags">%s</ul>', implode('', $tags));
        return $string;
    }
}

