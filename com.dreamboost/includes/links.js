$(function() {//on doc ready
	Links.init();
});

var Links = new function() {
	this.init = function() {
		if ( $('.link_title').size()>1 ) {
			$('.link_title').each( function(){
				$(this).addClass('underline').addClass('hand')
				var to_open = '#' + $(this).attr('toopen');
				$(to_open).addClass('no_display');
				$(this).click( function(){
					$(to_open).slideToggle(400);
				} );
			})
		}
	}
}

