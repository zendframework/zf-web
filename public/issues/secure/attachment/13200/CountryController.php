<?php
class Admin_CountryController extends Zend_Controller_Action
{

	public function addAction()
    {

        $form = new Admin_Form_CountryForm('/admin/country/add');
        if($this->getRequest()->isPost())
        {
                if($form->isValid($this->getRequest()->getParams()))
                {
                    $data = $form->getValues();
                    unset($data['Submit']);

                    $data = array(
                                    'name' =>	$form->getValue('name'),
                                    'code' =>	$form->getValue('code')
                    );
                    $country = new Admin_Model_Country;
                    $country->save($data);
                    $this->_helper->FlashMessenger('Added one Record!');
                    $this->_redirect('/admin/country/add');
                }
                else
                {
                    $this->_helper->FlashMessenger('No good Data!');
                }
        }
        $this->view->form = $form;

    }
	
	public function editAction()
	{
		
		
		$id=$this->_request->getParam('id');
		$form = new Admin_Form_CountryForm('/admin/country/edit',$id);
		
			
		$country = new Admin_Model_Country();
		
		if($this->getRequest()->isPost())
		{
			if($form->isValid($_POST))
			{
				$data = array(
							'id' =>	$id,
							'name' =>	$form->getValue('name'),
							'code' =>	$form->getValue('code')
				);
				$country->save($data);
				$form->reset();
				$this->_helper->FlashMessenger('Saved Record!');
				$this->_redirect('/admin/country/');
			}
			else
				$this->_helper->FlashMessenger('No good Data!');
		}
		else
		{
			
				$countryData = $country->findByPK($id);
				$form->populate($countryData);
		}
		$this->view->form = $form;
		
	}
	public function indexAction()
	{
		$country = new Admin_Model_Country();
		$countryArr = $country->fetchAll();
		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($countryArr->toArray()));
		$this->view->paginator = $paginator;
		$this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
	}
	
	public function getRecordsByCountryAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		echo 'hello';
	}
}