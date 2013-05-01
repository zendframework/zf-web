<?php
if (!extension_loaded('pdo_sqlite')) {
    echo "PDO_SQLite extension is not loaded";
	exit;
}

// Table structure for table `queue`
$queue = '
CREATE TABLE queue
(
  queue_id INTEGER PRIMARY KEY AUTOINCREMENT,
  queue_name VARCHAR(100) NOT NULL,
  timeout INTEGER NOT NULL DEFAULT 30
)';


// Table structure for table `message`
$message = '
CREATE TABLE message
(
  message_id INTEGER PRIMARY KEY AUTOINCREMENT,
  queue_id INTEGER,
  handle CHAR(32),
  body VARCHAR(8192) NOT NULL,
  md5 CHAR(32) NOT NULL,
  timeout REAL,
  created INTEGER,
  FOREIGN KEY (queue_id) REFERENCES queue(queue_id)
)';


$db = new PDO('sqlite:queue.db');

$db->query($queue);
$db->query($message);