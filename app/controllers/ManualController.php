<?php

/** ManualModel */
require_once dirname(dirname(__FILE__)) . '/models/ManualModel.php';

/** ManualStatusModel */
require_once dirname(dirname(__FILE__)) . '/models/ManualStatusModel.php';

/**
 * Online manual viewer
 */
class ManualController extends Zend_Controller_Action
{
    /**
     * Search index
     * @var Zend_Search_Lucene
     */
    protected $_index;

    /**
     * @var ManualModel
     */
    protected $_manualModel;

    /**
     * @var ReleaseModel
     */
    protected $_releaseModel = null;

    /**
     * Search form
     * @var Zend_Form
     */
    protected $_searchForm;

    /**
     * @var User
     */
    protected $_user = null;

    /**
     * Set Varien colors for PHP syntax highlighting
     */
    public function init()
    {
        // Ensure the manual script path includes manuals stored locally
        $this->view->addScriptPath($this->_getManualScriptPath());

        // Set up css, title, etc.
        $this->view->headLink()->appendStylesheet('/css/' . $this->view->css['manual'], 'all');
        $this->view->headTitle()->append('Documentation');
        $this->view->tab     = 'manual';                    // key of tab to highlight

        ini_set('highlight.string',  '#7F1E65');
        ini_set('highlight.comment', '#7B8D9A');
        ini_set('highlight.keyword', '#232D30');
        ini_set('highlight.bg',      '#EDF7FF');
        ini_set('highlight.default', '#1D53C1');
        ini_set('highlight.html',    '#000000');
    }

    /**
     * Overloading: handle requests for language-specific manuals
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     * @throws Exception if unable to handle
     */
    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6)) {
            $test = substr($method, 0, strlen($method) - 6);
            if ($this->_validateLanguage($test)) {
                $this->getRequest()->setActionName('manual')
                                   ->setParam('language', $test);
                return $this->manualAction();
            }
        }

        throw new Exception(sprintf('Invalid method "%s" called in %s', $method, __CLASS__));
    }

    public function indexAction()
    {
        $this->view->active  = 'index';                       // title for title bar
    }

    public function componentsAction()
    {
        $this->view->active  = 'components';                       // title for title bar
    }

    public function apiAction()
    {
        $this->view->active  = 'api';
    }

    public function faqAction()
    {
        $this->view->active  = 'faq';
    }

    /**
     * URI endpoint for translations: /manual/code/page.html#section
     *                         controller^     ^action     ^params[language]
     */
    public function manualAction()
    {
        // security check on action name (locale code)
        if (!$code = $this->_getLanguage()) {
            return $this->_redirect('/manual/');
        }

        $model = $this->_getManualModel();

        // locale code not found? speak english!
        if (!$model->setLocale($code)) {
            return $this->_redirect('/manual/en/');
        }

        // Get version information
        $version  = $this->_request->getUserParam('version', 'current');
        $mVersion = $model->getManualVersion($version);

        $pageRequested = $this->getRequest()->getParam('page', 'manual.html');
        $sectionName   = preg_replace('#\.html$#', '', $pageRequested);

        if ($sectionName != 'manual' && !$this->_isValidManualSection($sectionName, $mVersion, $code)) {
            $location = "/manual/$code";
            return $this->_redirect($location);
        }

        $pageRequestedAsPhtml = preg_replace('/\.html$/', '.phtml', $pageRequested);
        $pageViewScriptPath = "manual/$mVersion/$code/$pageRequestedAsPhtml";

        // populate the view
        $locales      = $model->getLocales();
        $versions     = $model->getVersions();
        $mVersion     = $model->getManualVersion($version);
        $bootstrap    = $this->getInvokeArg('bootstrap');
        $cacheManager = $bootstrap->getResource('cachemanager');
        $this->view->assign(array(
            'model'    => $model,
            'cache'    => $cacheManager->getCache('content'),
            'active'   => 'manual',               // title for title bar
            'name'     => $pageRequested,         // name of this manpage, e.g. index.html
            'section'  => $sectionName,           // name of the section (without the .html part)
            'page'     => $pageViewScriptPath,    // view script to render
            'version'  => $version,               // manual version to render
            'mVersion' => $mVersion,              // manual version to render
            'versions' => array_keys($versions),  // available manual versions
            'current'  => $versions['current'],   // current manual version
            'code'     => $code,                  // code of the current locale
            'locales'  => $locales,               // array of all locale data
            'form'     => $this->_getSearchForm(
                $locales,
                array_keys($versions),
                $mVersion
            ),                                    // search form
        ));
        ZfWeb_Plugins_Caching::$doNotCache = true;
    }


    /**
     * Search the manual
     *
     * @return void
     */
    public function searchAction()
    {
        if (!$code = $this->_getLanguage()) {
            $code = 'en';
        }

        $model    = $this->_getManualModel();
        $versions = array_keys($model->getVersions());
        $locales  = $model->getLocales();
        $version  = $model->getManualVersion(
            $this->_getParam('version', 'current')
        );
        $form     = $this->_getSearchForm($locales, $versions, $version);
        $this->view->assign(array(
            'form'     => $form,
            'locales'  => $locales,
            'versions' => $versions,
        ));

        $request = $this->getRequest();
        if (!$request->isGet()) {
            return $this->render('search-form');
        }

        $data = $request->getQuery();
        if (empty($data)) {
            return $this->render('search-form');
        }

        if (!$form->isValid($data)) {
            return $this->render('search-form');
        }

        $version = $model->getManualVersion($form->getValue('version'));
        $lang    = $form->getValue('language');
        $query   = $form->getValue('query');

        $bootstrap    = $this->getInvokeArg('bootstrap');
        $cacheManager = $bootstrap->getResource('cachemanager');
        $searchCache  = $cacheManager->getCache('search');

        require_once dirname(dirname(__FILE__)) . '/models/SearcherModel.php';
        $searcher = new SearcherModel($this->_getIndexPath($version), $searchCache);

        if ($lang && ($lang != 'all')) {
            $escapedLang = '\\' . implode('\\', str_split($lang));
            $hits = $searcher->search("+($query) +lang:$escapedLang");
        } else {
            $hits = $searcher->search($query);
        }


        $this->view->assign(array(
            'model'    => $this->_getManualModel(),
            'cache'    => $cacheManager->getCache('content'),
            'hits'     => $hits,                           // search results
            'searcher' => $searcher,
            'version'  => $version,
            'mVersion' => $version,
            'code'     => $code,                           // current locale
        ));
    }

    /**
     * Show manual translation status
     *
     * @return void
     */
    public function statusAction()
    {
        $this->view->title   = 'Manual Translation Status'; // title for title bar
        $this->view->active  = 'status';                    // title for title bar

        $localeModel         = new ManualModel();
        $this->view->locales = $localeModel->getLocales();

        $statusModel         = new ManualStatusModel(Zend_Registry::get('config'));
        $this->view->status  = $statusModel->load();

        $langList = array_keys($this->view->locales);

        foreach ($langList as $lang) {
            $this->view->status['locale'][$lang]['percent'] = 0;
            $n = $this->view->status['locale'][$lang]['numFiles'];
            $m = $this->view->status['locale'][$lang]['numMissing'];
            if ($n > 0) {
                $this->view->status['locale'][$lang]['percent'] = round(($n - $m) * 100 / $n);
            }
        }
        array_splice($langList, array_search('en', $langList), 1);
        $langParam = $this->_getParam('lang', false);
        $this->view->langParam = $langParam;
        if ($langParam && $langParam != 'en' && array_search($langParam, $langList) !== false) {
            $langList = array($langParam);
        }
        if (($sortByPercent = $this->_getParam('percent', true))) {
            usort($langList, array($this, 'sortLangByPercent'));
        }
        array_unshift($langList, 'en');
        $this->view->langList = $langList;
    }

    /**
     * Display videos
     *
     * @return void
     */
    public function videosAction()
    {
        $this->_forward('multimedia', 'docs');
    }

    /**
     * Validate a language
     *
     * @param  string $lang
     * @return bool
     */
    protected function _validateLanguage($lang)
    {
        return ((preg_match('/[^a-z0-9\-_.#]/i', $lang)) ? false : true);
    }

    /**
     * Get the requested language
     *
     * @return string|false
     */
    protected function _getLanguage()
    {
        $code = $this->_getParam('language', 'en');
        if (!$this->_validateLanguage($code)) {
            return false;
        }
        return $code;
    }

    /**
     * Sort languages by percentage complete
     *
     * @param  string $a
     * @param  string $b
     * @return bool
     */
    protected function _sortLangByPercent($a, $b)
    {
        $ap = $this->view->status['locale'][$a]['percent'];
        $bp = $this->view->status['locale'][$b]['percent'];
        if ($ap == $bp) {
            return strcmp($a, $b);
        }
        return ($ap > $bp) ? -1 : 1;
    }

    /**
     * Get manual model
     *
     * @return ManualModel
     */
    protected function _getManualModel()
    {
        if (null === $this->_manualModel) {
            $this->_manualModel = new ManualModel();
        }
        return $this->_manualModel;
    }

    protected function _getReleaseModel()
    {
        if (null === $this->_releaseModel) {
            require_once dirname(dirname(__FILE__)) . '/models/ReleaseModel.php';
            $this->_releaseModel = new ReleaseModel('ZendFramework');
        }
        return $this->_releaseModel;
    }

    /**
     * Create and return search form
     *
     * @param  array $locales
     * @param  array $versions
     * @param  int $version
     * @return Zend_Form
     */
    protected function _getSearchForm(array $locales, array $versions, $version)
    {
        if (null === $this->_searchForm) {
            require_once dirname(__FILE__) . '/../forms/ManualSearch.php';
            $this->_searchForm = new Application_Form_ManualSearch(array(
                'action'   => '/manual/search',
                'method'   => 'get',
                'locales'  => $locales,
                'language' => $this->_getParam('language'),
                'versions' => $versions,
                'version'  => $version,
            ));
        }
        return $this->_searchForm;
    }

    /**
     * Get search index path
     *
     * @return string
     */
    protected function _getIndexPath($version)
    {
        if (!preg_match('/^\d+\.\d+$/', $version)) {
            throw new Exception('Invalid version specified');
        }
        $config = Zend_Registry::get('config');
        $path   = $config->index->manual->path . "/$version";

        return $path;
    }

    protected function _getManualScriptPath()
    {
        $config = Zend_Registry::get('config');
        return $config->manual->views;
    }

    protected function _isValidManualSection($sectionName, $version = null, $language = 'en')
    {
        if ($version == null) {
            $version = $this->_getManualModel()->getManualVersion('current');
        }
        $relPath = "manual/" . $version . "/" . $language . "/" . $sectionName . ".phtml";
        $scriptPath = $this->view->getScriptPath($relPath);

        return ($scriptPath !== false);
    }

}
