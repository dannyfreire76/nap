// 2/22/2009: changed popCID call and function
// 5/26/2009: changed code for logging in while in cart

var hBG = new Image();
hBG.src = "/images/header_bg.jpg";

var fBG = new Image();
fBG.src = "/images/footer_bg.jpg";

var Main = new function() {

	this.init = function() {
		Main.curr_loc = window.location + '';
		this.curr_loc = this.curr_loc.substring( this.curr_loc.indexOf('.com')+4 );
		Main.special_hgt_correct = -256;
		Main.special_wdh_correct = -206;
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

		//instead of scrubbing all the files for hard-coded font tags, override them with css
		/*
		$("font[@size*=+2]").each( function() {
			$(this).addClass('two');
		} );
		$("font[@size=4]").each( function() {
			$(this).addClass('four');
		} );
		$("font[@size=5]").each( function() {
			$(this).addClass('five');
		} );
		$("font[@size*=-1]").each( function() {
			$(this).addClass('smaller');
		} );
		*/

		$('.cid_link').click(Main.popCID);

		Main.bindResizeForForms();

		Main.orig_content_height =  $('#container').height();

		Main.checkImg = setInterval("Main.checkImgLoaded();", 100);
		/*
		if ( Main.curr_loc == '/' || Main.curr_loc == '' || Main.curr_loc == '/index.php') {//home page
			var img1a = new Image();
			img1a.src = '/images/game.gif';
			var img2a = new Image();
			img2a.src = '/images/close.gif';
			setTimeout( "Main.openSpecial()", 1700 );//even though this is onload, some browsers still need a moment to calculate height of window
		}
		*/
	}

	this.checkImgLoaded = function() {//only resixe window when the images ahve actually loaded
		objImg = new Image();  
		objImg.src = $('#current_base').val() + 'images/header_bg.jpg';  
		
		objImg2 = new Image();  
		objImg2.src = $('#current_base').val() + 'images/footer_bg.jpg';  

		objImg3 = new Image();  
		objImg3.src = $('#current_base').val() + 'images/logo.png';  

		if( objImg.complete && objImg2.complete && objImg3.complete)  {
			clearInterval(Main.checkImg);
			setTimeout( "Main.resizeContent()", 200 );//even though this is onload, some browsers still need a moment to calculate height of window    
		}
	}

	this.resizeContent = function() {
		$(window).unbind('resize', Main.resizeContent);
		var windowHeight = parseInt( $(window).height() );
		
		var subMenuHeight = parseInt( $('#sub_menu').height() )
				+ Main.getCssAtrr('#sub_menu', 'border-top-width')
				+ Main.getCssAtrr('#sub_menu', 'border-bottom-width')
				+ Main.getCssAtrr('#sub_menu', 'padding-top')
				+ Main.getCssAtrr('#sub_menu', 'padding-bottom');

		var footerHeight = parseInt( $('#footer').height() )
				+ Main.getCssAtrr('#footer', 'border-top-width')
				+ Main.getCssAtrr('#footer', 'border-bottom-width')
				+ Main.getCssAtrr('#footer', 'padding-top')
				+ Main.getCssAtrr('#footer', 'padding-bottom');
				
		var headerHeight = parseInt( $('#header').height() )
			 + Main.getCssAtrr('#header', 'border-top-width')
				+ Main.getCssAtrr('#header', 'border-bottom-width')
				+ Main.getCssAtrr('#header', 'padding-top')
				+ Main.getCssAtrr('#header', 'padding-bottom'); 

		var containerExtra = Main.getCssAtrr('#container', 'border-top-width')
			+ Main.getCssAtrr('#container', 'border-bottom-width')
			+ Main.getCssAtrr('#container', 'padding-top')
			+ Main.getCssAtrr('#container', 'padding-bottom');
		
		var new_height = (windowHeight - headerHeight - footerHeight - subMenuHeight - containerExtra );

		if ( new_height!= $('#container').height()) {
			Main.thisDelay = 300;

			if ( new_height - $('#container').height() > 200 ) {
				Main.thisDelay = 1000;
			}

			$('#container').animate( {height: new_height + "px"}, Main.thisDelay, function(){
				setTimeout(" $(window).bind( 'resize', Main.resizeContent )", 200);
			} );
		}
	 }

	 this.getCssAtrr = function(elem, attr){
		 var val = 0;
		 if (   !isNaN( parseInt($(elem).css(attr)) )   ) {
			 val = parseInt( $(elem).css(attr) )
		 }
		 return val;
	 }

	this.reCenter = function($el, h_correct, v_correct) {
	    var dims = findWindowCenter(h_correct, v_correct);	    			
		//var new_left = (dims[0]/2)-370;
		//var new_top = (dims[1]/2)-165;
		var new_left = dims[0];
		var new_top = dims[1];

		$el.css('left', new_left).css('top', new_top);			
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
						window.location.href = $('#current_base').val()+'store/';
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
			.css('left', ( cid_pos[0]+64 )+'px')
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
			$('#forgotpw', form_id+'_actual').unbind().click(Main.switchToPW);
			$('#nevergotpw', form_id+'_actual').unbind().click(Main.switchToPW);
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
				$('#'+thisID).html('RETAILER LOGIN')				
			}

			$('#'+thisID)
				.unbind()
				.bind('click', Main.toggleMenu);
			if ( $('#'+thisID).attr("class").indexOf('open') != -1 ) {//should be open, so show it
				$('#'+thisID+'_form').fadeIn(300, function(){
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
				$(this).small_spinner_on_dkgreen().fadeIn(300, function(){
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
		var forget_url = $('#forgotpw', $(this).parents('form')).attr('link');
		var wrapper = $(this).parents('.nav_form_wrapper');
		wrapper.fadeOut(200, function() {
			$('.nav_form', this).load( forget_url, function(){
				if ( wrapper.attr('id')=='retailer_login_form' ) {
					Main.rePosition( $('#retailer_login') );
					$('#retailer_login_form').fadeIn(200, function(){
						$('#retailer_login').html('FORGOT PASSWORD');
						$('input:first', this).focus();
						Main.initRetailerLogin();
					});
				}
				else {
					Main.rePosition( $('#login') );
					$('#login_form').fadeIn(200, function(){
						//$('#login').html('Forgot Password')
						$('input:first', this).focus();
						Main.initLogin();
					});
				}
			} );
		})
	}

	this.rePosition = function($elem) {
		var elemID = $elem.attr('id');

		if ( $('#'+elemID+'_form').is(":visible") ) {//if it's visible, we need to wait a second while the container resizes
			setTimeout("Main.rePositionMain( $('#"+elemID+"') )", Main.thisDelay); 
		} else {
			Main.rePositionMain($elem);
		}
	}

	this.rePositionMain = function($elem) {
		var link_id = $elem.attr("id");
		var new_form_pos = findPos( $('#'+link_id).get(0) )
		elem_width = $('#'+link_id).width();
		$('#'+link_id+'_form').each( function(){
			if ( $(this).attr("class").indexOf('header_form') != -1 ) {
				$(this).css( 'top', ( new_form_pos[1]+$('#'+link_id).height()+3 )+'px' )

				var extraLeft = 15;
				if ( navigator.userAgent.toLowerCase().indexOf("msie") != -1 ) {
					extraLeft = 16;

					if ( navigator.appVersion.indexOf("6.0") != -1 ) {
						extraLeft += 1;
					}
				}

				$(this).css('left', (new_form_pos[0]-($(this).width()/2)+(elem_width/2) + extraLeft )+'px' )
			}
			else {
				$(this).css( 'top', ( new_form_pos[1]-$(this).height()-14 )+'px' )
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
				$(this).small_spinner_on_dkgreen().fadeIn(300, function(){
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
										$(this).Pulsate(300, 4)
										$('.header:first').ScrollTo(400);
										Main.switchToRetailerLogout();
									} )
								}
							}

							if ( form.attr('id')=='login_actual' ) {
								//BRAIN:
								//output the rest of the response because it contains the other servers images (and having them in the AJAX response doesn't actually execute them)
								var resp_arr = resp.split('|');
								var imagesStr = resp_arr[1];
								$('body').append('<div id="partnerSiteImgs" class="no_display">'+imagesStr+'</div>');
								//we use the fadeIn to make sure that the image executes on the other servers before we reload the submenu
								$('#partnerSiteImgs').fadeIn(200, function(){
									if ( Main.curr_loc.indexOf('/cart.php') != -1 || Main.curr_loc.indexOf('store/step') != -1 ) {//in cart or checkout, reload it
										// START 5/26/2009
										$('#sub_menu').fadeOut( 200, function() {
											$(this).html('<img src="/images/icons/iconIndicator.gif" /> <span class="error">Your cart will momentarily be updated with items you previously saved.  Please double-check it before completing your order.</span>')
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
											$(this).Pulsate(300, 4);
											$('.header:first').ScrollTo(400);
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
				else if ( Main.curr_loc.indexOf('store/confirm.php') != -1 || Main.curr_loc.indexOf('customer/') != -1 ) {//in checkout, get out
					window.location = $('#current_base').val()+'store/';
				}
				else {
					$('.header:first').ScrollTo(400);
					var submenu_url = $('#current_base').val()+'includes/head1.php?action=submenu';
					$('#sub_menu').load( submenu_url, function() {
						$(this).Pulsate(300, 4, function(){
							Main.initLogin();
							Main.prepFormLinks();
						})
					})
				}
			} );
		})
	}

	this.logOutRetailer = function() {
		var post_url = $('#current_base').val()+'retailer_login.php';
		$.post(post_url, { action:'logout' }, function(resp){
			if ( Main.curr_loc.indexOf('/store/') != -1 || Main.curr_loc.indexOf('/wc/') != -1 ) {//in store or wc
				window.location = $('#current_base').val()+'store/';
			}
			else {
				$('.header:first').ScrollTo(400);
				var submenu_url = $('#current_base').val()+'includes/head1.php?action=submenu';
				$('#sub_menu').load( submenu_url, function() {
					$(this).Pulsate(300, 4, function(){
						Main.initLogin();//have to do this too because this area changes on logout
						Main.initRetailerLogin();
						Main.prepFormLinks();
					})
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
				
			//$(this).addClass('open');
			if ( $('#'+menu_id+'_form').attr("class").indexOf('header_form') != -1 ) {
				$(this).animate( { className: 'sub_menuaonlink', background: "transparent url(/images/contentBG.png)"}, 300);
			}
			else {
				$(this).addClass('on')
			}

			var form_pos = findPos( $(this).get(0) );
			elem_width = $(this).width();
			elem_height = $(this).height();

			if ( menu_id=='retailer_login' ) {
				Main.initRetailerLogin();
			}

			Main.rePosition( $('#'+menu_id) );
			setTimeout( "Main.menuFadeIn('"+menu_id+"')", 200 );
		}
		else {
			Main.closeMenuForm(menu_id);
		}
		return false;
	}

	this.menuFadeIn = function() {
		$('#'+menu_id+'_form').fadeIn(300, function(){
			$('#'+menu_id+'_form input:first').focus();
			$('#' + menu_id).addClass('open');
		});
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

   if(window.external &&  /Win/.test(navigator.userAgent) )
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
