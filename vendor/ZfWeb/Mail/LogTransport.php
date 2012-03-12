<?php
class ZfWeb_Mail_LogTransport extends Zend_Mail_Transport_Abstract
{
    /**
     * Log mail messages
     * 
     * @return void
     */
    protected function _sendMail()
    {
        $config = Zend_Registry::get('config');
        $log = new Zend_Log(new Zend_Log_Writer_Stream($config->log->path . '/mail.log'));
        $message = "Message queued for delivery:\n\n"
                 . $this->header . "\n"
                 . $this->body;
        $log->info($message);
    }
}
