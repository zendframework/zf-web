<?php
/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 */

/** Zend_Controller_Router_Route */
require_once 'Zend/Controller/Router/Route.php';

/** PHPUnit2 test case */
require_once 'PHPUnit2/Framework/TestCase.php';

/**
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage UnitTests
 */
class Zend_Controller_RouteTest extends PHPUnit2_Framework_TestCase
{

    public function testStaticMatch()
    {

        $route = new Zend_Controller_Router_Route('users/all');
        $values = $route->match('users/all');

        $this->assertEquals(array(), $values);

    }

    public function testStaticMatchWithDefaults()
    {

        $route = new Zend_Controller_Router_Route('users/all', array('controller' => 'ctrl'));
        $values = $route->match('users/all');

        $this->assertEquals('ctrl', $values['controller']);

    }

    public function testNotMatched()
    {

        $route = new Zend_Controller_Router_Route('users/all');
        $values = $route->match('users/martel');
        
        $this->assertEquals(false, $values);
    }
    
    public function testNotMatchedWithVariablesAndDefaults()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action', array('controller' => 'index', 'action' => 'index'));
        $values = $route->match('archive/action/bogus');
        
        $this->assertEquals(false, $values);
    }
    
    
    public function testNotMatchedWithVariablesAndStatic() 
    {

        $route = new Zend_Controller_Router_Route('archive/:year/:month');
        $values = $route->match('ctrl/act/2000');

        $this->assertEquals(false, $values);

    }

    public function testVariableValues()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year');
        $values = $route->match('ctrl/act/2000');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertEquals('2000', $values['year']);

    }

    public function testVariablesWithDefault()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('year' => '2006'));
        $values = $route->match('ctrl/act');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertEquals('2006', $values['year']);

    }

    public function testVariablesWithNullDefault() // Kevin McArthur
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('year' => null));
        $values = $route->match('ctrl/act');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertNull($values['year']);

    }
    
    public function testVariablesWithDefaultAndValue()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('year' => 2006));
        $values = $route->match('ctrl/act/2000');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertEquals('2000', $values['year']);

    }

    public function testVariablesWithRequirementAndValue()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array(), array('year' => '\d+'));
        $values = $route->match('ctrl/act/2000');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertEquals('2000', $values['year']);

    }

    public function testVariablesWithRequirementAndIncorrectValue()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array(), array('year' => '\d+'));
        $values = $route->match('ctrl/act/2000t');

        $this->assertEquals(false, $values);

    }

    public function testVariablesWithDefaultAndRequirement()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('year' => 2006), array('year' => '\d+'));
        $values = $route->match('ctrl/act/2000');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertEquals('2000', $values['year']);
        
    }

    public function testVariablesWithDefaultAndRequirementAndIncorrectValue()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('year' => 2006), array('year' => '\d+'));
        $values = $route->match('ctrl/act/2000t');

        //$this->assertEquals('ctrl', $values['controller']);
        //$this->assertEquals('act', $values['action']);
        //$this->assertEquals('2006', $values['year']);
        
        // Ruby on Rails returns false for this test
        $this->assertEquals(false, $values);

    }

    public function testVariablesWithDefaultAndRequirementAndWithoutValue()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('year' => '2006'), array('year' => '\d+'));
        $values = $route->match('ctrl/act');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('act', $values['action']);
        $this->assertEquals('2006', $values['year']);

    }

    public function testVariablesWithDefaultInTheMiddle()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('action' => 'action'), array('year' => '\d+'));
        $values = $route->match('ctrl//2000');

        $this->assertEquals('ctrl', $values['controller']);
        $this->assertEquals('action', $values['action']);
        $this->assertEquals('2000', $values['year']);

    }

    public function testVariablesWithDefaultInTheMiddleAndDefaultHasRequirement()
    {

        $route = new Zend_Controller_Router_Route(':controller/:action/:year', array('action' => 'action'), array('action' => '[a-z]+', 'year' => '\d+'));
        $values = $route->match('ctrl//2000');

        $this->assertEquals(false, $values);

    }

    public function testAssemble()
    {

        $route = new Zend_Controller_Router_Route('authors/:name');
        $url = $route->assemble(array('name' => 'martel'));

        $this->assertEquals('authors/martel', $url);

    }

    public function testAssembleWithoutValue()
    {

        $route = new Zend_Controller_Router_Route('authors/:name');
        try {
            $url = $route->assemble();   
        } catch (Exception $e) {
            return true;
        }

        $this->fail();

    }

    public function testAssembleWithDefault()
    {

        $route = new Zend_Controller_Router_Route('authors/:name', array('name' => 'martel'));
        $url = $route->assemble();

        $this->assertEquals('authors/martel', $url);

    }

    public function testAssembleWithDefaultAndValue()
    {

        $route = new Zend_Controller_Router_Route('authors/:name', array('name' => 'martel'));
        $url = $route->assemble(array('name' => 'mike'));

        $this->assertEquals('authors/mike', $url);

    }

}