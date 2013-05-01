<?php
require_once 'Zend/Controller/Action.php';
require_once 'Zend/Mime.php';

class MimeController extends Zend_Controller_Action
{
	protected $_charset = 'UTF-8';
	protected $_len = 72;
	protected $_feed = "\r\n";

    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender();

        //$value = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩъыьЭЮЯ';
        $value = 'いろはにほへとちりぬるをわかよたれそつねならむ';


        echo "<pre>B-1. Zend_Mime::encodeBase64Header:<br>\r\n";
        echo Zend_Mime::encodeBase64Header($value, $this->_charset, $this->_len, $this->_feed);
        echo "\r\n<br><br>";

        echo "Q-1. Zend_Mime::encodeQuotedPrintableHeader:<br>\r\n";
        echo Zend_Mime::encodeQuotedPrintableHeader($value, $this->_charset, $this->_len, $this->_feed);
        echo "\r\n<br><br>";

        mb_internal_encoding($this->_charset);

        echo "B-2. Base64 by mb_encode_mimeheader:<br>\r\n";
        echo mb_encode_mimeheader($value, $this->_charset, 'B', $this->_feed, $this->_len);
        echo "\r\n<br><br>";

        echo "Q-2. QuotedPrintable by mb_encode_mimeheader:<br>\r\n";
        echo mb_encode_mimeheader($value, $this->_charset, 'Q', $this->_feed, $this->_len);

        echo "</pre>\r\n";

    }

}
?>

