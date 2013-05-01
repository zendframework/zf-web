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
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */


/**
 * Standalone Validate_Hostname test script
 * 
 * Please note this file should be encoded as UTF-8 in order to run correctly
 * 
 * @see Zend_Validate_Hostname
 */
set_include_path(get_include_path() . PATH_SEPARATOR . '../../../library/');
require_once('Zend/Validate/Hostname.php');

// Set up expected values
$valuesExpected = array(
    array(Zend_Validate_Hostname::CHECK_TLD, false, array('bürger.de', 'hãllo.de', 'hållo.se')),
    array(Zend_Validate_Hostname::CHECK_IDN, true, array('bürger.de', 'hãllo.de', 'hållo.se')),
    array(Zend_Validate_Hostname::CHECK_IDN, true, array('bÜrger.de', 'hÃllo.de', 'hÅllo.se')),
    array(Zend_Validate_Hostname::CHECK_IDN, false, array('hãllo.se', 'bürger.com', 'hãllo.uk'))
);

// Run test
$ok = true;
foreach ($valuesExpected as $element) {
    $validator = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, $element[0]);
    foreach ($element[2] as $input) {
        print "$input - ";
        if ($validator->isValid($input) === $element[1]) {
            print 'Pass';
        } else { 
            print 'Fail ' . implode("\n", $validator->getMessages());
            $ok = false;
        }
        print "\n";
    }
    print "\n";
}
print ($ok) ? "All tests passed OK :-)\n" : "Some tests failed!\n";
