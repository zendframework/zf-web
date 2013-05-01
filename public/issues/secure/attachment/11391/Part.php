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
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Zend_Mime
 */
require_once 'Zend/Mime.php';

/**
 * Zend_Mime_Exception
 */
require_once 'Zend/Mime/Exception.php';

/**
 * Class representing a MIME part.
 *
 * @category   Zend
 * @package    Zend_Mime
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Techi_Mime_Part extends Zend_Mime_Part {

	public $location;

    /**
     * Create and return the array of headers for this MIME part
     *
     * @access public
     * @return array
     */
    public function getHeadersArray($EOL = Zend_Mime::LINEEND)
    {
		$headers = parent::getHeadersArray($EOL);
		
		if ($this->location)
		{
			$headers[] = array('Content-Location', $this->location);
		}
		
		return $headers;
	}
}
