<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initCache()
	{
		$file = $this->getOption('defaultcachedir'). 'pluginLoaderCache.php';
		if (file_exists($file)) {
		    include_once $file;
		}
		if ($this->getOption('plugin')) {
		    Zend_Loader_PluginLoader::setIncludeFileCache($file);
		}
	}

    protected function _initSession() {
    	$session = new Zend_Session_Namespace( $this->getOption('name') );
		Zend_Registry::set( 'session', $session );

	    if (!isset($session->initialized)) {
		    Zend_Session::regenerateId();
		    $session->initialized = true;
		}
    }