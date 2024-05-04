(function( $ ) {

	/**
	 * Enable/Disable usage stats helper.
	 *

	 */
	var BACheetahUsage = {

		init: function() {
			BACheetahUsage._fadeToggle()
			BACheetahUsage._enableClick()
			BACheetahUsage._disableClick()
		},
		_fadeToggle: function() {
			$( 'a.stats-info' ).click( function( e ) {
				e.preventDefault();
				$( '.stats-info-data' ).fadeToggle()
			})
		},
		_enableClick: function() {
			$( '.buttons span.enable-stats' ).click( function( e ) {

				nonce = $(this).closest('.buttons').find('#_wpnonce').val()

				data = {
					'action'  : 'ba_cheetah_usage_toggle',
					'enable'  : 1,
					'_wpnonce': nonce
				}
				BACheetahUsage._doAjax( data )
			})
		},
		_disableClick: function() {
			$( '.buttons span.disable-stats' ).click( function( e ) {

				nonce = $(this).closest('.buttons').find('#_wpnonce').val()

				data = {
					'action'  : 'ba_cheetah_usage_toggle',
					'enable'  : 0,
					'_wpnonce': nonce
				}
				BACheetahUsage._doAjax( data )
			})
		},
		_doAjax: function( data ) {
			$.post(ajaxurl, data, function(response) {
				BACheetahUsage._close()
			});
		},

		_close: function() {
			$( '.ba-cheetah-usage').closest('.notice').fadeToggle()
		}
	};

	$( function() {
		BACheetahUsage.init();
	});

})( jQuery );
