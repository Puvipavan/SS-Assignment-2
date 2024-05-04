jQuery( document ).ready( function( $ ) {

	$.each( BACheetahAdminPointersConfig.pointers, function( i, pointer ) {
		
		var options = $.extend( pointer.options, {
			pointerClass: 'wp-pointer ba-cheetah-admin-pointer',
			close: function() {
				$.post( BACheetahAdminPointersConfig.ajaxurl, {
					pointer: pointer.id,
					action: 'dismiss-wp-pointer'
				} );
			}
		} );

		$( pointer.target ).pointer( options ).pointer( 'open' );
	} );
} );
