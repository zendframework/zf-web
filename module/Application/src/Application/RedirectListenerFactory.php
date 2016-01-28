<?php

namespace Application;

class RedirectListenerFactory
{
    public function __invoke($services)
    {
        $config = $services->has('config') ? $services->get('config') : [];
        $map = isset($config['redirects']) ? $config['redirects'] : [];
        return new RedirectListener($map);
    }
}
