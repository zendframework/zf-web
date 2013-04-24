		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$form = new Zend_Form();
		$element = new Zend_Form_Element_Text('element');

		$element->setDecorators(array(
			array('ViewHelper'),
			array(array('data' => 'HtmlTag'), array('tag' => 'span')),
			array('Label', array('tag' => 'strong')),
			array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'=>'row'))
		));

		$decorators = $element->getDecorators();
		$html = $element->render();
		$element->setDecorators($decorators);
		$html2 = $element->render();
		$decorators2 = $element->getDecorators();

		echo count($decorators);
		var_dump($decorators);
		echo "-------------------<br>";
		echo count($decorators2);
		var_dump($decorators2);
