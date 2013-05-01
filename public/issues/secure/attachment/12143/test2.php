<?php
require_once 'Zend/Session/SaveHandler/Interface.php';
require_once 'Zend/Session.php';

class SaveHandler
implements Zend_Session_SaveHandler_Interface
{
function open($save_path, $session_name)
{
  return(true);
}

function close()
{
  return(true);
}

function read($id)
{
  return '';
}

function write($id, $sess_data)
{
  throw new Exception("test exception within session save handler");
  return(true);
}

function destroy($id)
{
  return(true);
}

function gc($maxlifetime)
{
  return(true);
}
}
$savehandler = new SaveHandler();
Zend_Session::setSaveHandler($savehandler);
Zend_Session::start();
