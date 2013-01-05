$(function() {//on doc ready
	Report.init();
});

var Report = new function() {
	
	this.init = function() {
		$('.theres_more')
			.mouseover(Report.showDetails)
			.mouseout(Report.hideDetails);
	}

	this.showDetails = function() {
		$the_el = $(this);
		$el_to_show = $('.details', $the_el);

		var details_pos = findPos( $the_el.get(0) );
		$($el_to_show).css('width', ( $the_el.width() ) + 'px')//set the width to the same as the cell triggering this, so that they line up

		if ( $the_el.attr('details_align')=='top' ) {
			details_pos[1] = details_pos[1] - $el_to_show.height() - 5;
		}
		else if ( $the_el.attr('details_align')=='right' ) {
			details_pos[0] = details_pos[0] + $the_el.width();
		}
		else if ( $the_el.attr('details_align')=='left' ) {
			$($el_to_show).css('width', '300px');
			details_pos[0] = details_pos[0] - 307;
		}

		$($el_to_show)
			.css('top', (details_pos[1]) + 'px')
			.css('left', (details_pos[0]) + 'px')
			.fadeIn(150);
		return false;
	}

	this.hideDetails = function(evt) {
		$('.details', this).fadeOut(100);
		return false;
	}

}