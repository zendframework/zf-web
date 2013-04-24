public function testChainingWorksWithWildcardAndNoParameters()
{
    $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('module' => 'simple', 'controller' => 'bar', 'action' => 'bar'));
    $bar = new Zend_Controller_Router_Route(':controller/:action/*', array('controller' => 'index', 'action' => 'index'));

    $chain = $foo->chain($bar);

    $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/foo/bar/');
    $res = $chain->match($request);

    $this->assertEquals('simple', $res['module']);
    $this->assertEquals('foo', $res['controller']);
    $this->assertEquals('bar', $res['action']);
}

public function testChainingWorksWithWildcardAndOneParameter()
{
    $foo = new Zend_Controller_Router_Route_Hostname('www.zend.com', array('module' => 'simple', 'controller' => 'foo', 'action' => 'bar'));
    $bar = new Zend_Controller_Router_Route(':controller/:action/*', array('controller' => 'index', 'action' => 'index'));

    $chain = $foo->chain($bar);

    $request = new Zend_Controller_Router_ChainTest_Request('http://www.zend.com/foo/bar/id/12');
    $res = $chain->match($request);

    $this->assertEquals('simple', $res['module']);
    $this->assertEquals('foo', $res['controller']);
    $this->assertEquals('bar', $res['action']);
}