GLADOS
======

#### [Live Demo](http://simplepeacock.com/project/glados/) 

(Genetic Lifeform and Disk Operating System) is a fictional artificially intelligent computer system and the main antagonist in the game Portal. She was created by Erik Wolpaw and Kim Swift and is voiced by Ellen McLain. She is responsible for testing and maintenance in Aperture Science research facility in both video games.

GLaDOS uses "Festival" - a command line speech API developed by The Centre for Speech Technology Reasearch - University of Edinburgh. The server creates a text file with the speech text which is sent to a command line utility to be converted to a wave sound file. This is then converted to a much smaller mp3 file using the "lame" command line utility which is then sent to the client's browser.

The voice used is cmu_us_slt_arctic by Carnegie Mellon University.

#### Features
* jQuery AJAX call to submit text to the server
* FIFO type database usage - only up to 10 messages are stored in the database
* HTML5 `<audio>` to play sound in browsers with an `<embed>` fallback for older browsers

The project has no meaningful use apart from being a fun project.
