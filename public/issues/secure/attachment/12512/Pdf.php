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
 * @package    Zend_Search_Lucene
 * @subpackage Document
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */


/** Zend_Search_Lucene_Document */
require_once 'Zend/Search/Lucene/Document.php';
/** Zend_Pdf */
require_once 'Zend/Pdf.php';

/**
 * PDF document.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Document
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Search_Lucene_Document_Pdf extends Zend_Search_Lucene_Document{

    /**
     * File charset text encoding
     *
     * @var String
     */
    protected $_fileEncoding = 'CP1252';

    /**
     * Charset to be used internally
     *
     * @var String
     */
    protected $_internalEncoding = 'UTF-8';

    /**
     * Object constructor
     *
     * @param string  $fileName
     * @param boolean $storeContent
     * @param string  $fileEncoding
     * @param string  $internalEncoding
     * @throws Zend_Search_Lucene_Exception
     */
    private function __construct($fileName, $storeContent, $fileEncoding = null, $internalEncoding = null) {
        if (!is_null($fileEncoding)){
            $this->_fileEncoding = $fileEncoding;
        }
        if (!is_null($internalEncoding)){
            $this->_internalEncoding = $internalEncoding;
        }
        // Load the PDF document.
        $pdf = Zend_Pdf::load($fileName);

        // Data holders
        $coreProperties = array('Key' => md5_file($fileName));

        // Go through each meta data item and add to meta data properties
        foreach ($pdf->properties as $meta => $metaValue) {
            switch ($meta) {
                case 'Title':
                    if (trim($pdf->properties['Title']) != '') $coreProperties['title'] = $pdf->properties['Title'];
                    break;
                case 'Subject':
                    if (trim($pdf->properties['Subject']) != '') $coreProperties['subject'] = $pdf->properties['Subject'];
                    break;
                case 'Author':
                    if (trim($pdf->properties['Author']) != '') $coreProperties['author'] = $pdf->properties['Author'];
                    break;
                case 'Keywords':
                    if (trim($pdf->properties['Keywords']) != '') $coreProperties['keywords'] = $pdf->properties['Keywords'];
                    break;
                case 'CreationDate':
                    $dateCreated = $pdf->properties['CreationDate'];

                    $distance = substr($dateCreated, 16, 2);
                    if (!is_long($distance)) {
                        $distance = null;
                    }
                    // Convert date from the PDF format of D:20090731160351+01'00'
                    // @todo this should probably be a function in Zend_PDF to convert from this format
                    $dateCreated = mktime(substr($dateCreated, 10, 2), //hour
                        substr($dateCreated, 12, 2), //minute
                        substr($dateCreated, 14, 2), //second
                        substr($dateCreated,  6, 2), //month
                        substr($dateCreated,  8, 2), //day
                        substr($dateCreated,  2, 4), //year
                        $distance); //distance
                    $coreProperties['CreationDate'] = date('Ymd', $dateCreated);
                    break;
                case 'Date':
                    $coreProperties['date'] = $pdf->properties['Date'];
                    break;
            }
        }

        // Read core properties
        $documentBody = $this->pdf2txt($pdf->render());

        // Store filename
        $this->addField(Zend_Search_Lucene_Field::Text('filename', basename($fileName), $this->_internalEncoding));

        // Store contents
        if ($storeContent) {
            $this->addField(Zend_Search_Lucene_Field::Text('body', $documentBody, $this->_internalEncoding));
        } else {
            $this->addField(Zend_Search_Lucene_Field::UnStored('body', $documentBody, $this->_internalEncoding));
        }

        // Store meta data properties
        foreach ($coreProperties as $key => $value) {
            if ($value) $this->addField(Zend_Search_Lucene_Field::Text($key, $value, $this->_internalEncoding));
        }

        // Store title (if not present in meta data)
        if (!isset($coreProperties['title'])) {
            $this->addField(Zend_Search_Lucene_Field::Text('title', basename($fileName), $this->_internalEncoding));
        }
    }

    /**
     * Convert a PDF into text.
	 * ref: http://community.livejournal.com/php/295413.html
     *
     * @param string $data The binary data to extract the data from.
     * @return string The extracted text from the PDF
     */
    protected function pdf2txt($data){
        /**
         * Split apart the PDF document into sections. We will address each
         * section separately.
         */
        $a_obj = $this->getDataArray($data, "obj", "endobj");
        $j     = 0;

        /**
         * Attempt to extract each part of the PDF document into a "filter"
         * element and a "data" element. This can then be used to decode the
         * data.
         */
        foreach ($a_obj as $obj) {
            $a_filter = $this->getDataArray($obj, "<<", ">>");
            if (is_array($a_filter) && isset($a_filter[0])) {
                $a_chunks[$j]["filter"] = $a_filter[0];
                $a_data = $this->getDataArray($obj, "stream", "endstream");
                if (is_array($a_data) && isset($a_data[0])) {
                    $a_chunks[$j]["data"] = trim(substr($a_data[0], strlen("stream"), 
                        strlen($a_data[0]) - strlen("stream") - strlen("endstream")));
                }
                $j++;
            }
        }

        $result_data = NULL;

        // Decode the chunks
        foreach ($a_chunks as $chunk) {
            // Look at each chunk decide if we can decode it by looking at the contents of the filter
            if (isset($chunk["data"])) {
                // Look at the filter to find out which encoding has been used
                if (substr($chunk["filter"], "FlateDecode") !== false) {
                    // Use gzuncompress but supress error messages.
                    $data = @gzuncompress($chunk["data"]);
                    // If we got data then attempt to extract it.
                    if (trim($data) != "") {
                        // Specially for ccentuated characters ... per example \343 = &atilde; but also valid for other
                        $data = preg_replace('~\\\\([0-9]{3})~ei', 'chr(octdec("\\1"))', $data);
                        // Convert encoding. Surpress warnings and remove invalid characters
                        if ($this->_fileEncoding != $this->_internalEncoding){
                            $data = @iconv($this->_fileEncoding, $this->_internalEncoding.'//TRANSLIT//IGNORE', $data);
                        }
                        // Convert accentuated to non-accentuated characters
                        // @todo latin alphabets only, no greek, chinese, russian ...
                        $data = $this->_clearString($data);
                        if (trim($data) != "") $result_data .= ' ' . $this->ps2txt($data);
                    }
                }
            }
        }

        // Clean up
        // @todo this is only valid for latin alphabets, no greek, chinese, russian ...
        $result_data = trim(preg_replace('/([^a-z0-9 ])/i', ' ', $result_data));
        $result_data = preg_replace('/\s\s+/', ' ', $result_data);

        // Return the data extracted from the document.
        if ($result_data == "") {
            return NULL;
        } else {
            return $result_data;
        }
    }

    /**
     * Add special characters used in CP1252
     * ref: http://php.net/manual/en/function.get-html-translation-table.php#76564
     *
     * @return array
     */
    protected function _get_html_translation_table_CP1252() {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans[chr(130)] = '&sbquo;';  // Single Low-9 Quotation Mark
        $trans[chr(131)] = '&fnof;';   // Latin Small Letter F With Hook
        $trans[chr(132)] = '&bdquo;';  // Double Low-9 Quotation Mark
        $trans[chr(133)] = '&hellip;'; // Horizontal Ellipsis
        $trans[chr(134)] = '&dagger;'; // Dagger
        $trans[chr(135)] = '&Dagger;'; // Double Dagger
        $trans[chr(136)] = '&circ;';   // Modifier Letter Circumflex Accent
        $trans[chr(137)] = '&permil;'; // Per Mille Sign
        $trans[chr(138)] = '&Scaron;'; // Latin Capital Letter S With Caron
        $trans[chr(139)] = '&lsaquo;'; // Single Left-Pointing Angle Quotation Mark
        $trans[chr(140)] = '&OElig;';  // Latin Capital Ligature OE
        $trans[chr(145)] = '&lsquo;';  // Left Single Quotation Mark
        $trans[chr(146)] = '&rsquo;';  // Right Single Quotation Mark
        $trans[chr(147)] = '&ldquo;';  // Left Double Quotation Mark
        $trans[chr(148)] = '&rdquo;';  // Right Double Quotation Mark
        $trans[chr(149)] = '&bull;';   // Bullet
        $trans[chr(150)] = '&ndash;';  // En Dash
        $trans[chr(151)] = '&mdash;';  // Em Dash
        $trans[chr(152)] = '&tilde;';  // Small Tilde
        $trans[chr(153)] = '&trade;';  // Trade Mark Sign
        $trans[chr(154)] = '&scaron;'; // Latin Small Letter S With Caron
        $trans[chr(155)] = '&rsaquo;'; // Single Right-Pointing Angle Quotation Mark
        $trans[chr(156)] = '&oelig;';  // Latin Small Ligature OE
        $trans[chr(159)] = '&Yuml;';   // Latin Capital Letter Y With Diaeresis
        ksort($trans);
        return $trans;
    }

    /**
     * Remove accented characters
     *
     * @param String $sString
     * @return String
     */
    protected function _clearString($sString) {
        if (in_array(strtolower($this->_internalEncoding), array('utf-8', 'utf8'))){
            $sString = utf8_decode($sString);
        }
        $string = strtr($sString, $this->_get_html_translation_table_CP1252());
        $string = preg_replace("/\&(.)[^;]*;/", "\\1", $string);
        return $string;
    }

    /**
     * Convert a small chunk of data into text.
     *
     * @param  string $ps_data The chunk of data to convert.
     * @return string          The string extracted from the data.
     */
    protected function ps2txt($ps_data){
        // Stop this function returning bogus information from non-data string.
        if (ord($ps_data[0]) < 10) {
            return $ps_data;
        }
        if (substr($ps_data, 0, 8) == '/CIDInit') {
            return '';
        }

        $result = "";

        $a_data = $this->getDataArray($ps_data, "[", "]");

        // Extract the data.
        if (is_array($a_data)) {
            foreach ($a_data as $ps_text) {
                $a_text = $this->getDataArray($ps_text, "(", ")");
                if (is_array($a_text)) {
                    foreach ($a_text as $text) {
                        $result .= substr($text, 1, strlen($text) - 2);
                    }
                }
            }
        }

        // Didn't catch anything, try a different way of extracting the data
        if (trim($result) == "") {
            // the data may just be in raw format (outside of [] tags)
            $a_text = $this->getDataArray($ps_data, "(", ")");
            if (is_array($a_text)) {
                foreach ($a_text as $text) {
                    $result .= substr($text, 1, strlen($text) - 2);
                }
            }
        }

        return trim($result);
    }

    /**
     * Convert a section of data into an array, separated by the start and end words.
     *
     * @param  string $data       The data.
     * @param  string $start_word The start of each section of data.
     * @param  string $end_word   The end of each section of data.
     * @return array              The array of data.
     */
    protected function getDataArray($data, $start_word, $end_word)
    {
        $start    = 0;
        $end      = 0;
        $a_result = array();

        while ($start !== false && $end !== false) {
            $start = strpos($data, $start_word, $end);
            $end   = strpos($data, $end_word, $start);
            if ($end !== false && $start !== false) {
                // data is between start and end
                $a_result[] = substr($data, $start, $end - $start + strlen($end_word));
            }
        }

        return $a_result;
    }

    /**
     * Load Pdf document from a file
     *
     * @param string  $fileName
     * @param boolean $storeContent
     * @param string  $fileEncoding
     * @param string  $internalEncoding
     * @return Zend_Search_Lucene_Document_Pdf
     * @throws Zend_Search_Lucene_Document_Exception
     */
    public static function loadPdfFile($fileName, $storeContent = false, $fileEncoding = null, $internalEncoding = null) {
        if (!is_readable($fileName)) {
            require_once 'Zend/Search/Lucene/Document/Exception.php';
            throw new Zend_Search_Lucene_Document_Exception('Provided file \'' . $fileName . '\' is not readable.');
        }

        return new Zend_Search_Lucene_Document_Pdf($fileName, $storeContent, $fileEncoding, $internalEncoding);
    }
}