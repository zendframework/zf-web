<?php

namespace Manual;

use Zend\Mvc\Router\Http\Segment;

class Route extends Segment
{
    public function __construct($route, array $constraints = array(), array $defaults = array())
    {
        parent::__construct($route, $constraints, $defaults);

        // add the slash to the allowed unencoded chars map, since this route
        // includes that charater in its 'page' parameter
        if (!isset(static::$urlencodeCorrectionMap['%2F'])) {
            static::$urlencodeCorrectionMap['%2F'] = '/';
        }
    }
}