$(function() {//on doc ready
	Retail.init();
});

var Retail = new function() {
	
	this.init = function() {
		$('#zip').keypress( function(e){
			if (e.keyCode==13) {
				Retail.checkForm();
				return false;
			}
		} )
		$('#retailer_go').click(Retail.checkForm);
		$('#fr_form').submit( function(){return false} );
	}

	this.checkForm = function() {
		var err_msg = '';
		var err_fld = '';
		var form = $('#zip').parents('form');
		var r_zip = $('#zip',form).val().trim();

		if ( r_zip=='' || isNaN(r_zip) || r_zip.length < 5 ) {
			err_msg = 'Please enter a zip code (at least 5 digits).';
			err_fld = 'zip';
		}

		if ( err_fld != '' ) {
			if ( $('#retail_results').html()!='' ) {
				$('#retail_results').slideUp(400, function(){
					$('#retail_results').html('');
				})
			}

			$( '.loading',form ).addClass('error').html('<br />'+err_msg).fadeIn(200);
			$( '#'+err_fld,form ).focus();
			return false;
		}
		else {
			$('#retailer_go').attr('disabled', 'true');
			$( '.loading', form ).removeClass('error').large_spinner().fadeIn(200, function(){

				if ( $('#retail_results').html()!='' ) {
					$('#retail_results').slideUp(400, function(){
						$('#retail_results').load('index.php?submittal=1&zip='+r_zip,function(){
							$(this).slideDown(800, function(){
								$( '.loading', form ).fadeOut(200, function(){ $(this).html('') });
								$('#retailer_go').removeAttr('disabled');
							})
						});			
					})
				}
				else {
					$('#retail_results').load('index.php?submittal=1&zip='+r_zip,function(){
						$(this).slideDown(800, function(){
							$( '.loading', form ).fadeOut(200, function(){ $(this).html('') });
							$('#retailer_go').removeAttr('disabled');
						})
					});			
				}
			})
			return false;
		}

	}
}