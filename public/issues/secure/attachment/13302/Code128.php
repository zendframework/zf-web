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
 * @package    Zend_Barcode
 * @subpackage Object
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Code39.php 21658 2010-03-27 14:29:57Z mikaelkael $
 */

/**
 * @see Zend_Barcode_Object_ObjectAbstract
 */
require_once 'Zend/Barcode/Object/ObjectAbstract.php';

/**
 * @see 'Zend_Validate_Barcode'
 */
require_once 'Zend/Validate/Barcode.php';

/**
 * Class for generate Code128 barcode
 *
 * @category   Zend
 * @package    Zend_Barcode
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Barcode_Object_Code128 extends Zend_Barcode_Object_ObjectAbstract
{
  /**
    * Character sets ABC
    * @var array
    */
  protected static $CharSets = array(
    'A' => array(
      ' '=>0, '!'=>1, '"'=>2, '#'=>3, '$'=>4, '%'=>5, '&'=>6, "'"=>7,
      '('=>8, ')'=>9, '*'=>10, '+'=>11, ','=>12, '-'=>13, '.'=>14, '/'=>15,
      '0'=>16, '1'=>17, '2'=>18, '3'=>19, '4'=>20, '5'=>21, '6'=>22, '7'=>23,
      '8'=>24, '9'=>25, ':'=>26, ';'=>27, '<'=>28, '='=>29, '>'=>30, '?'=>31,
      '@'=>32, 'A'=>33, 'B'=>34, 'C'=>35, 'D'=>36, 'E'=>37, 'F'=>38, 'G'=>39,
      'H'=>40, 'I'=>41, 'J'=>42, 'K'=>43, 'L'=>44, 'M'=>45, 'N'=>46, 'O'=>47,
      'P'=>48, 'Q'=>49, 'R'=>50, 'S'=>51, 'T'=>52, 'U'=>53, 'V'=>54, 'W'=>55,
      'X'=>56, 'Y'=>57, 'Z'=>58, '['=>59, '\\'=>60, ']'=>61, '^'=>62, '_'=>63,
      0x00=>64, 0x01=>65, 0x02=>66, 0x03=>67, 0x04=>68, 0x05=>69, 0x06=>70, 0x07=>71,
      0x08=>72, 0x09=>73, 0x0A=>74, 0x0B=>75, 0x0C=>76, 0x0D=>77, 0x0E=>78, 0x0F=>79,
      0x10=>80, 0x11=>81, 0x12=>82, 0x13=>83, 0x14=>84, 0x15=>85, 0x16=>86, 0x17=>87,
      0x18=>88, 0x19=>89, 0x1A=>90, 0x1B=>91, 0x1C=>92, 0x1D=>93, 0x1E=>94, 0x1F=>95,
      'FNC3'=>96, 'FNC2'=>97, 'SHIFT'=>98, 'Code C'=>99, 'Code B'=>100, 'FNC4'=>101, 'FNC1'=>102, 'START A'=>103,
      'START B'=>104, 'START C'=>105, 'STOP'=>106), 
    'B' => array(
      ' '=>0, '!'=>1, '"'=>2, '#'=>3, '$'=>4, '%'=>5, '&'=>6, "'"=>7,
      '('=>8, ')'=>9, '*'=>10, '+'=>11, ','=>12, '-'=>13, '.'=>14, '/'=>15,
      '0'=>16, '1'=>17, '2'=>18, '3'=>19, '4'=>20, '5'=>21, '6'=>22, '7'=>23,
      '8'=>24, '9'=>25, ':'=>26, ';'=>27, '<'=>28, '='=>29, '>'=>30, '?'=>31,
      '@'=>32, 'A'=>33, 'B'=>34, 'C'=>35, 'D'=>36, 'E'=>37, 'F'=>38, 'G'=>39,
      'H'=>40, 'I'=>41, 'J'=>42, 'K'=>43, 'L'=>44, 'M'=>45, 'N'=>46, 'O'=>47,
      'P'=>48, 'Q'=>49, 'R'=>50, 'S'=>51, 'T'=>52, 'U'=>53, 'V'=>54, 'W'=>55,
      'X'=>56, 'Y'=>57, 'Z'=>58, '['=>59, '\\'=>60, ']'=>61, '^'=>62, '_'=>63,
      '`' =>64, 'a'=>65, 'b'=>66, 'c'=>67, 'd'=>68, 'e'=>69, 'f'=>70, 'g'=>71,
      'h'=>72, 'i'=>73, 'j'=>74, 'k'=>75, 'l'=>76, 'm'=>77, 'n'=>78, 'o'=>79,
      'p'=>80, 'q'=>81, 'r'=>82, 's'=>83, 't'=>84, 'u'=>85, 'v'=>86, 'w'=>87,
      'x'=>88, 'y'=>89, 'z'=>90, '{'=>91, '|'=>92, '}'=>93, '~'=>94, 0x7F=>95,
      'FNC3'=>96, 'FNC2'=>97, 'SHIFT'=>98, 'Code C'=>99, 'FNC4'=>100, 'Code A'=>101, 'FNC1'=>102, 'START A'=>103,
      'START B'=>104, 'START C'=>105, 'STOP'=>106,),
    'C' => array(
      '00'=>0, '01'=>1, '02'=>2, '03'=>3, '04'=>4, '05'=>5, '06'=>6, '07'=>7, '08'=>8, '09'=>9, 
      '10'=>10, '11'=>11, '12'=>12, '13'=>13, '14'=>14, '15'=>15, '16'=>16, '17'=>17, '18'=>18, '19'=>19, 
      '20'=>20, '21'=>21, '22'=>22, '23'=>23, '24'=>24, '25'=>25, '26'=>26, '27'=>27, '28'=>28, '29'=>29, 
      '30'=>30, '31'=>31, '32'=>32, '33'=>33, '34'=>34, '35'=>35, '36'=>36, '37'=>37, '38'=>38, '39'=>39,
      '40'=>40, '41'=>41, '42'=>42, '43'=>43, '44'=>44, '45'=>45, '46'=>46, '47'=>47, '48'=>48, '49'=>49, 
      '50'=>50, '51'=>51, '52'=>52, '53'=>53, '54'=>54, '55'=>55, '56'=>56, '57'=>57, '58'=>58, '59'=>59,
      '60'=>60, '61'=>61, '62'=>62, '63'=>63, '64'=>64, '65'=>65, '66'=>66, '67'=>67, '68'=>68, '69'=>69, 
      '70'=>70, '71'=>71, '72'=>72, '73'=>73, '74'=>74, '75'=>75, '76'=>76, '77'=>77, '78'=>78, '79'=>79,
      '80'=>80, '81'=>81, '82'=>82, '83'=>83, '84'=>84, '85'=>85, '86'=>86, '87'=>87, '88'=>88, '89'=>89, 
      '90'=>90, '91'=>91, '92'=>92, '93'=>93, '94'=>94, '95'=>95, '96'=>96, '97'=>97, '98'=>98, '99'=>99, 
      'Code B'=>100, 'Code A'=>101, 'FNC1'=>102, 'START A'=>103, 'START B'=>104, 'START C'=>105, 'STOP'=>106));

    /**
     * Code patterns for a given character
     * @var array
     */
    protected static $CodePattern = array(
      '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213',
      '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132',
      '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211',
      '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313',
      '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331',
      '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111',
      '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214',
      '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111',
      '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141',
      '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141',
      '114131', '311141', '411131', '211412', '211214', '211232', '2331112');

    /**
     * Partial check of Code128 barcode
     * @return void
     */
    protected function _checkParams()
    {
        $this->_checkRatio();
    }

    /**
     * Checks if the next $length chars of $arr starting at $pos are numeric. Returns
     * false if the end of the array is reached.
     * @param $arr Array to search
     * @param $pos Starting position
     * @param $length Length to search
     * @return bool
     */
    protected static function _isDigit($string, $pos, $length = 2) {
      return $pos + $length <= strlen($string) && is_numeric(substr($string, $pos, $length));
    }

    /**
     * Width of the barcode (in pixels)
     * @return int
     */
    protected function _calculateBarcodeWidth()
    {
	$quietZone = $this->getQuietZone();
	$barWidth = $this->_barThinWidth * $this->_factor;
	$characterLength = 11 * $barWidth;
	$encodedData     = count($this->_convertToBarcodeChars($this->getText())) * $characterLength + 2 * $barWidth;
	$width = $quietZone + $encodedData + $quietZone;
	return $width;
    }

    /**
     * Convert string to barcode string
     * @return array
     */
    protected function _convertToBarcodeChars($string)
    {
      $current_charset = null;
      $sum = 0;
      $fak = 0;
      $result = array();

      for ($pos = 0; $pos < strlen($string); $pos++) {
	$char = $string[$pos];
	$code = null;

	if (self::_isDigit($string, $pos, 4) && $current_charset != 'C'
	|| self::_isDigit($string, $pos, 2) && $current_charset == 'C') {
	  /**
	    * Switch to C if the next 4 chars are numeric or stay C if the next 2
	    * chars are numeric
	    */
	  if ($current_charset != 'C') {
	    if ($pos == 0) {
	      $code = self::$CharSets['C']["START C"];
	      $sum = $code;
	    } else {
	      $code = self::$CharSets[$current_charset]["Code C"];
	      $sum += $fak * $code;
	    }
	    $result[] = $code;
	    $current_charset = 'C';
	    $fak++;
	  }
	} else if (array_key_exists($char, self::$CharSets['B']) && $current_charset != 'B'
	&& !(array_key_exists($char, self::$CharSets['A']) && $current_charset == 'A')) {
	  /**
	    * Switch to B as B contains the char and B is not the current charset.
	    */
	  if ($pos == 0) {
	    $code = self::$CharSets['B']["START B"];
	    $sum = $code;
	  } else {
	    $code = self::$CharSets[$current_charset]["Code B"];
	    $sum += $fak * $code;
	  }
	  $result[] = $code;
	  $current_charset = 'B';
	  $fak++;
	} else if (array_key_exists($char, self::$CharSets['A']) && $current_charset != 'A'
	&& !(array_key_exists($char, self::$CharSets['B']) && $current_charset == 'B')) {
	  /**
	    * Switch to C as C contains the char and C is not the current charset.
	    */
	  if ($pos == 0) {
	    $code = self::$CharSets['A']["START A"];
	    $sum =  $code;
	  } else {
	    $code = self::$CharSets[$current_charset]["Code A"];
	    $sum += $fak * $code;
	  }
	  $result[] = $code;
	  $current_charset = 'A';
	  $fak++;
	}

	if ($current_charset == 'C') {
	  $code = self::$CharSets['C'][substr($string, $pos, 2)];
	  $pos++; //Two chars from input
	} else {
	  $code = self::$CharSets[$current_charset][$string[$pos]];
	}
	$result[] = $code;
	$sum += $fak * $code;
	$fak++;
      }

      $checksum = $sum % 103;

      $result[] = $checksum;
      $result[] = self::$CharSets[$current_charset]["STOP"];
      return $result;
    }

    /**
     * Prepare array to draw barcode
     * @return array
     */
    protected function _prepareBarcode()
    {
      $barcodeTable = array();
      $width = $this->_barThinWidth;
      $visible = true;

      foreach ($this->_convertToBarcodeChars($this->getText()) as $barcodeChar) {
	  $barcodePattern = self::$CodePattern[$barcodeChar];
	  foreach (str_split($barcodePattern) as $c) {
	      $barcodeTable[] = array((int) $visible, $c, 0, 1);
	      $visible = !$visible;
	  }
      }
      return $barcodeTable;
    }
}
