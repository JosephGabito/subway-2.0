jQuery(document).ready( function($) {
	
	'use strict';

	$('#update-product').on('click', function(e) {

		e.preventDefault();
		
		var element = $(this);
		
		element.attr('disabled', 'disabled');

		$.ajax( {
		    url: subway_api_settings.root + 'subway/v1/membership/update-product',
		    method: 'POST',
		    beforeSend: function ( xhr ) {
		        xhr.setRequestHeader( 'X-WP-Nonce', subway_api_settings.nonce );
		    },
		    data:{
		        'id' : $('#input-id').val(),
		        'title' : $('#input-title').val(),
		        'description' : $('#input-description').val(),
		    }
		} ).success( function ( response ) 
		{
			
			if ( response.is_error )
			{
				alert( response.message );
			} else 
			{
				alert( response.message );
			}

		} ).error( function( response, status, message ) 
		{

			console.log( message );


		}).done( function(){
			element.removeAttr('disabled');
		});
	});
});