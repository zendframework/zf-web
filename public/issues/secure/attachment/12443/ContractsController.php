<?php

class ContractsController extends Zend_Controller_Action
{

	/**
	 * Index page of management controller
	 *
	 * @return void
	 */
	public function indexAction() {}


	/**
	 * Search contracts
	 *
	 */
	public function searchAction()
	{
		$page = $this->_getParam('page', 1);
		$search_id = $this->_getParam('id');

		$s = new Bakery_Contracts_Searches();
		$params = $s->getSearchParams($search_id);

		if (empty($params)) {
			$this->view->contracts = array();
			return true;
		}

		$c = new Contracts_Details();
		$t = new Contracts_Tags();
		$f = new Contracts_Files();

		$select = $c->getAdapter()->select()->distinct();
		$select->from(array('c' => 'ctr_details'), array(
			'*',
		));
		$select->joinLeft(array('t' => 'ctr_tags'),
			'c.contract_id = t.contract_id',
			array()
		);
		$select->joinLeft(array('f' => 'ctr_files'),
			'c.contract_id = f.contract_id',
			array()
		);

		$paginator = Zend_Paginator::factory($select);
		$paginator->setDefaultScrollingStyle(PAGINATOR_STYLE);
		$paginator->setCurrentPageNumber($page);
		$paginator->setPageRange(7);
		$paginator->setItemCountPerPage(10);

		$this->view->contracts = $paginator;
		$this->view->search_id = $search_id;
	}

}