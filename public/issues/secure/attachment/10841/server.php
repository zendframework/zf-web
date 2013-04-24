<?php

class Server
{
   /**
   * Echo simple command.
   * Takes a string as input and echoes it.
   *
   * @param string $value The string to send back to client
   * @return string
   */

   public function parrot($value) {
      return $value;
   }
}

require_once ('Zend/Rest/Server.php');

$server = new Zend_Rest_Server();
$server->setClass('Server');
$server->handle();
