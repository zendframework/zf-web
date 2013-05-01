<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application)
    {
        parent::__construct($application);
        date_default_timezone_set('Europe/Paris');
        $config = Zend_Registry::get('config')->{APPLICATION_ENV}->database;
        $db = Zend_Db::factory($config->adapter, array(
            'host'     => $config->params->host,
            'username' => $config->params->username,
            'password' => $config->params->password,
            'dbname'   => $config->params->dbname
        ));
        $db->query('SET NAMES UTF8');
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('database', $db);
        require_once CONFIGS_PATH . 'form.fr.php';
        $view = new Zend_View();
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        $viewRenderer->view->doctype('XHTML1_TRANSITIONAL');
        $viewRenderer->view->setEncoding('utf-8');
    }
}

