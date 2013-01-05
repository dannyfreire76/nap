<?php
/**
 * About author:
 * Radu T.
 * email: eagle[not]bv[not]ro[[not][isat][not]]yahoo[[not][isdot][not]]com
 * 
 * About class:
 * LTimer class for page loading timer
 * 	-pauseTimer() - stops timer at a certain time
 *	-continueTimer() - continue timer from where pauseTimer stopped it
 * 	-getTT & getTTMS for returning the total time of loading
 * 
 * Example for LTimer class
 */


include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();


echo 'some html output code here';
echo 'some html output code here';

$timer->pauseTimer(); //the timer stops here

echo 'some html output code here
this html code is outputed without counting the time for being displayed
some html output code here';

$timer->continueTimer(); //continue timer from where pauseTimer stopped it

echo 'some html output code here
some html output code here
some more code....';

echo 'Total time for loading: '.$timer->getTTMS(); //this stops the timer and returns the total  time for loading the code above, in ms or sec if the result is bigger then 1000ms, which is 1 sec 
?>
