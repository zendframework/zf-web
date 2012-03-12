<?php
/**
 * Support for rendering partial views
 *
 * Normally, views are displayed framed inside layouts (two step view).  However,
 * there exists a need for a lower level -- partial views, or subtemplates.
 * These partial views are shared between, and rendered inside, other views. A
 * typical use for these would be a sidebar that repeats or is shared between views.
 */
class Zend_View_Helper_Partial
{
    /**
     * Renders a partial view.
     *
     * Partials are stored in the /views directory per controller, however they are
     * differentiated from normal views by being prefixed with an underscore.
     *
     * @param string $partial           Formatted like "controller/_partial.phtml"
     * @param array  $spec              Array of value to assign as instance variables
     * @return string                   Rendered partial
     */
    public function partial($partial, $spec = array())
    {
        require_once 'Zend/Registry.php';
        $view = clone Zend_Registry::get('view');
        $view->assign($spec);

        if (strpos($partial, '/')) {
            $partial = explode('/', $partial);
            array_push($partial, '_' . array_pop($partial));
            $partial = implode($partial, '/');
        } else {
            $partial = '_' . $partial;
        }

        return $view->render($partial . '.phtml');
    }
}
