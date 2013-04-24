<?php
require_once 'Zend/Config.php';
class Zend_ConfigTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
		$this->data = array(
			'float' => '1.2',
			'integer' => '1200',
			'pseudo_int' => '0124',
			'string' => 'foobar',
			'bool_true' => 'true',
			'bool_false' => 'false',
		);
	}


	public function testConversion()
	{
		$config = new Zend_Config($this->data, false, true);
		$this->assertEquals(1.2, $config->float);
		$this->assertEquals(1200, $config->integer);
		$this->assertEquals('0124', $config->pseudo_int);
		$this->assertEquals('foobar', $config->string);
		$this->assertEquals(true, $config->bool_true);
		$this->assertEquals(false, $config->bool_false);


		$array = $config->toArray();

		$this->assertEquals(1.2, $array['float']);
		$this->assertEquals(1200, $array['integer']);
		$this->assertEquals('0124', $array['pseudo_int']);
		$this->assertEquals('foobar', $array['string']);
		$this->assertEquals(true, $array['bool_true']);
		$this->assertEquals(false, $array['bool_false']);

	}

	public function testDefaultBehaviour()
	{
		$config = new Zend_Config($this->data, false, false);
		$this->assertEquals('1.2', $config->float);
		$this->assertEquals('1200', $config->integer);
		$this->assertEquals('0124', $config->pseudo_int);
		$this->assertEquals('foobar', $config->string);
		$this->assertEquals('true', $config->bool_true);
		$this->assertEquals('false', $config->bool_false);

		$array = $config->toArray();

		$this->assertEquals('1.2', $array['float']);
		$this->assertEquals('1200', $array['integer']);
		$this->assertEquals('0124', $array['pseudo_int']);
		$this->assertEquals('foobar', $array['string']);
		$this->assertEquals('true', $array['bool_true']);
		$this->assertEquals('false', $array['bool_false']);
	}
}
