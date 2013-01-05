// 2/22/2009: changed popCID call and function
// 5/26/2009: changed code for logging in while in cart

var Main = new function() {

	this.init = function() {
		Main.curr_loc = window.location + '';
		this.curr_loc = this.curr_loc.substring( this.curr_loc.indexOf('.com')+4 );
		Main.special_hgt_correct = -256;
		Main.special_wdh_correct = -206;

		//START menu-related functions
		$(".footer_nav .form_link").each(function(){
			$(this).mouseout(function() {
				if ( $(this).attr("class").indexOf('open') == -1 ) {
					$(this).removeClass('on');
				}
			})
			$(this).click( Main.toggleMenu )

		})

		Main.prepFormLinks();

		if ( $('#retailer_login_form .nav_form').html()=='' ) {
			Main.switchToRetailerLogout();
		}

		if ( $('#login_form').size()==0 ) {
			Main.prepLogout();
		}
		else {
			Main.initLogin();
		}

		 $('#container').click( function(){
			Main.closeOpenMenus();
		 } )

		Main.bindResizeForForms();

		$('#rep_logout').unbind().bind('click', Main.repLogOut);

		//END menu-related functions

		//get rid of or override hard-coded attributes within pages (not css)
		$('div[@align]').each( function() {
			$(this).attr('align', '');
		} )

		$('table[@width]').each( function() {
			if ( $(this).attr('width').indexOf('%')==-1 ) {
				$(this).removeAttr('width');
			}
		} )

		$('img[@width]').each( function() {
			$(this).removeAttr('width');
		} )

		$('img[@height]').each( function() {
			$(this).removeAttr('height');
		} )

		$('#container > table:first').each( function() {
			if ( !$(this).attr('cellpadding') ) {
				$(this).attr('cellpadding', '0');
			}
			if ( !$(this).attr('cellspacing') ) {
				$(this).attr('cellspacing', '0');
			}
		} )

		$('td[@width]').each( function() {
			if ( $(this).attr('width').indexOf('%')==-1 ) {
				$(this).removeAttr('width');
			}
		} )

		$('td[@height]').each( function() {
			if ( $(this).attr('height').indexOf('%')==-1 ) {
				$(this).removeAttr('height');
			}
		} )

		$('#container > table td[@align]:first').each( function() {
			if ( $(this).attr('align') ) {
				$(this).removeAttr('align');
			}
		} )
		//END get rid of...

		if ( $('#header').size() > 0 ) {
			$('#header #header_nav .nav_div[@id!=sub_menu]').each(function(){
				//set the widths so that when animated, the div stays still
				var link_width = parseInt( $(this).outerWidth() );
				$(this)
					.css('width', link_width + 'px')
					.mouseover( Main.animateNav )
			})
		}

		if ( Main.curr_loc.indexOf('/store/') != -1 || Main.curr_loc.indexOf('/cart.php') != -1 ) {//in store
			var img1 = new Image();
			img1.src = '/images/button_order_now_over.gif';
			var img2 = new Image();
			img2.src = '/images/button_add_to_cart_over.gif';
			var img3 = new Image();
			img3.src = '/images/button_update_cart_over.gif';
			var img5 = new Image();
			img5.src = '/images/button_view_now_over.gif';

			$('img[@src*=button_order_now]')
				.mouseover( function(){
					$(this).attr( 'src', '/images/button_order_now_over.gif');
				})
				.mouseout( function(){
					$(this).attr( 'src', '/images/button_order_now.gif');
				})

			$('img[@src*=button_view_now]')
				.mouseover( function(){
					$(this).attr( 'src', '/images/button_view_now_over.gif');
				})
				.mouseout( function(){
					$(this).attr( 'src', '/images/button_view_now.gif');
				})

			$('#button_add_to_cart')
				.addClass('hand')
				.val('')
				.mouseover( function(){
					$(this).addClass('over');
				})
				.mouseout( function(){
					$(this).removeClass('over');
				})

			$('input[@value*=Update Cart]')
				.addClass('hand')
				.addClass('update_cart')
				.val('')
				.mouseover( function(){
					$(this).addClass('update_cart_over');
				})
				.mouseout( function(){
					$(this).removeClass('update_cart_over');
				})

			$('input[@value*=Secure Checkout]')
				.addClass('hand')
				.addClass('secure_checkout')
				.val('')
				.mouseover( function(){
					$(this).addClass('secure_checkout_over');
				})
				.mouseout( function(){
					$(this).removeClass('secure_checkout_over');
				})
		}

		var img4 = new Image();
		img4.src = '/images/button_subscribe_over.gif';

		$('input[@src*=button_subscribe]')
			.mouseover( function(){
				$(this).attr( 'src', '/images/button_subscribe_over.gif');
			})
			.mouseout( function(){
				$(this).attr( 'src', '/images/button_subscribe.gif');
			})

		$('#newsletter_f').submit( function(){ return false; } );
		$('#button_subscribe_home').click(Main.checkSubsc);

		$('.cid_link').click(Main.popCID);

		/*
		if ( Main.curr_loc == '') {//home page
			var img1a = new Image();
			img1a.src = '/images/game.gif';
			var img2a = new Image();
			img2a.src = '/images/close.gif';
			setTimeout( "Main.openSpecial()", 1700 );//even though this is onload, some browsers still need a moment to calculate height of window
		}
		*/
	}

	this.repLogOut = function() {
	var post_url = $('#current_base').val()+'reps/index.php';
	$.post(post_url, { action:'logout' }, function(resp){
		$('#header').ScrollTo(400);
		var submenu_url = $('#current_base').val()+'includes/reps_head1.php?action=menu';
		$('#header_nav').load( submenu_url, function() {
			window.location.href=$('#current_base').val()+'reps/';
		})
	})
}			


	this.prepFormLinks = function(){
		$(".form_link").each(function(){
			$(this).mouseout(function() {
				if ( $(this).attr("class").indexOf('open') == -1 ) {
					$(this).removeClass('on');
				}
			})
			$(this).click( Main.toggleMenu )

		})	
	}

	this.popCID = function(){
		var cid_pos = findPos( $(this).get(0) );
		$('.cid_pop')
			.css('left', ( cid_pos[0]+86 )+'px')
			.css('top', (cid_pos[1])+'px')
			.fadeIn(300)
	}

	this.initLogin = function() {
		Main.initLoginForms('#login', '#c_forget_uandp');
	}

	this.initRetailerLogin = function() {
		Main.initLoginForms('#retailer_login', '#forget_uandp');
	}

	this.initLoginForms = function(form_id, forgetform_id){
		if ( $(form_id+'_actual').size()>0 ) {
			$(form_id+'_actual').unbind().submit( function(){return false;} )
			$(form_id+'_actual' + '  #submit').unbind().click(Main.checkLogin);
			$(form_id+'_actual' +' #forgotpw, '+form_id+'_actual' +' #nevergotpw').unbind().click(Main.switchToPW)
		}
		
		var logins_in_form = $('#login_link', forgetform_id).size();
		if ( logins_in_form > 0 ) {
			$(forgetform_id).unbind().submit( function(){return false;} )
			$('#submit', forgetform_id).unbind().click(Main.checkEmailPW);
			$('#login_link', forgetform_id).unbind().click( function(){
				$(form_id+'_form').fadeOut(200, function() {
					if ( form_id=='#retailer_login' ) {
						Main.switchToRetailLogin();
					}
					else {
						Main.switchToLogin();
					}
				})
			})
		}
	}

	this.switchToLogin = function() {
		Main.switchToLoginForm('login');
	}

	this.switchToRetailLogin = function() {
		Main.switchToLoginForm('retailer_login');
	}

	
	this.switchToLoginForm = function(thisID){
		$('#'+thisID+'_form .nav_form').load( $('#current_base').val()+thisID+'.php', function(){
			Main.rePosition( $('#'+thisID) );
			if ( thisID=='retailer_login' ) {
				$('#'+thisID).html('Retailer Login')				
			}

			$('#'+thisID)
				.unbind()
				.bind('click', Main.toggleMenu);
			if ( $('#'+thisID).attr("class").indexOf('open') != -1 ) {//should be open, so show it
				$('#'+thisID+'_form').fadeIn(300, function(){
					$('input:first', $('#'+thisID+'_form .nav_form')).focus();

					if ( thisID=='retailer_login' ) {
						Main.initRetailerLogin();
					}
					else {
						Main.initLogin();
					}
					return false;
				})
			}
		} );
	}

	this.checkEmailPW = function() {
		var err_msg = '';
		var err_fld = '';
		//var form = $('#forget_uandp');
		var form = $(this).parents('form');
		var email1 = $('#email',form).val().trim();

		if ( email1 == '' || !email1.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\..{2,2}))$)\b/gi) ) {
			err_msg = 'Please enter a valid email.';
			err_fld = 'email';
		}

		if ( err_fld != '' ) {
			$( '.loading',form ).addClass('error2').html(err_msg).fadeIn(200);
			$( '#'+err_fld,form ).focus();
			return false;
		}
		else {
			$('#submit',form).attr('disabled', 'true');
			$( '.loading',form ).fadeOut(200, function(){
				$(this).small_spinner_on_purple().fadeIn(300, function(){
					var post_url = form.attr('action');
					$.post(post_url, { submit:1, email:email1 }, function(resp){
						if ( resp == 'ok' ) {
							$( '.loading',form ).addClass('error2').html('An email has been sent.');
						}
						else {
							$( '.loading',form ).addClass('error2').html(resp);
							$('#submit',form).removeAttr('disabled');
						}
					})

				});
			})
			
		}
		return false;
	}

	this.switchToPW = function() {
		var forget_url = $(this).attr('link');
		var wrapper = $(this).parents('.nav_form_wrapper');
		wrapper.fadeOut(200, function() {
			$('.nav_form', this).load( forget_url, function(){
				if ( wrapper.attr('id')=='retailer_login_form' ) {
					Main.rePosition( $('#retailer_login') );
					$('#retailer_login_form').fadeIn(200, function(){
						$('#retailer_login').html('Forgot Password');
						$('input:first', this).focus();
						Main.initRetailerLogin();
					});
				}
				else {
					Main.rePosition( $('#login') );
					$('#login_form').fadeIn(200, function(){
						$('input:first', this).focus();
						Main.initLogin();
					});
				}
			} );
		})
	}

	this.rePosition = function($elem) {
		var link_id = $elem.attr("id");
		var new_form_pos = findPos( $('#'+link_id).get(0) )
		elem_width = $('#'+link_id).width();
		$('#'+link_id+'_form').each( function(){
			if ( $(this).attr("class").indexOf('header_form') != -1 ) {
				$(this).css( 'top', ( new_form_pos[1]+$('#'+link_id).height()+10 )+'px' )
				
				if ( navigator.userAgent.indexOf("Firefox") == -1 ) {
					$(this).css('left', (new_form_pos[0]-($(this).width()/2)+(elem_width/2) + 15 )+'px' )
				}
			}
			else {
				$(this).css( 'top', ( new_form_pos[1]-$(this).height()+5 )+'px' )
				$(this).css('left', (new_form_pos[0]-($(this).width()/2)+(elem_width/2) )+'px' )
			}
		} )
	}

	this.checkLogin = function() {
		var err_msg = '';
		var err_fld = '';
		//var form = $('#retailer_login_actual');
		var form = $(this).parents('form');
		var username1 = $('#username',form).val().trim();
		var pw1 = $('#password',form).val().trim();

		//BRAIN:
		var persist1 = false;

		if ( $('#persist',form).size()>0 ) {
			persist1 =  $('#persist',form).is(":checked");
		}
		//END BRAIN


		if (username1=='') {
			err_msg = 'Please enter your username.';
			err_fld = 'username';
		}
		else if ( pw1 == '' ) {
			err_msg = 'Please enter your password.';
			err_fld = 'password';
		}

		if ( err_fld != '' ) {
			$( '.loading',form ).addClass('error2').html(err_msg).fadeIn(200);
			$( '#'+err_fld,form ).focus();
			return false;
		}
		else {
			$('#submit',form).attr('disabled', 'true');
			$( '.loading',form ).fadeOut(200, function(){
				$(this).small_spinner_on_purple().fadeIn(300, function(){
					var post_url = form.attr('action');
					//BRAIN = add persist
					$.post(post_url, { submit:1, username:username1, password:pw1, persist:persist1 }, function(resp){
						//BRAIN:
						if ( resp.substring(0, 2) == 'ok' ) {
							if ( form.attr('id')=='retailer_login_actual' ) {
								if ( Main.curr_loc.indexOf('/store/') != -1 ) {//in store
									window.location = $('#current_base').val()+'store/';
								}
								else {
									Main.closeMenuForm('retailer_login');
									var submenu_url = $('#current_base').val()+'includes/head1.php?action=submenu'
									$('#sub_menu').load( submenu_url, function() {
										//$(this).Pulsate(300, 4)
										$('#header').ScrollTo(400);
										Main.switchToRetailerLogout();
									} )
								}
							}

							if ( form.attr('id')=='login_actual' ) {
								//BRAIN:
								//output the rest of the response because it contains the other servers images (and having them in the AJAX response doesn't actually execute them)
								var resp_arr = resp.split('|');
								var imagesStr = resp_arr[1];
								$('body').append('<div class="partnerSiteImgs" class="no_display">'+imagesStr+'</div>');
								//we use the fadeIn to make sure that the image executes on the other servers before we reload the submenu
								$('.partnerSiteImgs:last').fadeIn(200, function(){
									if ( Main.curr_loc.indexOf('/cart.php') != -1 || Main.curr_loc.indexOf('store/step') != -1 ) {//in cart or checkout, reload it
										// START 5/26/2009
										$('#sub_menu').fadeOut( 200, function() {
											$(this).html('<img src="/images/icons/iconIndicatorBlueBG.gif" /> <span class="error">Your cart will momentarily be updated with items you previously saved.  Please double-check it before completing your order.</span>')
												.fadeIn(250);
										})
										setTimeout( "window.location.replace(window.location.href)", 5000 )
										// END 5/26/2009
									}
									else if ( Main.curr_loc.indexOf('store/confirm.php') != -1 || Main.curr_loc.indexOf('customer/') != -1 ) {//in profile or confirm, get out
										window.location = $('#current_base').val()+'store/';
									}
									else {
										Main.closeMenuForm('login');
										var submenu_url = $('#current_base').val()+'includes/head1.php?action=submenu'
										$('#sub_menu').load( submenu_url, function() {
											//$(this).Pulsate(300, 4);
											$('#header').ScrollTo(400);
											Main.prepLogout();
										} )
									}									
								} );
							}

						}
						else {
							$( '.loading',form ).addClass('error2').html(resp);
							$('#submit',form).removeAttr('disabled');
						}
					})

				});
			})
			
		}
		return false;
	}

	this.prepLogout = function() {
		$('#logout')
			.unbind()
			.bind('click', Main.logOut);
	}

	this.switchToRetailerLogout = function() {
		$('#retailer_login')
			.html('Retailer Logout')
			.unbind()
			.bind('click', Main.logOutRetailer);
	}

	this.logOut = function() {
		var post_url = $('#current_base').val()+'login.php';
		$.post(post_url, { action:'logout' }, function(resp){
			//BRAIN:
			//output the response because it contains the other servers images (and having them in the AJAX response doesn't actually execute them)
			var imagesStr = resp;
			$('body').append('<div class="partnerSiteImgs" class="no_display">'+imagesStr+'</div>');
			//we use the fadeIn to make sure that the image executes on the other servers before we reload the submenu
			$('.partnerSiteImgs:last').fadeIn(200, function(){

				if ( Main.curr_loc.indexOf('/cart.php') != -1 || Main.curr_loc.indexOf('store/step') != -1 ) {//in cart or checkout, reload it
					window.location.replace(window.location.href)
				}
				else if ( Main.curr_loc.indexOf('store/confirm.php') != -1 || Main.curr_loc.indexOf('customer/') != -1 || Main.curr_loc.indexOf('questionnaires/') != -1 ) {//in checkout or customer features, get out
					window.location = $('#current_base').val()+'store/';
				}
				else {
					$('#header').ScrollTo(400);
					var submenu_url = $('#current_base').val()+'includes/head1.php?action=submenu';
					$('#sub_menu').load( submenu_url, function() {
						//$(this).Pulsate(300, 4, function(){
							Main.initLogin();
							Main.prepFormLinks();
						//})
					})
				}
			})
		})
	}

	this.logOutRetailer = function() {
		var post_url = $('#current_base').val()+'retailer_login.php';
		$.post(post_url, { action:'logout' }, function(resp){
			if ( Main.curr_loc.indexOf('/store/') != -1 || Main.curr_loc.indexOf('/wc/') != -1 ) {//in store or wc
				window.location = $('#current_base').val()+'store/';
			}
			else {
				$('#header').ScrollTo(400);
				var submenu_url = $('#current_base').val()+'includes/head1.php?action=submenu';
				$('#sub_menu').load( submenu_url, function() {
					//$(this).Pulsate(300, 4, function(){
						Main.initLogin();//have to do this too because this area changes on logout
						Main.initRetailerLogin();
						Main.prepFormLinks();
					//})
					Main.switchToRetailLogin();
				})
			}
		})
	}

	this.toggleMenu = function() {
		menu_id = $(this).attr("id")
		if ( $(this) && $(this).attr("class").indexOf('open') == -1 ) {
			$(".form_link").each(function(){
				if ( $(this).attr("class").indexOf('open') != -1 && $(this).attr("id")!=menu_id ) {
					Main.closeMenuForm( $(this).attr('id') );
				}		 			
		 	})
				
			if ( $('#'+menu_id+'_form').attr("class").indexOf('header_form') != -1 ) {
				$(this).animate( { className: 'sub_menuaonlink', background: "#A5087B"}, 300);
			}
			else {
				$(this).addClass('on')
			}

			if ( menu_id=='retailer_login' ) {
				Main.initRetailerLogin();
			}

			Main.rePosition( $('#'+menu_id) );
			$('#'+menu_id+'_form').fadeIn(300, function(){
				$('#'+menu_id+'_form input:first').focus();
				$('#' + menu_id).addClass('open');
			});
		}
		else {
			Main.closeMenuForm(menu_id);
		}
		return false;
	}

	 
	this.closeMenuForm  = function(ident) {
		$("#"+ident).removeClass('open');
		if ( $("#"+ident+'_form').attr("class").indexOf('header_form') != -1 ) {
			if ( navigator.userAgent.indexOf("Safari") != -1 ) {
				$("#"+ident).animate( { className: 'sub_menua', background: "transparent"}, 300);
			}
			else {
				$("#"+ident).animate( { className: 'sub_menua', background: "none"}, 300);
			}
		}
		else {
			$("#"+ident).removeClass('on')
		}

		$('#'+ident+'_form').fadeOut(300);
	}
		
	this.closeOpenMenus = function() {
		$(".form_link").each(function(){
			if ( $(this).attr("class").indexOf('open') != -1 ) {
				Main.closeMenuForm( $(this).attr('id') );
			}		 			
	 	})		
	}

	this.bindResizeForForms = function() {
		$(window).resize(function(){
			$('.form_link').each( function(){
				if ( $(this).attr("class").indexOf('open') != -1 ) {
					Main.rePosition( $(this) );
				}			
			} )
		});	
	}

	this.openSpecial = function() {
	    var dims1 = findWindowCenter(-156, -156);	    			
		var new_left = dims1[0];
		var new_top = dims1[1];
		
		$('#special')
			.css('left', new_left)
			.css('top', new_top)
			.fadeIn(1000, function(){
				$(this).addClass('open')

				$('.close', this).click(function(){
					$('#special').fadeOut(400, function(){
						$('#special').remove();
						$(window).unbind()

						Main.bindResizeForForms();
					});
					return false;
				})

				$('#ad', this).click(function(){
					$('#ad').fadeOut(200, function(){
						Main.openGame();
					});
				})

				$(window).resize(function(){
					Main.reCenter( $('#special'), -156, -156 );
				});
				$(window).scroll(function(){
					Main.reCenter( $('#special'), -156, -156 );
				});

			});
	}

	this.openGame = function(){
		Main.reCenter( $('#special'), Main.special_wdh_correct, Main.special_hgt_correct );
		$(window).unbind();
		$(window).resize(function(){
			Main.reCenter( $('#special'), Main.special_wdh_correct, Main.special_hgt_correct );
		});
		$(window).scroll(function(){
			Main.reCenter( $('#special'), Main.special_wdh_correct, Main.special_hgt_correct );
		});

		var game_url = $('#current_base').val() + 'quiz.php';
		$('#game_questions')
			.addClass('text_center')
			.spinner_on_purple()
			.fadeIn(300, function(){
				$(this).load( game_url, function(){
					$(this).removeClass('text_center');
					$('.close', this).click(function(){
						$('#special').fadeOut(400, function(){$('#special').remove(); $(window).unbind() });
					})
					$('#qa_1').fadeIn(300);
					$('#submit_quiz').click( Main.submitQuiz );
				} )
			})
	}

	this.submitQuiz = function(){
		var game_url = $('#current_base').val() + 'quiz.php';
		var form_data = "?submittal=1";
		var form = $(this).parents('form');
		var q_num = $(this).attr("question_num");

		active_radios = new Array();
		completed_radios = new Array();

		$("#qa_"+q_num+" input[@type='radio']").each( function() {
			if (   !active_radios.has( $(this).attr("name") )   ) {
				active_radios.push($(this).attr("name"));//add this unique radio set to the array
			}
			if ( $(this).get(0).checked ) {
				completed_radios.push($(this).attr("name"));
				form_data +=  "&" + $(this).attr("name") + "=" + $(this).val();
				form_data +=  "&q_num=" + q_num;//only applicable when we're only showing one question at a time
			}
		})

		if ( completed_radios.length < active_radios.length ) {
			for (var i=0; i<active_radios.length; i++) {
				if ( !completed_radios.has(active_radios[i]) ) {
					var err_question = $('input[@name='+active_radios[i]+']').attr('question');
					//$('#hidden'+err_question).focus()//.blur();
					//$('input[@name='+active_radios[i]+']:first').focus();
					$('#'+err_question).Pulsate(200, 3);

					return false;
					break;
				}
			}
		}

		$('#load_layer'+ q_num).spinner_on_purple().fadeIn();

		//$('#inner_questions')
		$('#q_feedback'+ q_num)
			.addClass('text_center')
			.fadeOut(100, function(){
				$(this)
					.load( game_url+form_data, function(){
						$('#load_layer'+ q_num).fadeOut(200);
						$(this)
							.removeClass('text_center')
							.fadeIn(200, function(){
								$('#next_question'+q_num+' a').click(function(){
									$show_this = $( '#qa_'+$(this).attr('next_q') );
									$hide_this = $( '#qa_'+q_num );
									$hide_this.fadeOut(300, function(){
										$show_this.fadeIn(300);
										$('#submit_quiz').click( Main.submitQuiz );
									});
								})
														

								if ( $('#results').size() > 0 ) {
									$('#just_qa'+q_num).slideUp(500);
								}

								$('#q_response').Pulsate(200, 2);						
							});
					} )
			})
			
		return false;
	}

	this.reCenter = function($el, h_correct, v_correct) {
	    var dims = findWindowCenter(h_correct, v_correct);	    			
		//var new_left = (dims[0]/2)-370;
		//var new_top = (dims[1]/2)-165;
		var new_left = dims[0];
		var new_top = dims[1];

		$el.css('left', new_left).css('top', new_top);			
	}

	this.checkSubsc = function() {
		var err_msg = '';
		var err_fld = '';
		var form = $('#newsletter_f');
		var sname = $('#sname',form).val().trim();
		var semail = $('#semail',form).val().trim();

		if (sname=='') {
			err_msg = 'Please enter your name.';
			err_fld = 'sname';
		}
		else if ( semail == '' || !semail.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\..{2,2}))$)\b/gi) ) {
			err_msg = 'Please enter a valid email address.';
			err_fld = 'semail';
		}

		if ( err_fld != '' ) {
			$( '.loading',form ).addClass('error').html(err_msg).fadeIn(200);
			$( '#'+err_fld,form ).focus();
			return false;
		}
		else {
			$('#button_subscribe_home').attr('disabled', 'true');
			$( '.loading',form ).fadeOut(200, function(){
				$(this).tiny_spinner().fadeIn(300, function(){
					var post_url = form.attr('action');
					$.post(post_url, { submit:1, name:sname, email:semail, aj:1 }, function(resp){
						resp = resp.split('|');
						if ( resp[0] == 'ok' ) {
						   $( '.loading',form ).removeClass('error').html('Thanks for subscribing!');
						}
						else {
							$( '.loading',form ).addClass('error').html(resp[1]);
						}
						$('#button_subscribe_home').removeAttr('disabled');
					})

				});
			})
			
		}
		return false;
	}

	this.animateNav = function() {
		var $trigger_link = $(this);
		$trigger_link
			.unbind('mouseout')
			.unbind('mouseover')
			.css('position', 'static')

		var trigger_pos = findPos( $trigger_link.get(0) );
		var cloud_left_pos = trigger_pos[0] + ($trigger_link.width() / 2) - 40 //position of the div that triggered + half its width - half the width of the cloud, itself
		var cloud_top_pos = 154 - $trigger_link.height();

		$('.cloud', $trigger_link)
			.addClass('anim_start')
			.css('top', (cloud_top_pos)+'px')
			.css('left', (cloud_left_pos)+'px')
			.fadeIn(20, function(){
				$(this).animate({ top: '110px', opacity: '0' }, 600, function() {
					$(this)
						.css('position', 'absolute')
						.css('top', '5px')
						.css('left', '7px')
						.css('display', 'none')
						.css('opacity', 1)
						.addClass('anim_done')
					$trigger_link.mouseover( Main.animateNav ) 
				});
			})		
	}
}//Main


$(function() {//on doc ready (put it after Main is defined above or might throw error)
	Main.init();
});

function checkOrderForm(form) {
	var quant_val = $('#quantity', form).val().trim();
	if ( quant_val == '' || quant_val==0 || isNaN(quant_val) ) {
		$('.msg', form).html('Please enter a quantity.').fadeIn(200);
		$('#quantity', form).focus();
		return false;
	}
	else {
		// BRAIN:
		$(':input:disabled', form).removeAttr('disabled');
		$('#button_add_to_cart', form).attr('disabled','true');
		return true;
	}
}


function move_in(img_name,img_src) {
	document[img_name].src=img_src;
}

function move_out(img_name,img_src) {
	document[img_name].src=img_src;
}

function CJL_saveLink(anchorId, linkTitle, url, bookmarkText, tabText)
{
   function set(innerHTML, href)
   {
      elem = document.getElementById(anchorId);

      elem.innerHTML = innerHTML;

      elem.href = "javascript:" + href;
   }

   function quote(s)
   {
      return "'" + s + "'";
   }


   url = quote(url ? url : location.href);
   linkTitle = quote(linkTitle ? linkTitle : document.title);

   if( window.external &&  /Win/.test(navigator.userAgent) )
   {
      set(
          bookmarkText ? bookmarkText : "Bookmark Page",
          "external.AddFavorite(" + url + "," + linkTitle + ")"
         );
   }
   else if( window.sidebar && sidebar.addPanel )
   {
      set(
          tabText ? tabText : "Add to Sidebar",
          "sidebar.addPanel(" + linkTitle + "," + url + ",'')"
         );
   }
}

//leaving dummy functions here for old calls
function MM_preloadImages() { //v3.0
}

function MM_findObj(n, d) { //v4.01
}

function MM_swapImgRestore() { //v3.0
}

function MM_swapImage() { //v3.0
}
