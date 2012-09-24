<?php

namespace Archives;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ArchivesController extends AbstractActionController
{
    /**
     * All mailing list IDs assigned by Nabble
     */
    const ALL           = 'f634137';
    const ANNOUNCEMENTS = 'f680114';
    const AUTH          = 'f676055';
    const CONTRIBUTORS  = 'f680267';
    const CORE          = 'f678360';
    const DATABASE      = 'f671793';
    const DOCUMENTATION = 'f679193';
    const FORMATS       = 'f677857';
    const GDATA         = 'f675049';
    const GENERAL       = 'f634138';
    const I18N          = 'f677025';
    const MVC           = 'f663775';
    const SERVER        = 'f680177';
    const WEB_SERVICES  = 'f675401';

    protected $map = array(
        'all'           => self::ALL,
        'announcements' => self::ANNOUNCEMENTS,
        'auth'          => self::AUTH,
        'core'          => self::CORE,
        'contributors'  => self::CONTRIBUTORS,
        'database'      => self::DATABASE,
        'documentation' => self::DOCUMENTATION,
        'formats'       => self::FORMATS,
        'gdata'         => self::GDATA,
        'general'       => self::GENERAL,
        'i18n'          => self::I18N,
        'mvc'           => self::MVC,
        'server'        => self::SERVER,
        'web-services'  => self::WEB_SERVICES,
    );

    public function listAction()
    {
        $list = $this->params()->fromRoute('id', 'all');
        $list = strtolower($list);
        if (!isset($this->map[$list])) {
            $list = 'all';
        }
        $model = new ViewModel(array(
            'list' => $this->map[$list],
        ));
        $model->setTemplate('archives/list');
        return $model;
    }

    public function subscribeAction()
    {
        $model = new ViewModel();
        $model->setTemplate('archives/subscribe');
        return $model;
    }
}
