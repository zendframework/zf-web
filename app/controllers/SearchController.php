<?php

class SearchController extends Zend_Controller_Action
{
    protected $_indices = array();

    protected $_indexTypes = array('manual');

    protected $_manualModel;

    protected $_manualVersion;

    protected $_searchForm;

    /**
     * Search the site
     *
     * @return void
     */
    public function indexAction()
    {
        $form    = $this->getSearchForm();
        $this->view->form = $form;

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

        $searchData = $this->search($form->getValues());

        $this->view->assign(array(
            'controller' => $this,
            'page'       => $this->_getParam('page', 1),
            'hits'       => $searchData['hits'],
            'searchers'  => $searchData['searchers']
        ));
        $this->render('search-results');
    }

    /**
     * Perform the search
     *
     * @param  array $data
     * @return array
     */
    public function search($data)
    {
        $type  = (array) $data['type'];
        $lang  = $data['language'];
        $query = $data['query'];

        $bootstrap    = $this->getInvokeArg('bootstrap');
        $cacheManager = $bootstrap->getResource('cachemanager');
        $searchCache  = $cacheManager->getCache('search');

        require_once dirname(dirname(__FILE__)) . '/models/SearcherModel.php';
        $searchers = array();
        foreach ($this->_indexTypes as $indexType) {
            // Prepare all searchers even they are not used here
            // (some of them may be additionaly used within view helpers)
            $searchers[$indexType] = array();
            $searchers[$indexType] = new SearcherModel($this->getIndexPath($indexType), $searchCache);
        }

        if ('all' == $type || in_array('all', $type)) {
            $searchTypes = $this->_indexTypes;
        } else {
            $searchTypes = array_intersect($this->_indexTypes, (array) $type);
        }

        $allResults = array();
        foreach ($searchTypes as $type) {
            if (('manual' == $type) && $lang && ($lang != 'all')) {
                $escapedLang = '\\' . implode('\\', str_split($lang));
                $allResults[$type] = $searchers[$type]->search("+($query) +lang:$escapedLang");
            } else {
            	$allResults[$type] = $searchers[$type]->search($query);
            }
        }

        return array('searchers' => $searchers, 'hits' => $allResults);
    }

    /**
     * Get a search index path
     *
     * @param  string $type
     * @return string
     */
    public function getIndexPath($type)
    {
        if (!in_array($type, $this->_indexTypes)) {
            throw new Exception('Invalid search index specified');
        }

        $config = Zend_Registry::get('config');

        if (strstr($type, 'jira')) {
            $thisType = substr($type, 5);
            $path     = $config->index->jira->{$thisType}->path;
            if ('/' != $path[0]) {
                $path = $config->index->basePath . '/' . $path;
            }
        } else {
            $path   = $config->index->{$type}->path;
            if ('/' != $path[0]) {
                $path = $config->index->basePath . '/' . $path;
            }
            if ($type == 'manual') {
                $path .= '/' . $this->getManualVersion();
            }
        }

        return $path;
    }

    /**
     * Create and return search form
     *
     * @return Zend_Form
     */
    public function getSearchForm()
    {
        if (null === $this->_searchForm) {
            $form = new Zend_Form(array(
                'action'     => '/search',
                'method'     => 'get',
                'attribs'    => array('id' => 'searchForm'),
                'decorators' => array(
                    'FormElements',
                    array('HtmlTag', array('tag' => 'ul', 'class' => 'searchForm')),
                    'Form',
                )
            ));
            $form->addElement('text', 'query', array(
                        'filters'  => array('StringTrim'),
                        'required' => true,
                        'label'    => 'Search term:',
                        'size'     => 30,
                        'decorators' => array(
                            'ViewHelper',
                            'Errors',
                            'Label',
                            array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
                        ),
                    ))
                 ->addElement('select', 'language', array(
                        'required' => false,
                        'label'    => 'Language:',
                        'decorators' => array(
                            'ViewHelper',
                            'Errors',
                            'Label',
                            array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
                        ),
                    ))
                 ->addElement('multiselect', 'type', array(
                        'required' => false,
                        'label'    => 'Search in:',
                        'decorators' => array(
                            'ViewHelper',
                            'Errors',
                            'Label',
                            array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
                        ),
                    ))
                 ->addElement('submit', 'search', array(
                        'required' => false,
                        'ignore'   => true,
                        'label'    => 'Search!',
                        'class'    => 'btn_submit',
                        'decorators' => array(
                            'ViewHelper',
                            array('HtmlTag', array('tag' => 'li'))
                        )
                    ));

            $form->search->removeDecorator('Label');

            $languages = array('all' => 'All');
            foreach ($this->getManualModel()->getLocales() as $key => $locale) {
                $languages[$key] = html_entity_decode(
                    $locale['locale_name'],
                    ENT_COMPAT,
                    'UTF-8'
                );
            }
            $form->language->setMultiOptions($languages)
                           ->setValue('en')
                           ->addValidator('InArray', false, array(array_keys($languages)))
                           ->setAttrib('escape', false);

            $types = array(
                'all'          => 'Entire Site',
                'confluence'   => 'Wiki',
                'jira_comments'=> 'Issue Tracker - Comments',
                'jira_issues'  => 'Issue Tracker - Issues',
                'manual'       => 'Documentation',
            );
            $form->type->setMultiOptions($types)
                       ->setValue('all')
                       ->addValidator('InArray', false, array(array_keys($types)));

            $this->_searchForm = $form;
        }
        return $this->_searchForm;
    }

    /**
     * Get manual model
     *
     * @return ManualModel
     */
    public function getManualModel()
    {
        if (null === $this->_manualModel) {
            require_once dirname(__FILE__) . '/../models/ManualModel.php';
            $this->_manualModel = new ManualModel();
        }
        return $this->_manualModel;
    }

    public function getManualVersion()
    {
        if (null === $this->_manualVersion) {
            $this->_manualVersion = $this->getManualModel()->getManualVersion('current');
        }
        return $this->_manualVersion;
    }
}
