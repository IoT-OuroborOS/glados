<?php
require_once 'inc/config.php';
require_once 'classes/DB.php';
$stored_messages_obj = DB::getInstance()->query("SELECT message FROM messages ORDER BY id DESC LIMIT 10");
?>
<ul>
  <h3>Last 10 messages:</h3>
  <?php
    foreach($stored_messages_obj->results() as $message) { ?>
    <li>
      <p><?php echo htmlspecialchars($message->message); ?></p>
      </li>
  <?php } ?>
</ul>
