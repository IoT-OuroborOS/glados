<?php

/* TODO

- escaping input
- cleaning up files
- file security
- add timestamp in database?
- store only 10 in database - "FIFO que"
- validation on the client side with jQuery
- abstract file manipulation in a seperate class
- footer with link to home
- add a fallback for before IE9 which don't have HTML5 audio tag support
- return errors from process

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
    <link href="css/my-styles.css" rel="stylesheet" media="screen">
</head>
<body>
<div class="container">
	<h1>GLaDOS</h1>
	<div class="row">
		<div class="col-md-6"><!--  1st column -->
			<p>(Genetic Lifeform and Disk Operating System) is a fictional artificially intelligent computer system and the main antagonist in the game Portal. She was created by Erik Wolpaw and Kim Swift and is voiced by Ellen McLain. She is responsible for testing and maintenance in Aperture Science research facility in both video games.</p>
			<h3>How it works</h3>
		</div><!-- ./1st column -->

		<div class="col-md-6">
			<div class="theform">
				<form id="tosay" name="tosay" role="form" method="POST" action="">
				  	<div class="form-group">
				    	<label for="t">Text (max 255 characters)</label>
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
							<p><?php echo htmlspecialchars($message->message); ?></p>
					    </li>
					<?php } ?>
				</ul>
			</div>
		</div><!-- ./2nd column -->
	</div><!-- ./row	 -->
</div><!-- ./container -->

<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="bs/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$("#submit").click(function(event) {
			event.preventDefault();

			// clear errors

			var texttosay = $("input[name='t']").val().trim();

			// check for spaces only
			if(texttosay.length <= 255 && texttosay.length > 0) {

				$.post('process.php', {t:texttosay},
					function(data) {
						$(".messages").append(data);
				});

        $('#texttosay').val('');

			} else {
				$(".errors").html('<p class="alert alert-danger">Invalid string length.</p>');
			}

			// clear text field

		});
	});
</script>

</body>
</html>
