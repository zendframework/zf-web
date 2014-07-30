<?php

namespace Manual;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Manual\Controller\Page' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $resolver = $services->get('ViewResolver');
                $config   = $services->get('Config');

                $controller = new Controller\PageController();
                $controller->setResolver($resolver);
                $controller->setApiDocVersions($config['zf_apidoc_versions']);
                $controller->setParams($config['zf_document_path']);
                return $controller;
            },
        ));
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'outdatedDocsMessage' => function ($helperPluginManager) {
                    $sm = $helperPluginManager->getServiceLocator();

                    $config = $sm->get('Config');

                    $helper = new View\Helper\OutdatedDocsMessage(
                        $config['zf_document_path'],
                        $config['zf_maintained_major_versions']
                    );

                    return $helper;
                },
            )
        );
    }
}
