<?php
class XmlController extends Zend_Controller_Action
{
    public $validEndpoints = array(
        'zend-config-xml/1.0',
    );

    public function namespaceAction()
    {
        $request   = $this->getRequest();
        $namespace = $request->getParam('namespace', false);
        if (!$namespace) {
            throw new Zend_Controller_Action_Exception('Requested namespace does not exist', 404);
        }
        if (!in_array($namespace, $this->validEndpoints)) {
            throw new Zend_Controller_Action_Exception('Requested namespace ("' . $namespace . '") does not exist', 404);
        }

        $namespaceScript = str_replace(array('/', '.', '_'), '-', $namespace);
        if (!$this->view->getScriptPath('xml/' . $namespaceScript . '.phtml')) {
            throw new Zend_Controller_Action_Exception('Requested namespace does not exist', 404);
        }
        $this->render($namespaceScript);
    }
}
