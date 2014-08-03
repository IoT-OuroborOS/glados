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
