<?php

require_once 'Zend/Feed.php';

/** ReleaseModel */
require_once dirname(dirname(__FILE__)) . '/models/ReleaseModel.php';

/** FeedsModel */
require_once dirname(dirname(__FILE__)) . '/models/FeedsModel.php';


class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        // get feeds to display on home page
        $model = new FeedsModel();

        $this->view->feeds = array(
            'management'  => $model->getFeed(FeedsModel::MANAGEMENT),
            'developer'   => $model->getFeed(FeedsModel::DEVELOPER),
            'contributor' => $model->getFeed(FeedsModel::CONTRIBUTOR),
        );

        // get the version number of the latest release
        $this->view->release = new ReleaseModel('ZendFramework');

        // get the version number of the latest release
        $this->view->gdata = new ReleaseModel('ZendGdata');

        $this->view->tab = 'index';
    }

    public function noRouteAction()
    {
        $this->_redirect('/');
    }

}
