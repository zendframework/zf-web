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
 * @package    Zend_Http
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */

require_once 'Zend/Http/UserAgent/Mobile.php';

/**
 * Test class for Zend_Http_UserAgent_Mobile.
 *
 * @category   Zend
 * @package    Zend_Http
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Http
 * @group      Zend_Http_UserAgent
 */
class Zend_Http_UserAgent_MobileTest extends PHPUnit_Framework_TestCase
{
    /** @group ZF-11308 */
    public function testUaSignatureListShouldNotContainDuplicateValues()
    {
        $stub = $this->getMock('Zend_Http_UserAgent_Mobile');
        $reflection_class = new ReflectionClass("Zend_Http_UserAgent_Mobile");

        $property = $reflection_class->getProperty('_uaSignatures');
        $property->setAccessible(true);

        $uaSignatures = $property->getValue($stub);
        $result = array_diff_key($uaSignatures,
            array_unique($uaSignatures, SORT_STRING));
        $this->assertEmpty($result);
    }
}
