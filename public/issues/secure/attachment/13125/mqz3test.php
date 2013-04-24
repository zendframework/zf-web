<?
date_default_timezone_set('Asia/Tokyo');

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Queue_Stomp_Client_Connection');

class MyStompClient extends Zend_Queue_Stomp_Client_Connection
{
	public function read()
	{
        $this->ping();

        $response = '';

        $proc_header = true;
        while ($proc_header)
        {
            $line = fgets($this->_socket);
            if ($line === "\n")
            {
            	$proc_header = false;
            }
            $header = explode(':', $line);
            if (trim($header[0]) === 'content-length')
            {
            	$body_size = intval($header[1]);
            }
            $response .= $line;
        }

        if (isset($body_size))
        {
        	$data = fread($this->_socket, $body_size);
            // check to make sure that the connection is not lost.
            if ($data === false) {
                require_once 'Zend/Queue/Exception.php';
                throw new Zend_Queue_Exception('Connection lost');
            }

            // append last character read to $response
            $response .= $data;

        	# eat the trailing null
        	fread($this->_socket, 1);
        }
        else
        {
            while (1)
            {
                $data = fread($this->_socket, 1);

                // check to make sure that the connection is not lost.
                if ($data === false) {
                    require_once 'Zend/Queue/Exception.php';
                    throw new Zend_Queue_Exception('Connection lost');
                }

                if (ord($data) == 0) {
            	    break;
                }

                // append last character read to $response
                $response .= $data;
            }
        }

        if ($response === '') {
            return false;
        }

        $frame = $this->createFrame();
        $frame->fromFrame($response);
        return $frame;
	}
}


$TIMEOUT_SEC = 10;
$SEND_ACK = true;

$conn = new MyStompClient();
$conn->open('tcp', '10.32.10.55', 61613, array('timeout_sec' => $TIMEOUT_SEC));

$frame = $conn->createFrame();
$frame->setCommand('CONNECT');
$frame->setHeader('login', 'guest');
$frame->setHeader('passcode', 'guest');
$conn->write($frame);

$frame = $conn->read();

$frame = $conn->createFrame();
$frame->setCommand('SUBSCRIBE');
$frame->setHeader('destination', 'myQueue');
if ($SEND_ACK)
{
	$frame->setHeader('ack', 'client');
}
$conn->write($frame);

while (1)
{
	echo "#\n#\n#\n#\n#\n";
	echo "wait (max=){$TIMEOUT_SEC} sec ...\n";
	$canRead = $conn->canRead();
	echo "done.\n";

	if (! $canRead)
	{
		echo "no message and break.\n";
		break;
	}
	$frame = $conn->read();
	if ($frame->getCommand() !== 'MESSAGE')
	{
		continue;
	}
	
	echo print_r($frame, true);
	$message_id = $frame->getHeader('message-id');

	if ($SEND_ACK)
	{
		$frame = $conn->createFrame();
		$frame->setCommand('ACK');
		$frame->setHeader('message-id', $message_id);
		$conn->write($frame);
	}
}

$conn->close();
