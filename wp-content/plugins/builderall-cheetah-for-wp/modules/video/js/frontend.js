(function($) {

	$(function() {
		$('.ba-cheetah-embed-video').fitVids();

		// Fix multiple videos where autoplay is enabled.
		if (( $('.ba-cheetah-module-video .ba-cheetah-wp-video video').length > 1 ) && typeof $.fn.mediaelementplayer !== 'undefined' ) {
			$('.ba-cheetah-module-video .ba-cheetah-wp-video video').mediaelementplayer( {pauseOtherPlayers: false} );
		}

	});

	/**
	 * Class for the Video Module

	 */

	BACheetahVideo = function( settings ){

		// Set params
		this.nodeID           	 = settings.id;
		this.nodeClass           = '.ba-cheetah-node-' + settings.id;
		this.wrapperClass        = this.nodeClass + ' .ba-cheetah-video';
		this.sticky	 		     = settings.sticky
		this.dismissed			 = false

		this._initVideo();
        this._initStickyOnScroll();

	};

	BACheetahVideo.prototype = {

		_initVideo: function(){
            var origTop = $( this.nodeClass ).offset().top;

            $( this.nodeClass ).attr( 'data-orig-top', origTop );
			$( this.nodeClass ).find( '.ba-cheetah-video-close-button' ).on('click', $.proxy(this._closeStickyByUser, this))
        },

		_closeStickyByUser: function() {
			this.dismissed = true
			this._removeSticky()
		},

        _makeSticky: function(){

			[property_y, property_x] = this.sticky.position.split('_')

            $( this.nodeClass ).addClass( 'ba-cheetah-video-sticky' );
            $( this.nodeClass ).css( property_x, this.sticky.distance_x );
            $( this.nodeClass ).css( property_y, this.sticky.distance_y );

            $( this.nodeClass ).css( 'height', 'unset' );
            $( this.nodeClass ).css( 'width', this.sticky.width );

			// hide fixed header
			$('.ba-header-is-fixed').css('visibility', 'hidden');

        },

        _removeSticky: function(){
			$( this.nodeClass ).removeClass( 'ba-cheetah-video-sticky' );
            $( this.nodeClass ).css( 'height', '' );
            $( this.nodeClass ).css( 'width', '' );

			// show fixed header
			$('.ba-header-is-fixed').css('visibility', 'visible');
        },

        _initStickyOnScroll: function(){

			if (this.sticky.mobile == 'no' && BACheetahLayout._isMobile()) {
				return;
			}

            $( window ).on( 'scroll', $.proxy( function( e ) {

				if (this.dismissed) {
					return
				}

    			var win = $( window ),
    				winTop = win.scrollTop(),
                    nodeTop = $( this.nodeClass ).data( 'orig-top' );
                    isSticky = $( this.nodeClass ).hasClass( 'ba-cheetah-video-sticky' );

    			if ( winTop >= nodeTop ) {
                    if ( ! isSticky ){
                        this._makeSticky();
                    }
    			} else if ( isSticky ) {
                    this._removeSticky();
    			}

    		}, this ) );
        },

	};
})(jQuery);
