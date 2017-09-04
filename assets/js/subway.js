/**
 * This file is part of the Subway WordPress Plugin Package.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Subway
 */

jQuery(document).ready(function($) {

    "use strict";

    $(window).load(function() {

        var $input = $('.subway-login-form__form p > input');

        if ($input.val()) {
            if ($input.val().length >= 1) {
                $input.prev('label').addClass('inactive');
            }
        }

    });

    $('.subway-login-form__form p > input').focusin(function() {

        $(this).prev('label').addClass('inactive');

    }).focusout(function() {

        if ($(this).val().length < 1) {

            $(this).prev('label').removeClass('inactive');

        }
    });


    /**
     * Login form submission.
     */
    var $subway_login_form = $('.subway-login-form__form form#loginform');

    $subway_login_form.on('submit', function(event) {

        // Prevent form submission.
        event.preventDefault();
        var subway_preloader = '<div id="subway_preloader"><div class="subway-ripple-css" style="transform:scale(0.14);"><div></div><div></div></div></div>';
        var http_params = {
            url: subway_config.ajax_url + '?action=subway_logging_in',
            data: $(this).serialize(),
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                // Disable the button.
                $subway_login_form.find('#wp-submit').attr('disabled', true);
                // Remove the preloader.
                $('#subway_preloader').remove();
                // Clear previous any error messages.
                $('.subway-login-form-message #message').html('');
                // Renew the preloader.
                $subway_login_form.find('p.login-submit').append(subway_preloader);
            }
        };

        var subway_login_request = $.ajax(http_params);

        subway_login_request.done(function(response) {

            if ('error' === response.type) {
                $('.subway-login-form-message').html('<div id="message" class="error">' + response.message + '</div>');
                if ( typeof grecaptcha !== 'undefined' ) {
                    grecaptcha.reset();
                }
            } else {
                if (0 !== response) {
                    $('.subway-login-form-message').html('<div id="message" class="success">' + response.message + '</div>');
                    document.location = response.redirect_url;
                }
            }

            $subway_login_form.find('#wp-submit').removeAttr('disabled');

            $('#subway_preloader').remove();

        });

        subway_login_request.fail(function(response) {

            $('.subway-login-form-message').html(subway_config.login_http_error);

            $('#subway_preloader').remove();

            if ( typeof grecaptcha !== 'undefined' ) {
                grecaptcha.reset();
            }

        });

    });

});
