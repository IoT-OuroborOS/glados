<?php
require_once 'inc/config.php';

foreach (glob(SOUNDS_DIR . "/*") as $filename) {
    if(is_file($filename)) {
    	echo "Deleting: " . $filename . '<br>';
    	unlink($filename);
    }
}
?>
