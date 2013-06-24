<?php
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

$application = Zend\Mvc\Application::init(include __DIR__.'/../config/application.config.php');

$viewHelperManager = $application->getServiceManager()->get('viewhelpermanager');
if ($viewHelperManager) {
    $navigation = $viewHelperManager->get('navigation');

    $sitemap = $navigation('navigation')->sitemap();

    $sitemap->setServerUrl('http://framework.zend.com/')
            ->setFormatOutput(true);

    // the sitemap helper assumes the view instance will be phpRenderer
    // so we supply one to avoid plugin errors
    $phpRenderer = new \Zend\View\Renderer\PhpRenderer();
    //$phpRenderer = $application->getServiceManager()->get('ViewRenderer');
    $sitemap->setView($phpRenderer);

    $xml = $sitemap->__toString();
    if ($xml) {
        file_put_contents('public/sitemap.xml', $xml);
    }
}