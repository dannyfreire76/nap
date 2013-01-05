$(function() {//on doc ready
	RepInc.init();
});

var RepInc = new function() {

	this.init = function() {
		
		RepInc.bindAreaOptions();

	}

	this.hlSelected = function() {
		var $theRow = $(this).parents('tr:first');

		$(this).each(function(){
			if ( $(this).attr('id').indexOf('neither') == -1 ) {
				$theRow.addClass('error3');
			}
		})
	}

	this.bindAreaOptions = function() {
		$(':input:checked', '#repStatesWrapper').each(RepInc.loadSubAreas);
		$(':input', '#repStatesWrapper').unbind().click(RepInc.loadSubAreas);
	}


	this.loadSubAreas = function() {
		var $showThis = $( '#'+$(this).attr('showThis') );

		if ( $(this).is(':checked') ) {

			if ( $(this).is(':checkbox') ) {
				$('label[for='+$(this).attr('id')+']').addClass('error3')
			}
	
			if ( $showThis.size()>0 ) {
				if ( !$showThis.is(':visible') ) {
					$showThis.slideDown(300);

					if ( $showThis.html()=="" ) {
						
							var prevVal = $showThis.attr('prevVal');
							var prevLevel = $showThis.attr('prevLevel');
							var level = $showThis.attr('level');
							var key = $showThis.attr('key');
							var areaType = $showThis.attr('areaType');

							var post_url = $('#current_base').val()+'includes/reps.php';
							$showThis.small_spinner().load(post_url, 
								{
									getAreaData: 1, 
									previous_value:prevVal, 
									previous_level:prevLevel, 
									this_level:level, 
									this_key:key, 
									area_type:areaType,
									rep_id:$('#rep_id').val()
								
								}, function(resp){
									RepInc.bindAreaOptions();
								}
							)
						
					}
				}
			} else {
				var $hideThis = $( '#'+$(this).attr('hideThis') );

				if ( $hideThis.is(':visible') ) {
					$hideThis.slideUp(250);
				}
			}
		} else {
			if ( $(this).is(':checkbox') ) {
				$('label[for='+$(this).attr('id')+']').removeClass('error3')
			}

			if ( $showThis.is(':visible') ) {
				$showThis.slideUp(250);
			}
		}
	}

}