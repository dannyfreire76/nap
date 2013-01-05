//  extensions for jQuery
var spind = "/images/icons/iconIndicatorDkGreenBG.gif";
var spind1 = new Image();
spind1.src = spind;


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
	} else if (document.body.offsetHeight || document.body.offsetWidth){ // all but Explorer Mac
		scrollvalue = document.documentElement.scrollTop > document.body.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
		yScroll = (document.body.offsetHeight + scrollvalue)/2;
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