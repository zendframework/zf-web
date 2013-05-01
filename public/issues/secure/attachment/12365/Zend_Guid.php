<?php

/**
 * Class representation of the globally unique identifier or GUID, a special type
 * of identifier used in software applications in order to provide a reference
 * number which is unique in any context.
 *
 * @author		Danny Verkade
 * @copyright	© 2009 MBWP Internetbureau
 */
class Zend_Guid
{
	/**
	 * @var array
	 */
	protected $bytes;

	/**
	 * @var Zend_Guid
	 */
	protected static $emptyGuid;

	/**
	 * Constructs a new Guid instance given its underlying value as a byte
	 * array.
	 *
	 * @param array $bytes a 16 element byte array.
	 */
	function __construct($bytes)
	{
		if (!is_array($bytes) || count($bytes) != 16) {
			throw new Zend_Exception("The argument must be a 16 element byte array");
		}
		for ($i = 0; $i < 16; $i++) {
			$b = $bytes[$i];
			if ((string)(int)$b !== (string)$b || $b < 0 || $b > 255) {
				throw new Zend_Exception("Value other than a byte at offset {$i}");
			}
		}
		$this->bytes = $bytes;
	}

	/**
	 * Returns a Guid object that has all its bits set to zero.
	 *
	 * @return Zend_Guid a nil Guid.
	 * @static
	 */
	public static function emptyGuid()
	{
		if (!isset($this->emptyGuid)) {
			$this->emptyGuid = new Zend_Guid(array_pad(array(), 16, 0));
		}
		return $this->emptyGuid;
	}

	/**
	 * Returns a new, pseudo-randomly generated Guid object.
	 *
	 * @return Zend_Guid a new Guid object.
	 */
	public static function generateGuid()
	{
		$bytes = array();
		for ($i = 0; $i < 16; $i++) {
			if ($i == 6) { // Version field (version 4)
				$b = mt_rand(0, 15) | 64;
			} else if ($i == 8) { // Variant field (type 2)
				$b = mt_rand(0, 63) | 128;
			} else {
				$b = mt_rand(0, 255);
			}
			$bytes[] = $b;
		}
		return new Zend_Guid($bytes);
	}

	/**
	 * Parses a Guid object from the specified 32 character hexadecimal
	 * string. Returns a nil Guid (see Guid::nilGuid) if the string could
	 * not be parsed.
	 *
	 * @param string $str the string representation of a Guid.
	 * @return Zend_Guid a Guid object parsed from its string representation.
	 */
	public static function parseGuid($str)
	{
		$guid = null;
		$str = str_replace(array('{', '(', '-', ')', '}'), '', $str);
		if (strlen($str) == 32) {
			$bytes = array();
			for ($i = 1; $i <= 32; $i++) {
				$ch = $str{$i - 1};
				if (($ch < '0' || $ch > '9')
					  && ($ch < 'a' || $ch > 'f')
					  && ($ch < 'A' || $ch > 'F')) {
					break;
				}
				$n = hexdec($ch);
				if ($i % 2 != 0) {
					$b = $n;
				} else {
					$bytes[] = $b * 16 + $n;
				}
			}
			if (count($bytes) == 16) {
				$guid = new Zend_Guid($bytes);
			}
		}
		if (is_null($guid)) {
			throw new Zend_Exception('[Guid::parseGuid()] '
				. "Could not parse string '{$str}'");

			$guid =& Zend_Guid::emptyGuid();
		}
		return $guid;
	}

	/**
	 * Returns a string representation of this Guid object.
	 *
	 * @return string a string in the format
	 *  "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", where "x" is a
	 *  hexadecimal digit.
	 */
	public function toString()
	{
		$str = '';
		for ($i = 1; $i <= 16; $i++) {
			$b = $this->bytes[$i - 1];
			if ($b < 16) {
				$str .= '0';
			}
			$str .= dechex($b);
			if ($i == 4 || $i == 6 || $i == 8 || $i == 10) {
				$str .= '-';
			}
		}
		return $str;
	}

	/**
	 * Returns a string representation of this Guid object.
	 *
	 * @return string a string in the format
	 *  "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", where "x" is a
	 *  hexadecimal digit.
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * Returns a 16 element byte array containing the underlying value of
	 * this Guid object.
	 *
	 * @return array
	 */
	public function toByteArray()
	{
		return $this->bytes;
	}
}