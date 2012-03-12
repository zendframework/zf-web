<?php
/**
 * Javascript encode a string to conceal it from robots.
 *
 */
class Zend_View_Helper_JsEncode
{
    /**
     * Javascript encode a string to conceal it from robots.
     *
     * @param string $string           String to encode
     * @return string                  Javascript decoder with encoded payload
     */
    public function jsEncode($string)
    {
    	$payload = '';
		foreach (str_split($string) as $char) {
			$payload .= '%' . dechex(ord($char));
		}
		return '<script language="javascript">'
			 .     "document.write(unescape('$payload'));"
			 . '</script>';
    }
}
