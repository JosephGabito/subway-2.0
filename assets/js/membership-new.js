jQuery(document).ready(function($){
	'use strict';
	$('#publish-product').on('click', function(e){
		e.preventDefault();
		var element = $(this);
		element.attr('disabled', 'disabled');
		$.ajax( {
		    url: subway_api_settings.root + 'subway/v1/membership/new-product',
		    method: 'POST',
		    beforeSend: function ( xhr ) {
		        xhr.setRequestHeader( 'X-WP-Nonce', subway_api_settings.nonce );
		    },
		    data:{
		        'title' : $('#input-title').val(),
		        'description' : $('#input-description').val(),
		    }
		} ).done( function ( response ) {
		    console.log( response );
		    element.removeAttr('disabled');
		} );
	});
});