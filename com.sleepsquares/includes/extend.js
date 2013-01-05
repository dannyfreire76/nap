//  extensions for jQuery and other common basic functions
function includeJS(jsPath){
	var js = document.createElement("script");
	js.setAttribute("type", "text/javascript");
	js.setAttribute("src", jsPath);
	document.getElementsByTagName("head")[0].appendChild(js);
}


// Utility functions for showing loading images
$.fn.loading_layer2 = function() {
	remove_loading_layer();
    $(this).append('<div id="loading_dialog" style="display:none"></div>');
    var dims = bgSize();
	if ( navigator.userAgent.indexOf("MSIE") != -1 ) {
		dims[0] -= 22;
		dims[1] -= 4;
	}	
    $('#loading_dialog').css('width', dims[0]).css('height', dims[1]);
	if ( navigator.userAgent.indexOf("MSIE") == -1 ) {
		$('#loading_dialog').fadeIn(200)
	}
	else {
		$('#loading_dialog').show();
	}
}

$.fn.loading_layer = function() {
	remove_loading_layer();
    $(this).append('<div id="loading_dialog"></div>');
    var dims = bgSize();
	if ( navigator.userAgent.indexOf("MSIE") != -1 ) {
		dims[0] -= 22;
		dims[1] -= 4;
	}	
    $('#loading_dialog').css('width', dims[0]).css('height', dims[1]);
}

remove_loading_layer = function() {
    $('#loading_dialog').remove();
}

$.fn.show_loading_bar = function() {
	var lb = "/images/loading_bar.gif";
	var img1 = new Image();
	img1.src = lb;
	$(this).append('&#160;&#160;<img src="'+lb+'" class="indicator" id="loading_bar" />');
    return this;
}

hide_loading_bar = function() {
	$('#loading_bar').remove();
    return this;
}

$.fn.loading_small_wait = function() {
	var lsw = "/images/loading_small_wait.gif";
	var img_lsw = new Image();
	img_lsw.src = lsw;

	$(this).html('<img src="/images/loading_small_wait.gif" class="indicator" />');
    return this;
}

$.fn.tiny_spinner = function() {
	$(this).html('<img src="/images/icons/iconLoadingTiny.gif" class="indicator" />');
    return this;
}

$.fn.small_spinner_on_purple = function() {
	$(this).html('<img src="/images/icons/smallIndicatorPurpleBG.gif" class="indicator" />');
    return this;
}

$.fn.spinner_on_purple = function() {
	$(this).html('<img src="/images/icons/iconIndicatorPurpleBG.gif" class="indicator" />');
    return this;
}

$.fn.small_spinner_on_bg = function() {
	$(this).html('<img src="/images/icons/iconIndicatorDkGreenBG.gif" class="indicator" />');
    return this;
}

$.fn.small_spinner = function() {
	$(this).html('<img src="/images/icons/iconIndicator.gif" class="indicator" />');
    return this;
}

$.fn.small_spinner_on_dkgreen = function() {
	$(this).html('<img src="/images/icons/iconIndicatorDkGreenBG.gif" class="indicator" />');
    return this;
}

$.fn.small_spinner_on_blue = function() {
	$(this).html('<img src="/images/icons/iconIndicatorBlueBG.gif" class="indicator" />');
    return this;
}
$.fn.spinner_overlay = function() {
	$(this).append('<div class="loading_overlay"><img src="/images/icons/iconIndicator.gif" class="indicator" />Loading... Please do not click the back button.</div>');
	return this;
}

$.fn.large_spinner = function() {
	$(this).html('<img src="/images/icons/iconLoadingAnimation.gif" class="indicator" />');
    return this;
}

$.fn.relative_spinner = function() {
	var spin_pos = findPos( $(this).get(0) );
	spin_pos[0] += $(this).width() / 2;
	spin_pos[1] += $(this).height() / 2;
	$('#rel_spin').remove();
	$('body').append('<img src="/images/icons/iconLoadingAnimation.gif" id="rel_spin" class="no_display absolute" />');
	spin_pos[1] -= 50;//change this if the image changes ( .height() does not work when image is not displayed)
	$('#rel_spin')
			.css('left', spin_pos[0]+'px')
			.css('top', spin_pos[1]+'px')
			.fadeIn(200);
    return this;
}

$.fn.remove_relative_spinner = function() {
	$('#rel_spin').fadeOut(200, function(){
		$('#rel_spin').remove();
	})
    return this;
}

$.fn.large_blue_spinner = function() {
	$(this).html('<img src="/images/icons/iconIndicatorBlue.gif" class="indicator" />');
    return this;
}

String.prototype.trim = function() {
a = this.replace(/^\s+/, '');
return a.replace(/\s+$/, '');
};

function findWindowCenter(widthCorrect, heightCorrect){ 
    if (window.innerHeight || window.innerWidth) {      
        yScroll = window.innerHeight/2 + window.scrollY;
        xScroll = window.innerWidth/2 + window.scrollX;
    } else if (document.documentElement.clientHeight || document.body.offsetWidth){ // all but Explorer Mac
        yScroll = document.documentElement.clientHeight/2 + document.documentElement.scrollTop;
		xScroll = document.body.offsetWidth/2
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        yScroll = document.body.offsetHeight/2;
        xScroll = document.body.offsetWidth/2;
    }
    yScroll += heightCorrect;
    xScroll += widthCorrect;
    return [xScroll, yScroll];
}

Array.prototype.has = function(value) {
	var i;
	for (var i = 0, loopCnt = this.length; i < loopCnt; i++) {
		if (this[i] == value) {
			return true;
		}
	}
	return false;
};

function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
			curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}

function bgSize(){
    if (window.innerHeight && window.scrollMaxY || window.innerWidth && window.scrollMaxX) {      
        yScroll = window.innerHeight + window.scrollMaxY;
        xScroll = window.innerWidth + window.scrollMaxX;
        var deff = document.documentElement;
        var wff = (deff&&deff.clientWidth) || document.body.clientWidth || window.innerWidth || self.innerWidth;
        xScroll -= (window.innerWidth - wff);        
        /*
        var hff = (deff&&deff.clientHeight) || document.body.clientHeight || window.innerHeight || self.innerHeight;
        yScroll -= (window.innerHeight - hff);
        */
    } else if (document.body.scrollHeight > document.body.offsetHeight || document.body.scrollWidth > document.body.offsetWidth){ // all but Explorer Mac
        yScroll = document.body.scrollHeight;
        if ( navigator.userAgent.indexOf("MSIE") != -1 ) {
            xScroll = document.body.offsetWidth;       
        }
        else {
            xScroll = document.body.scrollWidth;       
        }
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        yScroll = document.body.offsetHeight;
        xScroll = document.body.offsetWidth;
    }
    return [xScroll, yScroll];
}

function countLines(strtocount, cols) {
  var hard_lines = 0;
  var soft_lines = 0;
  var last = 0;
  while ( true ) {
    last = strtocount.indexOf("\n", last+1);
    hard_lines ++;

	if ( last == -1 ) break;
  }

	var softlines_arr = strtocount.split('\n');

	for(var i in softlines_arr) {
		soft_str=softlines_arr[i];
		soft_lines = soft_lines + ( Math.floor(soft_str.length / (cols-1)) );
	}

  if ( soft_lines>0 ) {
		hard_lines += soft_lines;
  }



  if ( hard_lines == 0) {
	  hard_lines = 1;
  }
  return hard_lines;
}

function textAreasExpandable() {
	$('textarea').keyup( function(){
		var num_lines = countLines( $('textarea').val(), $(this).attr('cols') );
		if ( num_lines > 1) {
			var new_height = 19 * num_lines;
		}
		else {
			var new_height = 21;
		}
		$(this).height( new_height +'px' );
	} )
}

var monthLength = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
function ValidateDate(DateValue)
{
	monthLength[1] = 28; // Resetting it back to its original value in case the function needs to change it for leap year
//	var correctformat = /^\d{1,2}\/\d{1,2}\/\d{4}$/
	var correctformat = /^\d{2}\/\d{2}\/\d{4}$/

	if (correctformat.test(DateValue)) // Check for the right format first
	{
		var month = parseInt(DateValue.substr(0,2),10);
		var day = parseInt(DateValue.substr(3,2),10);
		var year = parseInt(DateValue.substr(6,4),10);

		if (month > 12 || month < 1)
		{
			return false;
		}

		//A year is a leap year if it is divisble by 4 and not divisible 100, or also divisible by 400:
		if ((year/4 == parseInt(year/4)) && (year/100 != parseInt(year/100) || year/400 == parseInt(year/400)))
		{
			monthLength[1] = 29;
		}

		if (day > monthLength[month-1])
		{
			return false;
		}

		return true;
	}
	else
	{
		return false;
	}
}
