<?php

class Zend_View_Helper_DialogId extends Zend_View_Helper_Abstract
{
    public function dialogId($release)
    {
        $version = $release->getReleaseVersion();
        $label   = $release->getReleaseLabel();
        $id      = 'docs-' . $version;
        if (!empty($label)) {
            $id .= '-' . $label;
        }
        return $id;
    }
}
