$(function() {//on doc ready
	Quest.init();
});

var Quest = new function() {
	
	this.init = function() {
		$('#q_login').click(Quest.checkLogin);
		$('#q_signup').click(Quest.checkSignUp);
		$('#q_submit').click(Quest.checkQuestionnaire);
		$('#main_q').submit( function(){return false} );
		$('.has_form_tip').focus(Quest.showFormTip);
		$('.has_form_tip').blur(Quest.hideFormTip);
	}

	this.showFormTip = function() {
		$the_field = $(this);
		tip_pos = findPos( $the_field.get(0) );
		parent_cell = $(this).parents('td:first');
		$('.form_tip', parent_cell)
			.css('left', (tip_pos[0]+158)+'px')
			.css('top', tip_pos[1]+'px')
			.fadeIn( 200, function(){
				$the_field.bind('blur', Quest.hideFormTip)
			});
		return false;
	}

	this.hideFormTip = function() {
		parent_cell = $(this).parents('td:first');
		$('.form_tip', parent_cell).fadeOut();
		return false;
	}
	this.checkLogin = function() {
		var err_msg = '';
		var err_fld = '';
		var form = $(this).parents('form');
		var login_name = $('#login_name',form).val().trim();
		var login_pw = $('#login_pw',form).val().trim();
		
		if (login_name=='') {
			err_msg = 'Please enter your login name.';
			err_fld = 'login_name';
		}
		else if (login_pw=='') {
			err_msg = 'Please enter your password.';
			err_fld = 'login_pw';
		}

		if ( err_fld != '' ) {
			$( '.loading',form ).addClass('error').html('<br />'+err_msg).slideDown(200);
			$( '#'+err_fld,form ).focus();
			return false;
		}
		else {
			$('#q_login').attr('disabled', 'true');
			$( '.loading',form ).large_spinner().slideDown(200);

			$.post('login.php', { submittal:1, q_login:login_name, q_pw:login_pw }, function(resp){
				resp = resp.split('|');
				if ( resp[0] == 'ok' ) {
					window.location = 'index.php';				   
				}
				else {
					$( '.loading',form ).addClass('error').html('<br />'+resp[0]);
					$('#q_login').removeAttr('disabled');
				}
			})
		}
	}

	this.checkSignUp = function() {
		var err_msg = '';
		var err_fld = '';
		var form = $(this).parents('form');
		var age = $('#age',form).val().trim();
		var login_name = $('#login_name',form).val().trim();
		var login_pw = $('#login_pw',form).val().trim();
		var login_pw_confirm = $('#login_pw_confirm',form).val().trim();
		var rad_selected = false;
		var sex = '';

		$('input.rad',form).each(function() {
			rad_chk = $(this).get(0).checked;
			if ( rad_chk ) {
				sex = $(this).val();
				rad_selected = true;
			}
		});  

		if (age=='') {
			err_msg = 'Please enter your age.';
			err_fld = 'age';
		}
		else if (!rad_selected) {
			err_msg = 'Please select your sex.';
			err_fld = 'sexM';
		}
		else if (login_name=='' || login_name.length<6 ) {
			err_msg = 'Please enter a login name (at least 6 characters).';
			err_fld = 'login_name';
		}
		else if (login_pw=='' || login_pw.length<6 || login_pw.length>9) {
			err_msg = 'Please enter a password (6 - 9 characters).';
			err_fld = 'login_pw';
		}
		else if (login_pw!=login_pw_confirm) {
			err_msg = 'Confirm Password does not match Password.';
			err_fld = 'login_pw_confirm';
		}
		
		err_msg = '<br /><br />'+err_msg;

		if ( err_fld != '' ) {
			$( '.loading',form ).addClass('error').html(err_msg).slideDown(200);
			$( '#'+err_fld,form ).focus();
			return false;
		}
		else {
			$('#q_signup').attr('disabled', 'true');
			$( '.loading',form ).large_spinner().slideDown(200);

			$.post('signup.php', { submittal:1, q_age:age, q_sex:sex, q_login:login_name, q_pw:login_pw }, function(resp){
				resp = resp.split('|');
				if ( resp[0] == 'ok' ) {
				   $( '.loading',form ).removeClass('error').html('Thanks for signing up!  Please <a href="index.php">click here</a> to start completing questionnaires!');
				}
				else {
					$( '.loading',form ).addClass('error').html(resp[0]);
				}
				$('#q_signup').removeAttr('disabled');
			})
		}
	}

	this.checkQuestionnaire = function() {
		var form_complete = true;
		var form_data = "qhid="+$('#submit_qhid').val()+"&submittal=1";
		var form = $(this).parents('form');

		//first, get all radios which are not disabled
		active_radios = new Array();
		completed_radios = new Array();

		$('tr[@id*=qrow_]').removeClass('form_error');

		$("input[@type='radio']:not(':disabled')", form).each( function() {
			if (   !active_radios.has( $(this).attr("name") )   ) {
				active_radios.push($(this).attr("name"));//add this unique radio set to the array
			}
			if ( $(this).get(0).checked ) {
				completed_radios.push($(this).attr("name"));
				form_data +=  "&" + $(this).attr("name") + "=" + $(this).val();
			}
		})
		
		if ( completed_radios.length < active_radios.length ) {
			for (var i=0; i<active_radios.length; i++) {
				if ( !completed_radios.has(active_radios[i]) ) {
					//try to focus on the row above this one so it looks better to the user, but if there isn't one above it, focus on this one itself
					$this_row = $('input[@name='+active_radios[i]+']').parents('tr[@id*=qrow_]')
					$prev_row = $this_row.prev().prev();
					if ( $('input:first-child', $prev_row).size() == 0 ) {
						$prev_row = $this_row;
					}
					$prev_row = $('input:first-child', $prev_row);
					$prev_row.focus();

					$('input[@name='+active_radios[i]+']').parents('tr[@id*=qrow_]').addClass('form_error');
					loading_pos = findPos( $this_row.get(0) );
					$('#q_loading')
						.css('left', (loading_pos[0]+6) + 'px')
						.css('top', (loading_pos[1] + 30) + 'px')
						.html('Please answer the highlighted question.')
						.fadeIn(300, function(){
							$('input', $this_row).get(0).focus();
							setTimeout( "$('#q_loading').fadeOut()", 4000 );
						});
					return false;
					break;
				}
			}
		}

		//get any textareas which are not disabled or empty
		$("textarea:not(':disabled')", form).each( function() {
			if ( $(this).val() != '' ) {
				form_data +=  "&" + $(this).attr("name") + "=" + $(this).val();
			}
		})

		$('#q_submit').attr('disabled', '1');
		var loading_pos = findWindowCenter(-60, -60);

		$('body').loading_layer();
		$('#q_loading')
			.large_spinner()
			.css('left', (loading_pos[0]) + 'px')
			.css('top', (loading_pos[1]) + 'px')
			.fadeIn();

		$.get('index.php?'+form_data, function(resp) {
			resp = resp.split('|');
			if ( resp[0] == 'ok' ) {
			   window.location = "index.php?thanks=1"
			}
			else {
				$( '#q_loading' ).addClass('error').html(resp[0]);
			}
			$('#q_submit').removeAttr('disabled');
		})
		return false;
	}

	this.checkDependent = function(dq, da, therow) {
		selected_id = '';
		$prev_row = $('#'+therow).prev();
		$('input[@name='+dq+']').each(function() {
			rad_chk = $(this).get(0).checked;
			if ( rad_chk ) {
				selected_id = $(this).attr("id");
			}
		});
		if ( selected_id == da ) {
			$('input','#'+therow).removeAttr("disabled");
			$('.qlabel', $prev_row).removeClass("row_disabled");
			$('td','#'+therow).removeClass("row_disabled");
		}
		else {
			$('input','#'+therow).attr("disabled", "1");
			$('.qlabel', $prev_row).attr("style","").addClass("row_disabled");
			$('td','#'+therow).attr("style","").addClass("row_disabled");
		}

	}


}

