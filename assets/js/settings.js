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

	$( window ).load( function() {
		$( '#wpbody' ).css( 'display', 'block' );
	});

	$( '.subway_redirect_wp_admin-option' ).parents( '.form-table' ).prev().addClass( 'subway_redirect_options_title' );

	var $elements_affected = [
 		'.subway_public_post_deprecated-option',
 	];

 	subway_is_checkbox_checked( '#subway_is_public', $elements_affected, 'hidden' );

	subway_toggle_use_custom_page();
	subway_toggle_use_custom_url();
	subway_toggle_use_default();

	$("#subway_use_page").on('click', function(){
		subway_toggle_use_custom_page();
	});

	$("#subway_use_custom_url").on('click', function(){
		subway_toggle_use_custom_url();
	});

	$("#subway_use_default").on('click', function(){
		subway_toggle_use_default();
	});

	function subway_toggle_use_default() {
		
		if( $('#subway_use_default').is(':checked')) {

			$('#subway_redirect_custom_url_option_section').addClass('hidden');
			$('#subway_redirect_default_option_section').removeClass('hidden');
			$('#subway_redirect_page_option_section').addClass('hidden');
		}

		return;
	}

	function subway_toggle_use_custom_url() {

		if( $('#subway_use_custom_url').is(':checked')) {

			$('#subway_redirect_custom_url_option_section').removeClass('hidden');
			$('#subway_redirect_default_option_section').addClass('hidden');
			$('#subway_redirect_page_option_section').addClass('hidden');

		}

		return;
	}

	function subway_toggle_use_custom_page() {

		if( $('#subway_use_page').is(':checked')) {

			$('#subway_redirect_custom_url_option_section').addClass('hidden');
			$('#subway_redirect_default_option_section').addClass('hidden');
			$('#subway_redirect_page_option_section').removeClass('hidden');
		}

		return;
	}

	function subway_is_checkbox_checked( selector, elements_affected, the_class ) {

		if ( '' !== selector && '' !== elements_affected ) {

			if ( $( selector ).is( ':checked' ) ) {
				
				$.each( elements_affected, function( index, the_element ) {
					$( the_element ).addClass( the_class );
				});
			}

			$( selector ).change( function() {

				if ( $( selector ).is( ':checked' ) ) {

					$.each( elements_affected, function( index, the_element ) {
						$( the_element ).addClass( the_class );
					});
				} else {

					$.each( elements_affected, function( index, the_element ) {
						$( the_element ).removeClass( the_class );
					});
				}
			});
		}

		return;
	}

});
