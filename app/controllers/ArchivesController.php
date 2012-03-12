<?php

class ArchivesController extends Zend_Controller_Action
{
    /**
     * All mailing list IDs assigned by Nabble
     */
    const ALL           = 'f16154';
    const ANNOUNCEMENTS = 'f16198';
    const AUTH          = 'f16181';
    const CORE          = 'f16191';
    const DATABASE      = 'f16192';
    const DOCUMENTATION = 'f16193';
    const GENERAL       = 'f15440';
    const GDATA         = 'f16698';
    const I18N          = 'f16194';
    const FORMATS       = 'f16195';
    const MVC           = 'f16155';
    const SERVER        = 'f16196';
    const WEB_SERVICES  = 'f16197';

    public static $nabbleIDsToNames = array(
                    self::ALL             => 'All',
                    self::ANNOUNCEMENTS   => 'Announcements',
                    self::AUTH            => 'Authentication and Authorization',
                    self::CORE            => 'Core',
                    self::DATABASE        => 'Database',
                    self::DOCUMENTATION   => 'Documentation',
                    self::GENERAL         => 'General',
                    self::GDATA           => 'Google GData',
                    self::I18N            => 'Internationalization and Localization',
                    self::FORMATS         => 'Formats',
                    self::MVC             => 'MVC',
                    self::SERVER          => 'Server',
                    self::WEB_SERVICES    => 'Web Services'
                );
    /**
     * Display a version of the license.
     */
    public function indexAction() {
        
        $this->_forward('all', 'archives');
    }
    
    public function allAction() {
        $this->renderView(self::ALL);
    }
    
    public function announcementsAction() {
        $this->renderView(self::ANNOUNCEMENTS);
    }
    
    public function authAction() {
        $this->renderView(self::AUTH);
    }
    
    public function coreAction() {
        $this->renderView(self::CORE);
    }
    
    public function databaseAction() {
        $this->renderView(self::DATABASE);
    }
    
    public function documentationAction() {
        $this->renderView(self::DOCUMENTATION);
    }
    
    public function generalAction() {
        $this->renderView(self::GENERAL);
    }
    
    public function gdataAction() {
        $this->renderView(self::GDATA);
    }
    
    public function i18nAction() {
        $this->renderView(self::I18N);
    }
    
    public function formatsAction() {
        $this->renderView(self::FORMATS);
    }
    
    public function mvcAction() {
        $this->renderView(self::MVC);
    }
    
    public function serverAction() {
        $this->renderView(self::SERVER);
    }
    
    public function webServicesAction() {
        $this->renderView(self::WEB_SERVICES);
    }
    
    private function renderView($id) {
        $this->view->id = $id;
        $this->render('list');
    }
}
