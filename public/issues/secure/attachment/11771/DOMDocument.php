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
 * @package    Zend_Dom
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
 /**
 * @see Zend_Dom_Query_Css2Xpath
 */
require_once 'Zend/Dom/Query/Css2Xpath.php';

/**
 * @see Zend_Dom_Query_Result
 */
require_once 'Zend/Dom/Query/Result.php';
 /**
 * DOMDocument Class what has 
 * Query DOM structures based on CSS selectors and/or XPath
 * 
 * @package    Zend_Dom
 * @subpackage Query
 * @copyright  Copyright (C) 2008 - Present, Zend Technologies, Inc.
 * @license    New BSD {@link http://framework.zend.com/license/new-bsd}
 */
class Zend_Dom_DOMDocument extends DOMDocument
{
    /**
     * Perform a CSS selector query
     *
     * @param  string $query
     * @return Zend_Dom_Query_Result
     */
    public function query($query)
    {
        $xpathQuery = Zend_Dom_Query_Css2Xpath::transform($query);
        return $this->queryXpath($xpathQuery, $query);
    }

    /**
     * Perform an XPath query
     *
     * @param  string $xpathQuery
     * @param  string $query CSS selector query
     * @return Zend_Dom_Query_Result
     */
    public function queryXpath($xpathQuery, $query = null)
    {
        $nodeList   = $this->_getNodeList($xpathQuery);
        return new Zend_Dom_Query_Result($query, $xpathQuery, $this, $nodeList);
    }

    /**
     * Prepare node list
     *
     * @param  DOMDocument $document
     * @param  string|array $xpathQuery
     * @return array
     */
    protected function _getNodeList($xpathQuery)
    {
        $xpath      = new DOMXPath($this);
        $xpathQuery = (string) $xpathQuery;
        if (preg_match_all('|\[contains\((@[a-z0-9_-]+),\s?\' |i', $xpathQuery, $matches)) {
            foreach ($matches[1] as $attribute) {
                $queryString = '//*[' . $attribute . ']';
                $attributeName = substr($attribute, 1);
                $nodes = $xpath->query($queryString);
                foreach ($nodes as $node) {
                    $attr = $node->attributes->getNamedItem($attributeName);
                    $attr->value = ' ' . $attr->value . ' ';
                }
            }
        }
        return $xpath->query($xpathQuery);
    }
}
