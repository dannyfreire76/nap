// 2/22/2009: changed popCID call and function
// 5/26/2009: changed code for logging in while in cart
var Main = new function() {
	this.init = function() {
		$('.cid_link').click(Main.popCID);
		if ( $('#login').size()==0 ) {			$('body').append('<div style="display:none" id="login"></div>');
					$('#login').click(function(){
				
				window.location = "/login.php";
			});
		}
	}
	this.popCID = function(){
		var cid_pos = findPos( $(this).get(0) );
		$('.cid_pop')
			.css('left', ( cid_pos[0]+64 )+'px')
			.css('top', (cid_pos[1])+'px')
			.fadeIn(300)
	}   
}

$(function() {//on doc ready (put it after Main is defined above or might throw error)
	Main.init();
});

