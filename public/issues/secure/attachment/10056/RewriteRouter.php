<?php

class Custom_Controller_RewriteRouter extends Zend_Controller_RewriteRouter
{
    public function addAdvancedRoute($name, $map, $params = array(), $reqs = array())
    {
        if (!class_exists('Custom_Controller_Router_AdvancedRoute')) Zend::loadClass('Custom_Controller_Router_AdvancedRoute');
        $this->_routes[$name] = new Custom_Controller_Router_AdvancedRoute($map, $params, $reqs);
    }
}