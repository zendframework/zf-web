<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initCache()
	{
		$options = $this->getOptions();
		$file = $options['etc']['cache']['defaultcachedir']. 'pluginLoaderCache.php';
		if (file_exists($file)) {
		    include_once $file;
		}
		if ($options['etc']['cache']['plugin']) {
		    Zend_Loader_PluginLoader::setIncludeFileCache($file);
		}
	}

    protected function _initSession() {
    	$options = $this->getOptions();
    	$session = new Zend_Session_Namespace( $options['resources']['session']['name'] );
		Zend_Registry::set( 'session', $session );

	    if (!isset($session->initialized)) {
		    Zend_Session::regenerateId();
		    $session->initialized = true;
		}
    }