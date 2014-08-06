<?php
require_once 'inc/config.php';
require_once 'classes/DB.php';

if (isset($_POST['t'])) {

    $texttosay = trim($_POST["t"]);

    if (strlen($texttosay) > 0 && strlen($texttosay) <= 255) {

    	// call function to generate sound file
  		if(!$sound_file = textToSound($texttosay)) {
  			$error_message = "There was an error generating the sound file.";
  		} else { ?>

  			<!-- play the sound file using HTML5-->
  			<audio preload="auto" autoplay>
  				<!-- we could also add the controls -->
  				<source src='<?php echo $sound_file; ?>' type="audio/mpeg">
  				<!-- this is a fallback for older browsers -->
  				<embed src="<?php echo $sound_file; ?>" autostart=true loop=false>
  			</audio>

  		<?php
  		}

    }
} // end main program logic

function textToSound($texttosay) {
    //generate a unique text file to store speach string
	$textfile = uniqid();

	//put the speach string into a text file
	if (!file_put_contents(SOUNDS_DIR . $textfile, $texttosay)) {
		return false; // error in creating the text file
	}

	//generate file names for wav and mp3
	$wav = SOUNDS_DIR . $textfile . ".wav";
	$mp3 = SOUNDS_DIR . $textfile . ".mp3";

	// output and return from exec commands
	// return is non 0 if exec fails
	$exec_output = "";
	$exec_return = "";

	// generate wav
	exec("text2wave -o " . $wav . " " . SOUNDS_DIR . $textfile, $exec_output, $exec_return);

	// generate mp3 - smaller file size to send back to client
	exec("lame " . $wav . " " . $mp3, $exec_output, $exec_return);

	if ($exec_return != 0) {
		return false; // error in generating mp3 or wav files
	}

	// put the message into the database
	try {

		DB::getInstance()->query("INSERT INTO messages (`message`, `timestamp`) VALUES (?, ?)", array($texttosay, date('Y-m-d H:i:s')));
	
	}	catch (Exception $error) {
			return false; // database INSERT error
	}

  // delete all but the last 10 records
  try {
  	
    DB::getInstance()->query("DELETE FROM `messages` WHERE id NOT IN (SELECT id FROM (SELECT id FROM `messages` ORDER BY id DESC LIMIT 10) foo )");
  
  }	catch (Exception $error) {
      return false; // database DELETE error
  }

  // delete textfile and wav file
  // we can't delete mp3 file as we need to send it to the client
  if(is_file(SOUNDS_DIR . $textfile)) {
    unlink(SOUNDS_DIR . $textfile);
  }

  if(is_file($wav)) {
    unlink($wav);
  }

	return $mp3;
}
?>
