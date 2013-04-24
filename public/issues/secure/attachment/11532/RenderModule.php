<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Partial.php 10664 2008-08-05 10:56:06Z matthew $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_View_Helper_Abstract.php */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Helper for rendering a view script of another module.
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_View_Helper_RenderModule extends Zend_View_Helper_Abstract
{
    /**
     * Renders a view script of another module.
     *
     * @param  string $name Name of view script
     * @param  string $module Name of the module
     * @throws Zend_View_Exception When module does not exist
     * @return string
     */
    public function renderModule($name, $module)
    {       
        // Get all original paths
        $helperPaths = $this->view->getHelperPaths();
        $scriptPaths = $this->view->getScriptPaths();
        $filterPaths = $this->view->getFilterPaths();
        
        // Determine the module directory and set the new base path
        $moduleDir = Zend_Controller_Front::getInstance()->getControllerDirectory($module);
        if (null === $moduleDir) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('Cannot render view; module does not exist');
        }
        $viewsDir = dirname($moduleDir) . '/views';
        $this->view->addBasePath($viewsDir);
        
        // Render the view
        $result = $this->view->render($name);
        
        // Reset all paths
        $this->view->setHelperPath($helperPaths);
        $this->view->setScriptPath($scriptPaths);
        $this->view->setFilterPath($filterPaths);

        // Return the result
        return $result;
    }
}
