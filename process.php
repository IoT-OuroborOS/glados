<?php
require_once 'inc/config.php';
require_once 'classes/DB.php';

// main program logic
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $texttosay = trim($_POST["t"]);

    if ($texttosay != "") {

    	if (strlen($texttosay) <= 255) {

	    $tempfilename = uniqid();

			$file = fopen($tempfilename,"x");

			fwrite($file, $texttosay);
			fclose($file);

			$outwav = $tempfilename . ".wav";
			$outmp3 = $tempfilename . ".mp3";

			$command = "text2wave -o " . $outwav . " " . $tempfilename;
			exec($command);
			exec("lame " . $outwav . " " . $outmp3);

			try {
				DB::getInstance()->query("INSERT INTO messages (`message`) VALUES (?)", array($texttosay));

			}	catch (Exception $error) {
					echo "The query could not be completed: " . $error;
			}

	    } else {
	    	$error_message = "Error: This text string is too long. GLaDOS will only accept 255 characters or less";
	    }

    } else {
    		$error_message = "Error: Please enter some text to say.";
    }

} // end main program logic
?>
<?php 
	if(isset($_POST["t"]) && !isset($error_message)) { ?>

	<audio controls autoplay style="display:none;">
		<source src="<?php echo $outmp3; ?>" type="audio/mpeg">
	</audio>

<?php } ?>