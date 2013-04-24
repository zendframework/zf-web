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
 * HTML Form Validate_Hostname test script
 * 
 * Please note the HTML page containing the form must be encoded as charset=utf-8
 * These characters are then passed to PHP as UTF-8 encoded.
 * 
 * To allow the display of literal UTF-8 characters in this HTML page this file 
 * needs to be encoded as UTF-8 though this is not required to allow users to 
 * enter UTF-8 characters in the form.
 * 
 * @see Zend_Validate_Hostname
 */

// Change next line to path to your installation of Zend Framework
set_include_path(get_include_path() . PATH_SEPARATOR . '/path/to/library');


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Validate_Hostname POST test</title>

<!-- Meta Tags -->
<meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
</head>

<body>
    <h1>Validate_Hostname POST test</h1>
    <p>Please use this form to test UTF-8 hostnames. A list of supported Top-Level Domains exists in 
    <tt>Zend_Validate_Hostname</tt> in the <tt>$_registeredTlds</tt> class property. You can enable/disable IDN support by changing the radio button below.</p>
    <p>Some example valid IDN domains to test include: <em>bürger.de, hãllo.de, hållo.se, bÜrger.de, hÃllo.de, hÅllo.se</em></p>
    <p>Some example invalid IDN domains to test include: <em>hãllo.se, bürger.com, hãllo.uk</em></p>
    <?php 
    if ($_POST['action'] === 'validate'): 
        require_once('Zend/Validate/Hostname.php');
        $characters = strip_tags($_POST['chars']);
        
        $idn = false;
        if ($_POST['idn'] === "yes") {
            $validator = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, Zend_Validate_Hostname::CHECK_IDN);
            $idn = true;
        } else {
            $validator = new Zend_Validate_Hostname();
        }
    ?>
    
        <h2>Entered characters: <?php echo $characters; ?></h2>
        
        <p><?php echo ($idn) ? 'IDN supported' : 'IDN not supported'; ?></p>
        
        <p>Testing for match in Zend_Validate_Hostname: 
        
        <?php
        if ($validator->isValid($characters)) {
            echo '<strong>Passed</strong>';
        } else {
            echo "<strong>Failed</strong>, validator messages:<ul>\n";
            foreach ($validator->getMessages() as $message) {
                echo "<li>$message</li>\n";
            }
            echo "</ul>\n";
        }
        ?>
        
        </p>
    
    <?php endif; ?>
    
    <form action="HostnameTestForm.php" method="post">
        <fieldset>
            <legend>Test IDN domains</legend>
            <input type="hidden" name="action" value="validate" />
            Support IDN: 
            <label><input type="radio" name="idn" value="yes" checked /> Yes</label>
            <label><input type="radio" name="idn" value="no" /> No</label><br />
            <label>Domain name: <input type="text" name="chars" /></label>
            <input type="submit" value="Test string"/>
        </fieldset>
    </form>
</body>
</html>