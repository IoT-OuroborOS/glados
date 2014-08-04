<?php

/* TODO

- escaping input
- cleaning up files
- file security
- add timestamp in database?
- store only 10 in database - "FIFO que"
- validation on the client side with jQuery
- abstract file manipulation in a seperate class

*/

require_once 'inc/config.php';
require_once 'classes/DB.php';

// read the database
$stored_messages_obj = DB::getInstance()->query("SELECT message FROM messages ORDER BY id DESC LIMIT 10");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  	<meta charset="utf-8" />
	<title>GLaDOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="bs/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bs/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link href="css/my-styles.css" rel="stylesheet" media="screen">
</head>
<body>

<div class="container">
	<h1>GLaDOS</h1>

	<p>(Genetic Lifeform and Disk Operating System) is a fictional artificially intelligent computer system and the main antagonist in the game Portal as well as the first half of its sequel, Portal 2. She was created by Erik Wolpaw and Kim Swift and is voiced by Ellen McLain. She is responsible for testing and maintenance in Aperture Science research facility in both video games. While she initially appears to simply be a voice to guide and aid the player, her words and actions become increasingly malicious until she makes her intentions clear. The game reveals that she is corrupted and used a neurotoxin to kill the scientists in the lab before the events of Portal. She is destroyed at the end of the first title by the player-character Chell but is revealed to have survived in the credits song "Still Alive".</p>

	<div class="theform">

		<form id="tosay" name="tosay" role="form" method="POST" action="">
		  	<div class="form-group">
		    	<!-- <label for="t">Text (max 255 characters)</label> -->
		    	<input type="text" name="t" class="form-control" id="texttosay" placeholder="Enter Text To Say (max 255 characters)">
		  	</div>

			<div class="errors">
			<?php // display error message if any
				if (isset($error_message)) {
					echo '<p class="alert alert-warning">' . $error_message . '</p>';
				}
			?>
		</div>
		  	<button type="submit" id="submit" class="btn btn-primary">Say It</button>
		</form>

	</div>

	<div class="messages">
	<ul>
		<h3>Last 10 messages:</h3>

		<?php
			foreach($stored_messages_obj->results() as $message) { ?>
			<li>
				<p><?php echo '"' . $message->message . '"'; ?></p>
		    </li>
		<?php } ?>
	</ul>
	</div>

</div>

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="bs/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$("#submit").click(function(event) {
			
			event.preventDefault();

			$.post('process.php', $('#tosay').serialize(),
				function(data) {
					$(".messages").append(data);	
			});	
		});						   
	});
</script>

</body>
</html>

<?php
	// Cleaning Up
	unlink($tempfilename);
	unlink($outwav);
	//unlink($outmp3);

	// Close database connection
	if(isset($db)) {
		mysql_close($db);
	}

	//build command to delete mp3
	//$delcmd = "./delscript.sh " . $outmp3 . " &";
	//exec($delcmd);
?>
