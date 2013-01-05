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
 */
class LTimer{
	var $nowtime;
	var $totaltime;
	var $pause;
	
	function LTimer()
	{
		$this->totaltime=0;
		$this->pause=false;
		
		$this->nowtime=array_sum(explode(' ',microtime()));
	}
	
	function pauseTimer()
	{
		$this->totaltime+=array_sum(explode(' ',microtime()))-$this->nowtime;	
		$this->pause=true;
	}
	
	function continueTimer()
	{
		$this->pause=false;
		$this->nowtime=array_sum(explode(' ',microtime()));
	}
	
	function getTT($nr_dec=5,$separator='.')
	{
		switch ($this->pause)
		{
			case false:
				$this->totaltime+=array_sum(explode(' ',microtime()))-$this->nowtime;
				break;
		}
		
		return number_format($this->totaltime,$nr_dec,$separator,'');
	}
	
	function getTTMS()
	{
		switch ($this->pause)
		{
			case false:
				$this->totaltime+=array_sum(explode(' ',microtime()))-$this->nowtime;
				break;
		}
		
		$time=$this->totaltime*1000;
		if ($time>1000){
			$time=$time/1000;
			return number_format($time,5,'.','').' sec';
		}
		else{
			return number_format($time,5,'.','').' ms';
		}
		
		
	}
}
?>