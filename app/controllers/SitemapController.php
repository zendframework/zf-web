<?php
class SitemapController extends Zend_Controller_Action
{
    public function indexAction()
    {
    }

    public function behindTheSiteAction()
    {
    }

    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6)) {
            return $this->_forward('index');
        }

        throw new Exception(sprintf('Invalid method "%s" called in class %s', $method, __CLASS__));
    }
}
