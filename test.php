<?php

require_once 'inc/config.php';
require_once 'classes/DB.php';

$messages = DB::getInstance()->query("SELECT message FROM messages ORDER BY id DESC LIMIT 10");

//$messages = DB::getInstance()->query("SELECT * FROM messages ORDER BY id");

if(!$messages->count()) {
	echo "no results";
}

foreach($messages->results() as $message) {
	echo $message->message, '<br>';
}

DB::getInstance()->query("INSERT INTO messages (`message`) VALUES (?)", array("new message 123"));

echo "inserting complete";
