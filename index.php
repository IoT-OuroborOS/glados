<?php

/* TODO

- escaping input
- cleaning up files
- file security
- footer with link to home
- return errors from process.php via .post request

*/

require_once 'inc/config.php';
require_once 'classes/DB.php';

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

<!-- main container -->
<div class="container">
	
	<h1>GLaDOS</h1>

	<!-- 1st row -->
	<div class="row">
		
		<!--  1st column -->
		<div class="col-md-6">
			<p>(Genetic Lifeform and Disk Operating System) is a fictional artificially intelligent computer system and the main antagonist in the game Portal. She was created by Erik Wolpaw and Kim Swift and is voiced by Ellen McLain. She is responsible for testing and maintenance in Aperture Science research facility in both video games.</p>
		</div>
		<!--  ./1st column -->

		<!-- 2nd column -->
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
		</div>
		<!-- ./2nd column -->	
	</div>
	<!-- ./1st row -->

	<!-- 2nd row -->
	<div class="row">
		
		<!--  1st column -->
		<div class="col-md-6">
			<h3>How it works</h3>
			<p>GLaDOS uses "Festival" - a command line speech API developed by The Centre for Speech Technology Reasearch - University of Edinburgh. The server creates a text file with the speech text which is sent to a command line utility to be converted to a wave sound file. This is then converted to a much smaller mp3 file using the "lame" command line utility which is then sent to the client's browser. The voice used is cmu_us_slt_arctic by Carnegie Mellon University.</p>
		</div>
		<!--  ./1st column -->

		<!-- 2nd column -->
		<div class="col-md-6">
			<div class="messages" id="messages">
        		<?php include_once 'storedmessages.php' ?>
			</div>
		</div>
		<!-- ./2nd column -->
	</div>
	<!-- ./2nd row -->
	 
</div><!-- ./main container -->

<!-- footer -->
<div class="footer">
    <div class="container">
    	<ul>
		    <li><a href="http://simplepeacock.com/projects">Back to Projects</a></li>
		    <li><a href="http://simplepeacock.com">Home</a></li>
		    <li><a href="https://github.com/simple-peacock/glados">GLaDOS on GitHub</a></li>
		</ul>
		<p class="text-muted">&copy; Simple Peacock Web Solutions 2014</p>
    </div>
</div>
<!-- ./footer -->

<!-- JavaScript includes -->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="bs/js/bootstrap.min.js"></script>

<!-- AJAX post with jQuery -->
<script type="text/javascript">
	$(document).ready(function() {

		$("#submit").click(function(event) {
			event.preventDefault();

			// clear errors

			var texttosay = $("input[name='t']").val().trim();

			// check for spaces only
			if(texttosay.length <= 255 && texttosay.length > 0) {

	        //ajax request to the process.php file
	        //which returns sound file
				$.post('process.php', {t:texttosay},
					function(data) {
						$("#texttosay").append(data);

			            //clear the text input field
			            $('#texttosay').val('');

			            //fetch the stored messages and show updated list
			            //including message we just submitted
			            $.get('storedmessages.php', function(data) {
			              $('#messages').html(data);
			            });
				});

			} else {
				$(".errors").html('<p class="alert alert-danger">Invalid string length.</p>');
			}

			// clear text field

		});
	});
</script>

</body>
</html>
