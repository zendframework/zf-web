<?php

class AdsController extends Zend_Controller_Action 
{

    public function init()
    {
        $this->view->headTitle(Standart_Main::translate('infoPagesHeadTitle'));
        $this->view->banner(Banners::getPositions(-2));
        $this->view->newsHeading = News::getHeading();
        $this->view->placeholder('rssLink')->set($this->view->feedLink());
    }
    
    public function indexAction()
    {
        $this->_redirect('/ads/categories-list/');
    }
    
    public function categoriesListAction()
    {
        $this->view->adsCategories = Ads_Categories::getList();
        $this->view->adsTypes = Ads_Types::getList();
        $this->view->colspan = $this->view->adsTypes->count()+1;
    }
    
    public function byCategoryAction()
    {
        
    }
    
    public function byTypeAction()
    {
        
    }
    
    public function detailsAction()
    {
    }
    
    public function editAction()
    {
        
        $id = $this->_request->getParam('id', 0);
        
        $ads = new Ads();
        $ad = $ads->find($id)->current();
        
        $user = Standart_Main::getLoggedUser();
        
        if ($ad && $ad->user_id != $user->id) {
            $this->_redirect('/ads/categories-list/');
        }
        
        $form = new AdEditForm();
        
        if ($ad) {
            $form->populate($ad->toArray());
        } else {
            $form->populate(array(
                'language_id' => Standart_Main::getLanguage('id'),
                'user_id' => $user->id,
            ));
        }
        
        if (Standart_Main::buttonPressed('doSave')) {
            if ($form->isValid($_POST)) {
                
                $values = $form->getValues();

                $values['ads']['valid_until'] .= ' 23:59:59';
                
                $date = new Standart_Date();
                $currentDate = $date->get(Standart_Date::PG_TIMESTAMP);
                
                
                $values['ads']['updated'] = $currentDate;
                if (!$ad) {
                    $values['ads']['added'] = $currentDate;
                }
                
                $akismetData = array(
                    'user_ip'              => $_SERVER['REMOTE_ADDR'],
                    'user_agent'           => $_SERVER['HTTP_USER_AGENT'],
                    'comment_type'         => 'comment',
                    'comment_author'       => $user->first_name,
                    'comment_author_email' => $user->email,
                    'comment_content'      => $values['ads']['description'],                
                );
                
                $akismet = new Zend_Service_Akismet(
                    Zend_Registry::get('config')->akismet->apiKey,
                    Zend_Registry::get('config')->host->default
                );
                
                if ($akismet->isSpam($akismetData)) {
                    $values['ads']['is_spam'] = 1;
                    $this->view->message(Standart_Main::translate('adIsSpam'), 'error');
                } else {
                    $values['ads']['is_spam'] = 0;
                }
                
                if ($ad) {
                    $ads->update(
                        $values['ads'],
                        $ads->getAdapter()->quoteInto('id = ?', $ad->id)
                    );
                } else {
                    $ads->insert($values['ads']);
                    $id = $ads->getLastSequenceId($ads->getSequenceName());
                }
                
                $this->_redirect('/ads/edit/id/'.$id.'/');
                
            } else {
                $this->view->message(Standart_Main::translate('invalidFormData'), 'error');
            }
        }
        
        $this->view->form = $form;
    }
    
    public function responseAction()
    {
        $id = $this->_request->getParam('id', 0);
        
        if (!$id) {
            $this->_redirect('/ads/categories-list/');
        }
        
        $ads = new Ads();
        $ad = $ads->find($id)->current();
        
        if (!$ad) {
            $this->_redirect('/ads/categories-list/');
        }
        
        $form = new AdsResponseForm();
        $form->getSubForm('ads_responses')->getElement('ad_id')->setValue($id);
        
        if (Standart_Main::buttonPressed('doResponse')) {
            
            if ($form->isValid($_POST)) {
                
                $values = $form->getValues();
                
                $akismetData = array(
                    'user_ip'              => $_SERVER['REMOTE_ADDR'],
                    'user_agent'           => $_SERVER['HTTP_USER_AGENT'],
                    'comment_type'         => 'comment',
                    'comment_author'       => $values['ads_responses']['name'],
                    'comment_author_email' => $values['ads_responses']['email'],
                    'comment_content'      => $values['ads_responses']['content'],                
                
                );
                
                $akismet = new Zend_Service_Akismet(
                    Zend_Registry::get('config')->akismet->apiKey,
                    Zend_Registry::get('config')->host->default
                );
                
                if ($akismet->isSpam($akismetData)) {
                    $values['ads_responses']['is_spam'] = 1;
                    $this->view->message(Standart_Main::translate('adsResponseIsSpam'), 'error');
                }
                
                $adsResponses = new Ads_Responses();
                $adsResponses->insert($values['ads_responses']);
                
                $this->_redirect('/ads/response/id/'.$id.'/');
                
            } else {
                
                $this->view->message(Standart_Main::translate('invalidFormData'), 'error');
                
            }
            
        }
        
        $this->view->form = $form;
        
    }
    
}