<?php

namespace Sitemap\Navigation\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

class ManualNavigationFactory extends DefaultNavigationFactory
{
    protected $latestVersion;


    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            $configuration['navigation'][$this->getName()] = array();

            $config = $serviceLocator->get('Config');
            foreach ($config['zf_document_path'] as $version => $langs) {
                foreach ($langs as $lang => $path) {
                    // we're only including English language pages in the
                    // sitemap for now to keep the filesize down
                    if ($lang != 'en') {
                        continue;
                    }

                    $versionDefaults = array();
                    if (empty($config['zf_latest_version']) || empty($config['zf_maintained_major_versions'])) {
                        // version info missing from config, skip priority

                    } else if ($version == $config['zf_latest_version']) {
                        // use normal priority for latest version
                        $versionDefaults['priority'] = 0.5;

                    } else if (in_array($version, $config['zf_maintained_major_versions'])) {
                        // lower priority for older but still maintained versions
                        $versionDefaults['priority'] = 0.3;

                    } else {
                        // use lowest priority for older releases
                        $versionDefaults['priority'] = 0.1;
                    }

                    if (is_readable($path)) {
                        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
                        foreach ($iterator as $manualFile) {
                            if (!$manualFile->isFile()) {
                                continue;
                            }

                            // only include HTML pages (for now)
                            if (substr($manualFile->getFilename(), -5) != '.html') {
                                continue;
                            }

                            // if the file was in a sub-folder we need to grab
                            // the path to include in the URL
                            $pathPrefix = substr($manualFile->getPath(), strlen($path));
                            if ($pathPrefix) {
                                $pathPrefix .= '/';
                            }
                            $pagePath = $pathPrefix.$manualFile->getFilename();

                            // build the page and put in the navigation array
                            $page = array_merge($versionDefaults, array(
                                'route' => 'manual',
                                'params' => array(
                                    'version' => $version,
                                    'lang' => $lang,
                                    'page' => $pagePath
                                )
                            ));

                            $configuration['navigation'][$this->getName()][] = $page;
                        }
                    }
                }
            }

            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();

            $pages = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            // we have to use the HTTP router here because the Sitemap requires
            // it, and this code may have been called from a CLI script
            $router = $serviceLocator->get('HttpRouter');

            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }

        return $this->pages;
    }
}
