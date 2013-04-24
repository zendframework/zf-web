<?php
require_once 'Pearified/Smarty/Smarty.class.php';

/**
 * @category   Naneau
 * @package    Naneau_View
 * @copyright  Copyright (c) 2007 Maurice Fonk - http://naneau.nl,
Amendments by A Kitson
 */
class ZWare_View_Smarty extends Zend_View_Abstract
{
    /**
     * Smarty object
     * @var Smarty
     */
    protected $_smarty;

    /**
     * Constructor
     *
     * Pass it a an array with the following configuration options:
     *
     * scriptPath: the directory where your templates reside
     * compileDir: the directory where you want your compiled templates
     * (must be
     * writable by the webserver)
     * configDir: the directory where your configuration files reside
     *
     * both scriptPath and compileDir are mandatory options, as Smarty
     * needs
     * them. You can't set a cacheDir, if you want caching use
     * Zend_Cache
     * instead, adding caching to the view explicitly would alter
     * behaviour
     * from Zend_View.
     *
     * @see Zend_View::__construct
     * @param array $config
     * @throws Exception
     */
    public function __construct($config = array())
    {
        $this->_smarty = new Smarty();
        //smarty object

        if (!array_key_exists('compileDir', $config)) {
            throw new Exception('compileDir must be set in $config for ' . get_class($this));
        } else {
            $this->_smarty->compile_dir = $config['compileDir'];
        }
        //compile dir must be set

        if (array_key_exists('configDir', $config)) {
            $this->_smarty->config_dir = $config['configDir'];
        }
        //configuration files directory

        parent::__construct($config);
        //call parent constructor
    }

    /**
     * Return the template engine object
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->_smarty;
    }

    /**
     * fetch a template, echos the result,
     *
     * @see Zend_View_Abstract::render()
     * @param string $name the template
     * @return void
     */
    protected function _run()
    {
        $this->strictVars(true);

        //assign variables to the template engine
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ('_' != substr($key, 0, 1)) {
                $this->_smarty->assign($key, $value);
            }
        }

        //why 'this'?
        //to emulate standard zend view functionality
        //doesn't mess up smarty in any way
        $this->_smarty->assign_by_ref('this', $this);

        //smarty needs a template_dir, and can only use templates,
        //found in that directory, so we have to strip it from the
        // filename
        //This checks the default application/smarty/template directory
        // as well as the module views/scripts directory to find the
        // template
        $paths = $this->getScriptPaths();
        $arg = func_get_arg(0);
        foreach ($paths as $path) {
            if (0 === strpos($arg, $path)) {
                if ($file = substr($arg, strlen($path))) {
                    $this->_smarty->template_dir = $path;
                    break;
                }
            }
        }

        //process the template (and filter the output)
        echo $this->_smarty->fetch($file);
    }

    public function register_object($objName, &$obj, $allowed=null,
        $smarty_args = true, $block_methods = array()
    ) {
        $this->_smarty->register_object($objName,$obj,$allowed, $smarty_args,$block_methods);
    }
}
