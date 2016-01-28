<?php

namespace Application;

/**
  * A listener that performs redirects.
 *
 * Redirect maps are in the form of:
 *
 * <code>
 * [
 *     'route-name' => [
 *         'target' => 'path or absolute-uri to redirect to',
 *         'status' => 302, // optional status; defaults to 302
 *     ],
 * ]
 * </code>
 */
class RedirectListener
{
    private $redirectMap;

    /**
     * Create an instance with the provided redirect map.
     *
     * @param array $map
     */
    public function __construct(array $map = [])
    {
        $this->redirectMap = $map;
    }

    /**
     * Listen to the routing event, and, if necessary, issue a redirect
     *
     * @param \Zend\Mvc\MvcEvent
     * @return void|\Zend\Http\Response Returns response if able to redirect.
     */
    public function __invoke($e)
    {
        $routeMatch = $e->getRouteMatch();
        if (! $routeMatch) {
            return;
        }

        $route = $routeMatch->getMatchedRouteName();
        if (! isset($this->redirectMap[$route])) {
            return;
        }

        $info = $this->redirectMap[$route];
        if (! isset($info['target'])) {
            return;
        }

        $response = $e->getResponse();
        $response->setStatusCode(isset($info['status']) ? $info['status'] : 302);
        $response->getHeaders()->addHeaderLine('Location', $info['target']);
        return $response;
    }
}
