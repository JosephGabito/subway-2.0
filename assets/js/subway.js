jQuery(document).ready(function($){

	"use strict";

	$(window).load( function(){

		var $input = $('.subway-login-form__form p > input');

		if ( $input.val().length >= 1 ) {
			$input.prev('label').addClass('inactive');
		}

	});

	$('.subway-login-form__form p > input').focusin( function(){

		$(this).prev('label').addClass('inactive');

	}).focusout(function(){

		if ( $(this).val().length < 1 ) {

			$(this).prev('label').removeClass('inactive');
			
		}
	});


	/**
	 * Login form submission.
	 */
	var $subway_login_form = $('.subway-login-form__form form#loginform');
	
	$subway_login_form.on('submit', function( event ){
		// Prevent form submission.
		event.preventDefault();
		var subway_preloader = '<div id="subway_preloader"><div class="subway-ripple-css" style="transform:scale(0.14);"><div></div><div></div></div></div>';
		var http_params = {
			url: subway_config.ajax_url + '?action=subway_logging_in',
			data: $(this).serialize(),
			type: 'POST',
			dataType: 'json' ,
			beforeSend: function() {
				$subway_login_form.find('#wp-submit').attr('disabled', true);
				$('#subway_preloader').remove();
				$subway_login_form.find('p.login-submit').append( subway_preloader );
			}
		};

		var subway_login_request = $.ajax( http_params );

		subway_login_request.done( function( response ) {

			if ( 'error' === response.type ) {
				$('.subway-login-form-message').html( '<div id="message" class="error">' + response.message + '</div>' );
			} else {
				$('.subway-login-form-message').html( '<div id="message" class="success">' + response.message + '</div>' );
			}

			$subway_login_form.find('#wp-submit').removeAttr('disabled');
			$('#subway_preloader').remove();

		});

		subway_login_request.fail( function() {
			$('.subway-login-form-message').html( subway_config.login_http_error );
			$('#subway_preloader').remove();
		});

	});

});