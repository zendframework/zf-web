<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Mime
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/**
 * Test helper
 */
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'TestHelper.php';

/**
 * Zend_Mime_Decode
 */
require_once 'Zend/Mime/Decode.php';

/**
 * PHPUnit test case
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @package 	Zend_Mime
 * @subpackage  UnitTests
 */
class Zend_Mime_DecodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * MIME decode test object
     *
     * @var Zend_Mime_Decode
     */
    protected $_decode = null;
    protected $_testText;

    protected function setUp()
    {
    }

    public function testSplitHeaderField()
    {
        $boundary = "----=_Alternative_1211565553483705f1280701.15526894A";
        $header   = "Content-Type: multipart/alternative; "
                  . "boundary=\"$boundary\"";
        $split    = Zend_Mime_Decode::splitHeaderField($header);
        $this->assertEquals(2, count($split));
        $this->assertEquals($boundary, $split['boundary']);
    }

    public function testSplitHeaderFieldWithExtraWhitespace()
    {
        $boundary = "----=_Alternative_1211565553483705f1280701.15526894A";
        $header   = "Content-Type: multipart/alternative; "
                  . "boundary = \"$boundary\"";
        $split    = Zend_Mime_Decode::splitHeaderField($header);
        $this->assertEquals(2, count($split));
        $this->assertEquals($boundary, $split['boundary']);
    }

    public function testSplitHeaderFieldReturnsOnlyWantedPart()
    {
        $boundary = "----=_Alternative_1211565553483705f1280701.15526894A";
        $header   = "Content-Type: multipart/alternative; "
                  . "boundary=\"$boundary\"";
        $split    = Zend_Mime_Decode::splitHeaderField($header, 'boundary');
        $this->assertEquals($boundary, $split);
    }

    public function testSplitHeaderFieldReturnsArrayWithFirstName()
    {
        $boundary = "----=_Alternative_1211565553483705f1280701.15526894A";
        $header   = "Content-Type: multipart/alternative; "
                  . "boundary=\"$boundary\"";
        $split    = Zend_Mime_Decode::splitHeaderField($header, null, 'content-type');
        $this->assertEquals(2, count($split));
        $this->assertTrue(isset($split['content-type']), "'content-type' element is set");
        $this->assertEquals($boundary, $split['boundary']);
    }
}
