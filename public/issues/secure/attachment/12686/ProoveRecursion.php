<?php
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

class SingleEntity
	{
	/**
	 * Alternative Entity
	 * @var SingleEntity
	 */
	public $AlternativeEntity = Null;

	/**
	 * Single Entity
	 *
	 * @return SingleEntity
	 */
	public function SingleEntity()
		{
		return new SingleEntity();
		}
	}

$Discover = new Zend_Soap_AutoDiscover
	(
	'Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex'
	);

$Discover->setClass('SingleEntity');
$Discover->handle();
