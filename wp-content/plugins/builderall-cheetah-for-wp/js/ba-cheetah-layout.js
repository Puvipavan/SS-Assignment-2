(function($){

	if(typeof BACheetahLayout != 'undefined') {
		return;
	}

	/**
	 * Helper class with generic logic for a builder layout.
	 *
	 * @class BACheetahLayout

	 */
	BACheetahLayout = {

		/**
		 * Initializes a builder layout.
		 *

		 * @method init
		 */
		init: function()
		{
			// Destroy existing layout events.
			BACheetahLayout._destroy();

			// Init CSS classes.
			BACheetahLayout._initClasses();

			// Init backgrounds.
			BACheetahLayout._initBackgrounds();

			// Only init if the builder isn't active.
			if ( 0 === $('.ba-cheetah-edit').length ) {

				// Init module animations.
				BACheetahLayout._initModuleAnimations();

				if (!BACheetahLayout._isMobile()) {
					// Init module scroll animations.
					BACheetahLayout._initModuleScrollAnimations();
				}

				// Init anchor links.
				BACheetahLayout._initAnchorLinks();

				// Init the browser hash.
				BACheetahLayout._initHash();

				// Init forms.
				BACheetahLayout._initForms();

				// Fixed header
				BACheetahLayout.__fixedHeader();

				// Pixel
				BACheetahLayout._initPixel();
			}

		},

		__fixedHeader: function () {
			var header = document.querySelector(".ba-header-is-fixed");
			window.onload = function () {
				var header_container = document.querySelector(".ba-header-container");
				if (header) {
					header.classList.add("ba-cheetah-fixed");
					header_container.style.paddingBottom = header.clientHeight + 'px';
				}
			}
		},

		_initPixel: function () {
			if (BACheetahLayoutConfig.tracking.pixel.id) {
				$(document).on('click', '[data-pixel-event]', function(e){
					e.stopPropagation()
					const event = $(this).attr('data-pixel-event');
					if (typeof fbq == 'function' && event) {
						fbq('trackCustom', event)
					}
				});
			}
		},

		/**
		 * Public method for refreshing Wookmark or MosaicFlow galleries
		 * within an element.
		 *

		 * @method refreshGalleries
		 */
		refreshGalleries: function( element )
		{
			var $element  = 'undefined' == typeof element ? $( 'body' ) : $( element ),
				mfContent = $element.find( '.ba-cheetah-mosaicflow-content' ),
				wmContent = $element.find( '.ba-cheetah-gallery' ),
				mfObject  = null;

			if ( mfContent ) {

				mfObject = mfContent.data( 'mosaicflow' );

				if ( mfObject ) {
					mfObject.columns = $( [] );
					mfObject.columnsHeights = [];
					mfContent.data( 'mosaicflow', mfObject );
					mfContent.mosaicflow( 'refill' );
				}
			}
			if ( wmContent ) {
				wmContent.trigger( 'refreshWookmark' );
			}
		},

		/**
		 * Public method for refreshing Masonry within an element
		 *

		 * @method refreshGridLayout
		 */
		refreshGridLayout: function( element )
		{
			var $element 		= 'undefined' == typeof element ? $( 'body' ) : $( element ),
				msnryContent	= $element.find('.masonry');

			if ( msnryContent.length )	{
				msnryContent.masonry('layout');
			}
		},

		/**
		 * Public method for reloading BxSlider within an element
		 *

		 * @method reloadSlider
		 */
		reloadSlider: function( element )
		{
			var $element 	= 'undefined' == typeof element ? $( 'body' ) : $( element ),
				bxContent	= $element.find('.bx-viewport > div').eq(0),
				bxObject   	= null;

			if ( bxContent.length ) {
				bxObject = bxContent.data( 'bxSlider');
				if ( bxObject ) {
					bxObject.reloadSlider();
				}
			}
		},

		/**
		 * Public method for resizing WP audio player
		 *

		 * @method resizeAudio
		 */
		resizeAudio: function( element )
		{
			var $element 	 	= 'undefined' == typeof element ? $( 'body' ) : $( element ),
				audioPlayers 	= $element.find('.wp-audio-shortcode.mejs-audio'),
				player 		 	= null,
				mejsPlayer 	 	= null,
				rail 			= null,
				railWidth 		= 400;

			if ( audioPlayers.length && typeof mejs !== 'undefined' ) {
            	audioPlayers.each(function(){
	            	player 		= $(this);
	            	mejsPlayer 	= mejs.players[player.attr('id')];
	            	rail 		= player.find('.mejs-controls .mejs-time-rail');
	            	var innerMejs = player.find('.mejs-inner'),
	            		total 	  = player.find('.mejs-controls .mejs-time-total');

	            	if ( typeof mejsPlayer !== 'undefined' ) {
	            		railWidth = Math.ceil(player.width() * 0.8);

	            		if ( innerMejs.length ) {

		            		rail.css('width', railWidth +'px!important');
		            		//total.width(rail.width() - 10);

		            		mejsPlayer.options.autosizeProgress = true;

		            		// webkit has trouble doing this without a delay
							setTimeout(function () {
								mejsPlayer.setControlsSize();
							}, 50);

			            	player.find('.mejs-inner').css({
			            		visibility: 'visible',
			            		height: 'inherit'
			            	});
		            	}
		           	}
	            });
	        }
		},

		/**
		 * Public method for preloading WP audio player when it's inside the hidden element
		 *

		 * @method preloadAudio
		 */
		preloadAudio: function(element)
		{
			var $element 	 = 'undefined' == typeof element ? $( 'body' ) : $( element ),
				contentWrap  = $element.closest('.ba-cheetah-accordion-item'),
				audioPlayers = $element.find('.wp-audio-shortcode.mejs-audio');

			if ( ! contentWrap.hasClass('ba-cheetah-accordion-item-active') && audioPlayers.find('.mejs-inner').length ) {
				audioPlayers.find('.mejs-inner').css({
					visibility : 'hidden',
					height: 0
				});
			}
		},

		/**
		 * Public method for resizing slideshow momdule within the tab
		 *

		 * @method resizeSlideshow
		 */
		resizeSlideshow: function(){
			if(typeof YUI !== 'undefined') {
				YUI().use('node-event-simulate', function(Y) {
					Y.one(window).simulate("resize");
				});
			}
		},

		/**
		 * Public method for reloading an embedded Google Map within the tabs or hidden element.
		 *

		 * @method reloadGoogleMap
		 */
		reloadGoogleMap: function(element){
			var $element  = 'undefined' == typeof element ? $( 'body' ) : $( element ),
			    googleMap = $element.find( 'iframe[src*="google.com/maps"]' );

			if ( googleMap.length ) {
			    googleMap.attr( 'src', function(i, val) {
			        return val;
			    });
			}
		},

		/**
		 * Instantiates a new popup and sets its behavior based on the settings
		 *
		 * @param {String} trigger trigger event to open popup (click, scroll, timer)
		 * @param {String} content popup html markup
		 * @param {String} target element thats open the popup when clicked (click trigger)
		 * @param {String} width popup width eg. 700px
		 * @param {String} backdrop backdrop color
		 * @param {Object} settings additional settings for scroll and timer triggers
		 */
		makePopup: function(trigger, content, target = null, width = null, backdrop, settings){

			console.log('popup instance', {trigger, content: content.length, target, width, backdrop, settings})

			let opened = false;

			const config = {
				popup_timer_minutes: 0,
				popup_timer_seconds: 0,
				popup_scroll_point: 0
			}

			Object.keys(config).forEach(s => config[s] = settings.hasOwnProperty(s) ? parseInt(settings[s]) : 0)

			function BACheetahModalOpen() {

				// rebind scripts because initially dom content is a js variable, so delegations does not work
				BACheetahLayout._rebindScriptByPostID(jQuery(content).attr('data-post-id'))

				opened = true

				Swal.fire({
					padding: 0,
					showConfirmButton: false,
					showCancelButton: true,
					cancelButtonText: 'Close',
					width: width,
					backdrop: backdrop,
					html: content
				})
			}

			switch(trigger){
				case 'click':
					$(target).on('click', BACheetahModalOpen)
					break;

				case 'timer':
					setTimeout(BACheetahModalOpen, (
						(parseInt(config.popup_timer_minutes * 60)) + parseInt(config.popup_timer_seconds)) * 1000
					)
					break;

				case 'scroll':
					document.addEventListener('scroll', function(){
						let element = document.body;
						let parent = element.parentNode;
						let position = (element.scrollTop || parent.scrollTop) / (parent.scrollHeight - parent.clientHeight ) * 100;
						if (!opened && Math.round(position) == config.popup_scroll_point) {
							BACheetahModalOpen()
						}
					})
					break;

				case 'exit':
					document.addEventListener('mouseout', evt => {
						if (!opened && (evt.toElement === null || typeof evt.toElement === 'undefined') && evt.relatedTarget === null && evt.target.tagName !== 'INPUT') {
							BACheetahModalOpen()
						}
					});
					break;
			}
		},

		_rebindScriptByPostID(postID = null)
		{
			if (!postID) return;

			const id = 'ba-cheetah-layout-' + postID + '-js'
			const selector = 'script[id="' + id + '"]';
			const src = $(selector).attr('src')

			setTimeout(function() {
				$(selector).remove();
				$('<script>').attr('src', src).attr('id', id).appendTo('head');
			}, 10)
		},

		/**
		 * Unbinds builder layout events.
		 *

		 * @access private
		 * @method _destroy
		 */
		_destroy: function()
		{
			var win = $(window);

			win.off('scroll.ba-cheetah-bg-parallax');
			win.off('resize.ba-cheetah-bg-video');
		},

		/**
		 * Checks to see if the current device has touch enabled.
		 *

		 * @access private
		 * @method _isTouch
		 * @return {Boolean}
		 */
		_isTouch: function()
		{
			if(('ontouchstart' in window) || (window.DocumentTouch && document instanceof DocumentTouch)) {
				return true;
			}

			return false;
		},

		/**
		 * Checks to see if the current device is mobile.
		 *

		 * @access private
		 * @method _isMobile
		 * @return {Boolean}
		 */
		_isMobile: function()
		{
			return /Mobile|Android|Silk\/|Kindle|BlackBerry|Opera Mini|Opera Mobi|webOS/i.test( navigator.userAgent );
		},

		/**
		 * Initializes builder body classes.
		 *

		 * @access private
		 * @method _initClasses
		 */
		_initClasses: function()
		{
			var body = $( 'body' ),
				ua   = navigator.userAgent;

			// Add the builder body class.
			if ( ! body.hasClass( 'archive' ) && $( '.ba-cheetah-content-primary' ).length > 0 ) {
				body.addClass('ba-cheetah');
			}

			// Add the builder touch body class.
			if(BACheetahLayout._isTouch()) {
				body.addClass('ba-cheetah-touch');
			}

			// Add the builder mobile body class.
			if(BACheetahLayout._isMobile()) {
				body.addClass('ba-cheetah-mobile');
			}

			if ( $(window).width() < BACheetahLayoutConfig.breakpoints.small ) {
				body.addClass( 'ba-cheetah-breakpoint-small' );
			}

			if ( $(window).width() > BACheetahLayoutConfig.breakpoints.small && $(window).width() < BACheetahLayoutConfig.breakpoints.medium ) {
				body.addClass( 'ba-cheetah-breakpoint-medium' );
			}

			if ( $(window).width() > BACheetahLayoutConfig.breakpoints.medium ) {
				body.addClass( 'ba-cheetah-breakpoint-large' );
			}

			// IE11 body class.
			if ( ua.indexOf( 'Trident/7.0' ) > -1 && ua.indexOf( 'rv:11.0' ) > -1 ) {
				body.addClass( 'ba-cheetah-ie-11' );
			}
		},

		/**
		 * Initializes builder node backgrounds that require
		 * additional JavaScript logic such as parallax.
		 *

		 * @access private
		 * @method _initBackgrounds
		 */
		_initBackgrounds: function()
		{
			var win = $(window);

			// Init parallax backgrounds.
			if($('.ba-cheetah-row-bg-parallax').length > 0 && !BACheetahLayout._isMobile()) {
				BACheetahLayout._scrollParallaxBackgrounds();
				BACheetahLayout._initParallaxBackgrounds();
				win.on('scroll.ba-cheetah-bg-parallax', BACheetahLayout._scrollParallaxBackgrounds);
			}

			// Init video backgrounds.
			if($('.ba-cheetah-bg-video').length > 0) {
				BACheetahLayout._initBgVideos();
				BACheetahLayout._resizeBgVideos();
				win.on('resize.ba-cheetah-bg-video', BACheetahLayout._resizeBgVideos);
			}
		},

		/**
		 * Initializes all parallax backgrounds in a layout.
		 *

		 * @access private
		 * @method _initParallaxBackgrounds
		 */
		_initParallaxBackgrounds: function()
		{
			$('.ba-cheetah-row-bg-parallax').each(BACheetahLayout._initParallaxBackground);
		},

		/**
		 * Initializes a single parallax background.
		 *

		 * @access private
		 * @method _initParallaxBackgrounds
		 */
		_initParallaxBackground: function()
		{
			var row     = $(this),
				content = row.find('> .ba-cheetah-row-content-wrap'),
				src     = row.data('parallax-image'),
				loaded  = row.data('parallax-loaded'),
				img     = new Image();

			if(loaded) {
				return;
			}
			else if(typeof src != 'undefined') {

				$(img).on('load', function() {
					content.css('background-image', 'url(' + src + ')');
					row.data('parallax-loaded', true);
				});

				img.src = src;
			}
		},

		/**
		 * Fires when the window is scrolled to adjust
		 * parallax backgrounds.
		 *

		 * @access private
		 * @method _scrollParallaxBackgrounds
		 */
		_scrollParallaxBackgrounds: function()
		{
			$('.ba-cheetah-row-bg-parallax').each(BACheetahLayout._scrollParallaxBackground);
		},

		/**
		 * Fires when the window is scrolled to adjust
		 * a single parallax background.
		 *

		 * @access private
		 * @method _scrollParallaxBackground
		 */
		_scrollParallaxBackground: function()
		{
			var win     = $(window),
				row     = $(this),
				content = row.find('> .ba-cheetah-row-content-wrap'),
				speed   = row.data('parallax-speed'),
				offset  = content.offset(),
				yPos    = -((win.scrollTop() - offset.top) / speed);

			content.css('background-position', 'center ' + yPos + 'px');
		},

		/**
		 * Initializes all video backgrounds.
		 *

		 * @access private
		 * @method _initBgVideos
		 */
		_initBgVideos: function()
		{
			$('.ba-cheetah-bg-video').each(BACheetahLayout._initBgVideo);
		},

		/**
		 * Initializes a video background.
		 *

		 * @access private
		 * @method _initBgVideo
		 */
		_initBgVideo: function()
		{
			var wrap   = $( this ),
				width       = wrap.data( 'width' ),
				height      = wrap.data( 'height' ),
				mp4         = wrap.data( 'mp4' ),
				youtube     = wrap.data( 'youtube'),
				vimeo       = wrap.data( 'vimeo'),
				mp4Type     = wrap.data( 'mp4-type' ),
				webm        = wrap.data( 'webm' ),
				webmType    = wrap.data( 'webm-type' ),
				fallback    = wrap.data( 'fallback' ),
				loaded      = wrap.data( 'loaded' ),
				videoMobile = wrap.data( 'video-mobile' ),
				fallbackTag = '',
				videoTag    = null,
				mp4Tag      = null,
				webmTag     = null;

			// Return if the video has been loaded for this row.
			if ( loaded ) {
				return;
			}

			videoTag  = $( '<video autoplay loop muted playsinline></video>' );

			/**
			 * Add poster image (fallback image)
			 */
			if( 'undefined' != typeof fallback && '' != fallback ) {
				videoTag.attr( 'poster', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' )
				videoTag.css( 'background', 'transparent url("' + fallback + '") no-repeat center center' )
				videoTag.css( 'background-size', 'cover' )
				videoTag.css( 'height', '100%' )
			}

			// MP4 Source Tag
			if ( 'undefined' != typeof mp4 && '' != mp4 ) {

				mp4Tag = $( '<source />' );
				mp4Tag.attr( 'src', mp4 );
				mp4Tag.attr( 'type', mp4Type );

				videoTag.append( mp4Tag );
			}
			// WebM Source Tag
			if ( 'undefined' != typeof webm && '' != webm ) {

				webmTag = $( '<source />' );
				webmTag.attr( 'src', webm );
				webmTag.attr( 'type', webmType );

				videoTag.append( webmTag );
			}

			// This is either desktop, or mobile is enabled.
			if ( ! BACheetahLayout._isMobile() || ( BACheetahLayout._isMobile() && "yes" == videoMobile ) ) {
				if ( 'undefined' != typeof youtube ) {
					BACheetahLayout._initYoutubeBgVideo.apply( this );
				}
				else if ( 'undefined' != typeof vimeo ) {
					BACheetahLayout._initVimeoBgVideo.apply( this );
				}
				else {
					wrap.append( videoTag );
				}
			}
			else {
				// if we are here, it means we are on mobile and NO is set so remove video src and use fallback
				videoTag.attr('src', '')
				wrap.append( videoTag );
			}

			// Mark this video as loaded.
			wrap.data('loaded', true);
		},

		/**
		 * Initializes Youtube video background
		 *

		 * @access private
		 * @method _initYoutubeBgVideo
		 */
		_initYoutubeBgVideo: function()
		{
			var playerWrap  = $(this),
				videoId     = playerWrap.data('video-id'),
				videoPlayer = playerWrap.find('.ba-cheetah-bg-video-player'),
				enableAudio = playerWrap.data('enable-audio'),
				audioButton = playerWrap.find('.ba-cheetah-bg-video-audio'),
				startTime   = 'undefined' !== typeof playerWrap.data('start') ? playerWrap.data('start') : 0,
				endTime     = 'undefined' !== typeof playerWrap.data('end') ? playerWrap.data('end') : 0,
				loop        = 'undefined' !== typeof playerWrap.data('loop') ? playerWrap.data('loop') : 1,
				stateCount  = 0,
				player,fallback_showing;

			if ( videoId ) {
				fallback = playerWrap.data('fallback') || false
				if( fallback ) {
					playerWrap.find('iframe').remove()
					fallbackTag = $( '<div></div>' );
					fallbackTag.addClass( 'ba-cheetah-bg-video-fallback' );
					fallbackTag.css( 'background-image', 'url(' + playerWrap.data('fallback') + ')' );
					fallbackTag.css( 'background-size', 'cover' );
					fallbackTag.css( 'transition', 'background-image 1s')
					playerWrap.append( fallbackTag );
					fallback_showing = true;
				}
				BACheetahLayout._onYoutubeApiReady( function( YT ) {
					setTimeout( function() {

						player = new YT.Player( videoPlayer[0], {
							videoId: videoId,
							events: {
								onReady: function(event) {
									if ( "no" === enableAudio || BACheetahLayout._isMobile() ) {
										event.target.mute();
									}
									else if ( "yes" === enableAudio && event.target.isMuted ) {
										event.target.unMute();
									}

									// Store an instance to a parent
									playerWrap.data('YTPlayer', player);
									BACheetahLayout._resizeYoutubeBgVideo.apply(playerWrap);

									// Queue the video.
									event.target.playVideo();

									if ( audioButton.length > 0 && ! BACheetahLayout._isMobile() ) {
										audioButton.on( 'click', {button: audioButton, player: player}, BACheetahLayout._toggleBgVideoAudio );
									}
								},
								onStateChange: function( event ) {

									if ( event.data === 1 ) {
										if ( fallback_showing ) {
											$( '.ba-cheetah-bg-video-fallback' ).css( 'background-image', 'url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7)' )
										}
									}
									// Manual check if video is not playable in some browsers.
									// StateChange order: [-1, 3, -1]
									if ( stateCount < 4 ) {
										stateCount++;
									}

									// Comply with the audio policy in some browsers like Chrome and Safari.
									if ( stateCount > 1 && (-1 === event.data || 2 === event.data) && "yes" === enableAudio ) {
										player.mute();
										player.playVideo();
										audioButton.show();
									}

									if ( event.data === YT.PlayerState.ENDED && 1 === loop ) {
										if ( startTime > 0 ) {
											player.seekTo( startTime );
										}
										else {
											player.playVideo();
										}
									}
								},
								onError: function(event) {
									console.info('YT Error: ' + event.data)
									BACheetahLayout._onErrorYoutubeVimeo(playerWrap)
								}
							},
							playerVars: {
								playsinline: BACheetahLayout._isMobile() ? 1 : 0,
								controls: 0,
								showinfo: 0,
								rel : 0,
								start: startTime,
								end: endTime,
							}
						} );
					}, 1 );
				} );
			}
		},

		/**
		 * On youtube or vimeo error show the fallback image if available.

		 */
		_onErrorYoutubeVimeo: function(playerWrap) {

			fallback = playerWrap.data('fallback') || false
			if( ! fallback ) {
				return false;
			}
			playerWrap.find('iframe').remove()
			fallbackTag = $( '<div></div>' );
			fallbackTag.addClass( 'ba-cheetah-bg-video-fallback' );
			fallbackTag.css( 'background-image', 'url(' + playerWrap.data('fallback') + ')' );
			playerWrap.append( fallbackTag );
		},

		/**
		 * Check if Youtube API has been downloaded
		 *

		 * @access private
		 * @method _onYoutubeApiReady
		 * @param  {Function} callback Method to call when YT API has been loaded
		 */
		_onYoutubeApiReady: function( callback ) {
			if ( window.YT && YT.loaded ) {
				callback( YT );
			} else {
				// If not ready check again by timeout..
				setTimeout( function() {
					BACheetahLayout._onYoutubeApiReady( callback );
				}, 350 );
			}
		},

		/**
		 * Initializes Vimeo video background
		 *

		 * @access private
		 * @method _initVimeoBgVideo
		 */
		_initVimeoBgVideo: function()
		{
			var playerWrap	= $(this),
				videoId 	= playerWrap.data('video-id'),
				videoPlayer = playerWrap.find('.ba-cheetah-bg-video-player'),
				enableAudio = playerWrap.data('enable-audio'),
				audioButton = playerWrap.find('.ba-cheetah-bg-video-audio'),
				player,
				width = playerWrap.outerWidth(),
				ua    = navigator.userAgent;

			if ( typeof Vimeo !== 'undefined' && videoId )	{
				player = new Vimeo.Player(videoPlayer[0], {
					id         : videoId,
					loop       : true,
					title      : false,
					portrait   : false,
					background : true,
					autopause  : false,
					muted      : true
				});

				playerWrap.data('VMPlayer', player);
				if ( "no" === enableAudio ) {
					player.setVolume(0);
				}
				else if ("yes" === enableAudio ) {
					// Chrome and Safari have audio policy restrictions for autoplay videos.
					if ( ua.indexOf("Safari") > -1 || ua.indexOf("Chrome") > -1 ) {
						player.setVolume(0);
						audioButton.show();
					}
					else {
						player.setVolume(1);
					}
				}

				player.play().catch(function(error) {
					BACheetahLayout._onErrorYoutubeVimeo(playerWrap)
				});

				if ( audioButton.length > 0 ) {
					audioButton.on( 'click', {button: audioButton, player: player}, BACheetahLayout._toggleBgVideoAudio );
				}
			}
		},

		/**
		 * Mute / unmute audio on row's video background.
		 * It works for both Youtube and Vimeo.
		 *

		 * @access private
		 * @method _toggleBgVideoAudio
		 * @param {Object} e Method arguments
		 */
		_toggleBgVideoAudio: function( e ) {
			var player  = e.data.player,
			    control = e.data.button.find('.ba-cheetah-audio-control');

			if ( control.hasClass( 'fa-volume-off' ) ) {
				// Unmute
				control
					.removeClass( 'fa-volume-off' )
					.addClass( 'fa-volume-up' );
				e.data.button.find( '.fa-times' ).hide();

				if ( 'function' === typeof player.unMute ) {
					player.unMute();
				}
				else {
					player.setVolume( 1 );
				}
			}
			else {
				// Mute
				control
					.removeClass( 'fa-volume-up' )
					.addClass( 'fa-volume-off' );
				e.data.button.find( '.fa-times' ).show();

				if ( 'function' === typeof player.unMute ) {
					player.mute();
				}
				else {
					player.setVolume( 0 );
				}
			}
		},

		/**
		 * Fires when there is an error loading a video
		 * background source and shows the fallback.
		 *

		 * @access private
		 * @method _videoBgSourceError
		 * @param {Object} e An event object

		 */
		_videoBgSourceError: function( e )
		{
			var source 		= $( e.target ),
				wrap   		= source.closest( '.ba-cheetah-bg-video' ),
				vid		    = wrap.find( 'video' ),
				fallback  	= wrap.data( 'fallback' ),
				fallbackTag = '';
			source.remove();

			if ( vid.find( 'source' ).length ) {
				// Don't show the fallback if we still have other sources to check.
				return;
			} else if ( '' !== fallback ) {
				fallbackTag = $( '<div></div>' );
				fallbackTag.addClass( 'ba-cheetah-bg-video-fallback' );
				fallbackTag.css( 'background-image', 'url(' + fallback + ')' );
				wrap.append( fallbackTag );
				vid.remove();
			}
		},

		/**
		 * Fires when the window is resized to resize
		 * all video backgrounds.
		 *

		 * @access private
		 * @method _resizeBgVideos
		 */
		_resizeBgVideos: function()
		{
			$('.ba-cheetah-bg-video').each( function() {

				BACheetahLayout._resizeBgVideo.apply( this );

				if ( $( this ).parent().find( 'img' ).length > 0 ) {
					$( this ).parent().imagesLoaded( $.proxy( BACheetahLayout._resizeBgVideo, this ) );
				}
			} );
		},

		/**
		 * Fires when the window is resized to resize
		 * a single video background.
		 *

		 * @access private
		 * @method _resizeBgVideo
		 */
		_resizeBgVideo: function()
		{
			if ( 0 === $( this ).find( 'video' ).length && 0 === $( this ).find( 'iframe' ).length ) {
				return;
			}

			var wrap        = $(this),
				wrapHeight  = wrap.outerHeight(),
				wrapWidth   = wrap.outerWidth(),
				vid         = wrap.find('video'),
				vidHeight   = wrap.data('height'),
				vidWidth    = wrap.data('width'),
				newWidth    = wrapWidth,
				newHeight   = Math.round(vidHeight * wrapWidth/vidWidth),
				newLeft     = 0,
				newTop      = 0,
				iframe 		= wrap.find('iframe');

			if ( vid.length ) {
				if(vidHeight === '' || typeof vidHeight === 'undefined' || vidWidth === '' || typeof vidWidth === 'undefined') {
					vid.css({
						'left'      : '0px',
						'top'       : '0px',
						'width'     : newWidth + 'px'
					});

					// Try to set the actual video dimension on 'loadedmetadata' when using URL as video source
					vid.on('loadedmetadata', BACheetahLayout._resizeOnLoadedMeta);

				}
				else {

					if(newHeight < wrapHeight) {
						newHeight   = wrapHeight;
						newWidth    = Math.round(vidWidth * wrapHeight/vidHeight);
						newLeft     = -((newWidth - wrapWidth)/2);
					}
					else {
						newTop      = -((newHeight - wrapHeight)/2);
					}

					vid.css({
						'left'      : newLeft + 'px',
						'top'       : newTop + 'px',
						'height'    : newHeight + 'px',
						'width'     : newWidth + 'px'
					});
				}
			}
			else if ( iframe.length ) {

				// Resize Youtube video player within iframe tag
				if ( typeof wrap.data('youtube') !== 'undefined' ) {
					BACheetahLayout._resizeYoutubeBgVideo.apply(this);
				}
			}
		},

		/**
		 * Fires when video meta has been loaded.
		 * This will be Triggered when width/height attributes were not specified during video background resizing.
		 *

		 * @access private
		 * @method _resizeOnLoadedMeta
		 */
		_resizeOnLoadedMeta: function(){
			var video 		= $(this),
				wrapHeight 	= video.parent().outerHeight(),
				wrapWidth 	= video.parent().outerWidth(),
				vidWidth 	= video[0].videoWidth,
				vidHeight 	= video[0].videoHeight,
				newHeight   = Math.round(vidHeight * wrapWidth/vidWidth),
				newWidth    = wrapWidth,
				newLeft     = 0,
				newTop 		= 0;

			if(newHeight < wrapHeight) {
				newHeight   = wrapHeight;
				newWidth    = Math.round(vidWidth * wrapHeight/vidHeight);
				newLeft     = -((newWidth - wrapWidth)/2);
			}
			else {
				newTop      = -((newHeight - wrapHeight)/2);
			}

			video.parent().data('width', vidWidth);
			video.parent().data('height', vidHeight);

			video.css({
				'left'      : newLeft + 'px',
				'top'       : newTop + 'px',
				'width'     : newWidth + 'px',
				'height' 	: newHeight + 'px'
			});
		},

		/**
		 * Fires when the window is resized to resize
		 * a single Youtube video background.
		 *

		 * @access private
		 * @method _resizeYoutubeBgVideo
		 */
		_resizeYoutubeBgVideo: function()
		{
			var wrap				= $(this),
				wrapWidth 			= wrap.outerWidth(),
				wrapHeight 			= wrap.outerHeight(),
				player 				= wrap.data('YTPlayer'),
				video 				= player ? player.getIframe() : null,
				aspectRatioSetting 	= '16:9', // Medium
				aspectRatioArray 	= aspectRatioSetting.split( ':' ),
				aspectRatio 		= aspectRatioArray[0] / aspectRatioArray[1],
				ratioWidth 			= wrapWidth / aspectRatio,
				ratioHeight 		= wrapHeight * aspectRatio,
				isWidthFixed 		= wrapWidth / wrapHeight > aspectRatio,
				width 				= isWidthFixed ? wrapWidth : ratioHeight,
				height 				= isWidthFixed ? ratioWidth : wrapHeight;

			if ( video ) {
				$(video).width( width ).height( height );
			}
		},

		/**
		 * Initializes module animations.
		 *

		 * @access private
		 * @method _initModuleAnimations
		 */
		_initModuleScrollAnimations: function()
		{
			const elements = document.getElementsByClassName('ba-cheetah-scroll-animation');
			window.onscroll = function () {

				for (let i = 0; i !== elements.length; i++) {
					if (document.body.offsetWidth >= 990){

						let $elDataset = elements[i].dataset;

						let documentHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight,
							windowHeight = window.innerHeight,
							docScroll = $(document).scrollTop();

						let actualValue = (docScroll * 100) / (documentHeight - windowHeight);

						let transform = '';
						let filter = '';
						let opacity = '';

						if ($elDataset.scrollAnimationVerticalEffect === 'active') {

							let initialValue = $elDataset.scrollAnimationVerticalViewportInitial;
							let finalValue = $elDataset.scrollAnimationVerticalViewportFinal;
							let speed = $elDataset.scrollAnimationVerticalViewportSpeed * 2;


							if (
								actualValue > initialValue &&
								actualValue < finalValue
							) {
								let increaseHeight = ((actualValue - initialValue) * speed);
								let decreaseHeight = ((finalValue - actualValue) * speed) - elements[i].offsetHeight;

								if ($elDataset.scrollAnimationVerticalDirection === 'down') {
									transform = 'translateY(' + increaseHeight + 'px)';
								}

								if ($elDataset.scrollAnimationVerticalDirection === 'up') {
									transform = 'translateY(' + decreaseHeight + 'px)';
								}
							}
						}

						if ($elDataset.scrollAnimationHorizontalEffect === 'active') {

							let initialValue = $elDataset.scrollAnimationHorizontalViewportInitial;
							let finalValue = $elDataset.scrollAnimationHorizontalViewportFinal;
							let speed = $elDataset.scrollAnimationHorizontalViewportSpeed * 2;


							if (
								actualValue > initialValue &&
								actualValue < finalValue
							) {
								let increasePos = ((actualValue - initialValue) * speed);

								if ($elDataset.scrollAnimationHorizontalDirection === 'right') {
									transform = transform + ' translateX(' + increasePos + 'px)';
								}

								if ($elDataset.scrollAnimationHorizontalDirection === 'left') {
									transform = transform + ' translateX(-' + increasePos + 'px)';
								}
							}
						}

						if ($elDataset.scrollAnimationRotateEffect === 'active') {

							let initialValue = $elDataset.scrollAnimationRotateViewportInitial;
							let finalValue = $elDataset.scrollAnimationRotateViewportFinal;
							let speed = $elDataset.scrollAnimationRotateViewportSpeed * 2;


							if (
								actualValue > initialValue &&
								actualValue < finalValue
							) {
								let increasePos = ((actualValue - initialValue) * speed);

								if ($elDataset.scrollAnimationRotateDirection === 'right') {
									transform = transform + ' rotate(' + increasePos + 'deg)';
								}

								if ($elDataset.scrollAnimationRotateDirection === 'left') {
									transform = transform + ' rotate(-' + increasePos + 'deg)';
								}
							}
						}

						if ($elDataset.scrollAnimationScaleEffect === 'active') {

							let initialValue = $elDataset.scrollAnimationScaleViewportInitial;
							let finalValue = $elDataset.scrollAnimationScaleViewportFinal;
							let speed = $elDataset.scrollAnimationScaleViewportSpeed * 2;


							let middle = finalValue / 2 + initialValue / 2;

							if (
								actualValue > initialValue &&
								actualValue < finalValue
							) {

								if ($elDataset.scrollAnimationScaleDirection === 'up') {
									let increasePos = actualValue * speed / 100 + 1;
									transform = transform + ' scale(' + increasePos + ')';
								}

								if ($elDataset.scrollAnimationScaleDirection === 'down') {
									let decreasePos = 1 - (actualValue / 100) * speed;
									if (decreasePos < 0)
										decreasePos = 0;
									transform = transform + ' scale(' + decreasePos + ')';
								}

								if ($elDataset.scrollAnimationScaleDirection === 'down-up') {

									if (middle > actualValue) {
										let decreasePos = 1 - (actualValue / 100) * speed;
										if (decreasePos < 0)
											decreasePos = 0;

										transform = transform + ' scale(' + decreasePos + ')';
									} else {
										let increasePos = ((middle * speed / 100 - 1) * -1) + ((actualValue - middle) / 100) * speed;
										transform = transform + ' scale(' + increasePos + ')';
									}
								}

								if ($elDataset.scrollAnimationScaleDirection === 'up-down') {

									if (middle > actualValue) {
										let increasePos = actualValue * speed / 100 + 1;
										transform = transform + ' scale(' + increasePos + ')';
									} else {
										let decreasePos = (middle * speed / 100 + 1) - ((actualValue - middle) / 100) * speed;
										if (decreasePos < 0)
											decreasePos = 0;
										transform = transform + ' scale(' + decreasePos + ')';
									}
								}
							}
						}

						if ($elDataset.scrollAnimationBlurEffect === 'active') {

							let initialValue = $elDataset.scrollAnimationBlurViewportInitial;
							let finalValue = $elDataset.scrollAnimationBlurViewportFinal;
							let speed = $elDataset.scrollAnimationBlurViewportSpeed * 2;

							let middle = finalValue / 2 + initialValue / 2;

							if (
								actualValue > initialValue &&
								actualValue < finalValue
							) {

								if ($elDataset.scrollAnimationBlurDirection === 'in') {
									let increasePos = (((actualValue - finalValue) / 100) * speed) * -1;
									filter = filter + ' blur(' + increasePos + 'px)';
								}

								if ($elDataset.scrollAnimationBlurDirection === 'out') {
									let decreasePos = ((actualValue - initialValue) / 100) * speed;

									if (decreasePos < 0)
										decreasePos = 0;
									filter = filter + ' blur(' + decreasePos + 'px)';
								}

								if ($elDataset.scrollAnimationBlurDirection === 'in-out') {

									if (middle > actualValue) {
										let increasePos = (((actualValue - finalValue) / 100) * speed) * -1;
										filter = filter + ' blur(' + increasePos + 'px)';
									} else {

										let decreasePos = ((actualValue - middle) / 100) * speed - ((((actualValue - finalValue) / 100) * speed) * -1);

										if (decreasePos < 0)
											decreasePos = 0;
										filter = filter + ' blur(' + decreasePos + 'px)';
									}
								}

								if ($elDataset.scrollAnimationBlurDirection === 'out-in') {

									if (middle > actualValue) {
										let decreasePos = ((actualValue - initialValue) / 100) * speed;

										if (decreasePos < 0)
											decreasePos = 0;
										filter = filter + ' blur(' + decreasePos + 'px)';
									} else {
										let increasePos = ((((actualValue - finalValue) / 100) * speed) * -1) - (((actualValue - middle) / 100) * speed);
										filter = filter + ' blur(' + increasePos + 'px)';
									}
								}
							}
						}

						if ($elDataset.scrollAnimationFadeEffect === 'active') {

							let initialValue = $elDataset.scrollAnimationFadeViewportInitial;
							let finalValue = $elDataset.scrollAnimationFadeViewportFinal;
							let speed = $elDataset.scrollAnimationFadeViewportSpeed * 2;

							let middle = finalValue / 2 + initialValue / 2;

							if (
								actualValue > initialValue &&
								actualValue < finalValue
							) {

								if ($elDataset.scrollAnimationFadeDirection === 'in') {
									let increasePos = (((actualValue - finalValue) / 100) * speed) * -1;
									opacity = opacity + ' ' + increasePos;
								}

								if ($elDataset.scrollAnimationFadeDirection === 'out') {
									let decreasePos = ((actualValue - initialValue) / 100) * speed;

									if (decreasePos < 0)
										decreasePos = 0;
									opacity = opacity + ' ' + decreasePos;
								}

								if ($elDataset.scrollAnimationFadeDirection === 'in-out') {

									if (middle > actualValue) {
										let increasePos = (((actualValue - finalValue) / 100) * speed) * -1;
										opacity = opacity + ' ' + increasePos + 'px)';
									} else {

										let decreasePos = ((actualValue - middle) / 100) * speed - ((((actualValue - finalValue) / 100) * speed) * -1);

										if (decreasePos < 0)
											decreasePos = 0;
										opacity = opacity + ' ' + decreasePos;
									}
								}

								if ($elDataset.scrollAnimationFadeDirection === 'out-in') {

									if (middle > actualValue) {
										let decreasePos = ((actualValue - initialValue) / 100) * speed;

										if (decreasePos < 0)
											decreasePos = 0;
										opacity = opacity + ' ' + decreasePos;
									} else {
										let increasePos = ((((actualValue - finalValue) / 100) * speed) * -1) - (((actualValue - middle) / 100) * speed);
										opacity = opacity + ' ' + increasePos;
									}
								}
							}
						}

						if (transform !== '')
							elements[i].style.transform = transform;
						if (filter !== '')
							elements[i].style.filter = filter;
						if (opacity !== '')
							elements[i].style.opacity = opacity;

					}else{
						elements[i].style.transform = 'none';
						elements[i].style.filter = 'none';
						elements[i].style.opacity = 'none';
					}
				}
			};

			const elementsMouse = document.getElementsByClassName('ba-cheetah-scroll-animation-mouse');

			for (let i = 0; i !== elementsMouse.length; i++) {

				let $elDataset = elementsMouse[i].dataset;

				if($elDataset.scrollAnimationMouseEffect === 'active'){

					let speed = $elDataset.scrollAnimationMouseSpeed * 2;
					let direction = $elDataset.scrollAnimationMouseDirection;

					elementsMouse[i].onmouseover = function (e) {
						if (document.body.offsetWidth >= 990) {
							// e = Mouse click event.
							var rect = e.target.getBoundingClientRect();
							var x = e.clientX - rect.left; //x position within the element.
							var y = e.clientY - rect.top;  //y position within the element.

							// let transform = elementsMouse[i].style.transform;
							let transform = '';

							if (direction === 'in')
								elementsMouse[i].style.transform = transform + 'matrix(1, 0, 0, 1, ' + (x / 100 * speed) + ', ' + (y / 100 * speed) + ')';
							if (direction === 'out')
								elementsMouse[i].style.transform = transform + 'matrix(1, 0, 0, 1, ' + (x / 100 * speed * -1) + ', ' + (y / 100 * speed * -1) + ')';
						}else {
							elementsMouse[i].style.transform = 'none';
						}
					};

					if (document.body.offsetWidth < 990) {
						elementsMouse[i].style.transform = 'none';
					}

				}

				if($elDataset.scrollAnimationMouseEffectPerspective === 'active'){

					let speed = $elDataset.scrollAnimationMouseSpeedPerspective * 2;

					elementsMouse[i].onmouseover = function (e) {
						if (document.body.offsetWidth >= 990){
							const {clientX, clientY, currentTarget} = e;
							const {clientWidth, clientHeight, offsetLeft, offsetTop} = currentTarget;

							const horizontal = (clientX - offsetLeft) / clientWidth;
							const vertical = (clientY - offsetTop) / clientHeight;
							const rotateX = (speed / 2 - horizontal * speed).toFixed(2);
							const rotateY = (vertical * speed - speed / 2).toFixed(2);

							elementsMouse[i].style.transform = `perspective(${clientWidth}px) rotateX(${rotateY}deg) rotateY(${rotateX}deg) scale3d(1, 1, 1)`;
						}else {
							elementsMouse[i].style.transform = 'none';
						}
					};

					if (document.body.offsetWidth < 990) {
						elementsMouse[i].style.transform = 'none';
					}

				}
			}

		},

		/**
		 * Initializes module animations.
		 *

		 * @access private
		 * @method _initModuleAnimations
		 */
		_initModuleAnimations: function()
		{
			if(typeof jQuery.fn.waypoint !== 'undefined') {
				$('.ba-cheetah-animation').each( function() {
					var node = $( this ),
						nodeTop = node.offset().top,
						winHeight = $( window ).height(),
						bodyHeight = $( 'body' ).height(),
						waypoint = BACheetahLayoutConfig.waypoint,
						offset = '80%';

					if ( typeof waypoint.offset !== undefined ) {
						offset = BACheetahLayoutConfig.waypoint.offset + '%';
					}

					if ( bodyHeight - nodeTop < winHeight * 0.2 ) {
						offset = '100%';
					}

					node.waypoint({
						offset: offset,
						handler: BACheetahLayout._doModuleAnimation
					});
				} );
			}
		},

		/**
		 * Runs a module animation.
		 *

		 * @access private
		 * @method _doModuleAnimation
		 */
		_doModuleAnimation: function()
		{
			var module = 'undefined' == typeof this.element ? $(this) : $(this.element),
				delay = parseFloat(module.data('animation-delay')),
				duration = parseFloat(module.data('animation-duration'));

			if ( ! isNaN( duration ) ) {
				module.css( 'animation-duration', duration + 's' );
			}

			if(!isNaN(delay) && delay > 0) {
				setTimeout(function(){
					module.addClass('ba-cheetah-animated');
				}, delay * 1000);
			} else {
				setTimeout(function(){
					module.addClass('ba-cheetah-animated');
				}, 1);
			}
		},

		/**
		 * Opens a tab or accordion item if the browser hash is set
		 * to the ID of one on the page.
		 *

		 * @access private
		 * @method _initHash
		 */
		_initHash: function()
		{
			var hash 			= window.location.hash.replace( '#', '' ).split( '/' ).shift(),
				element 		= null,
				tabs			= null,
				responsiveLabel	= null,
				tabIndex		= null,
				label			= null;

			if ( '' !== hash ) {

				try {

					element = $( '#' + hash );

					if ( element.length > 0 ) {

						if ( element.hasClass( 'ba-cheetah-accordion-item' ) ) {
							setTimeout( function() {
								element.find( '.ba-cheetah-accordion-button' ).trigger( 'click' );
							}, 100 );
						}
						if ( element.hasClass( 'ba-cheetah-tabs-panel' ) ) {

							setTimeout( function() {

								tabs 			= element.closest( '.ba-cheetah-tabs' );
								responsiveLabel = element.find( '.ba-cheetah-tabs-panel-label' );
								tabIndex 		= responsiveLabel.data( 'index' );
								label 			= tabs.find( '.ba-cheetah-tabs-labels .ba-cheetah-tabs-label[data-index=' + tabIndex + ']' );

								if ( responsiveLabel.is( ':visible' ) ) {
									responsiveLabel.trigger( 'click' );
								}
								else {
									label[0].click();
									BACheetahLayout._scrollToElement( element );
								}

							}, 100 );
						}
					}
				}
				catch( e ) {}
			}
		},

		/**
		 * Initializes all anchor links on the page for smooth scrolling.
		 *

		 * @access private
		 * @method _initAnchorLinks
		 */
		_initAnchorLinks: function()
		{
			$( 'a' ).each( BACheetahLayout._initAnchorLink );
		},

		/**
		 * Initializes a single anchor link for smooth scrolling.
		 *

		 * @access private
		 * @method _initAnchorLink
		 */
		_initAnchorLink: function()
		{
			var link    = $( this ),
				href    = link.attr( 'href' ),
				loc     = window.location,
				id      = null,
				element = null;
			if ( 'undefined' != typeof href && href.indexOf( '#' ) > -1 && link.closest('svg').length < 1 ) {

				if ( loc.pathname.replace( /^\//, '' ) == this.pathname.replace( /^\//, '' ) && loc.hostname == this.hostname ) {

					try {

						id      = href.split( '#' ).pop();
						// If there is no ID then we have nowhere to look
						// Fixes a quirk in jQuery and FireFox
						if( ! id ) {
							return;
						}
						element = $( '#' + id );

						if ( element.length > 0 ) {
							if ( link.hasClass( 'ba-cheetah-scroll-link' ) || element.hasClass( 'ba-cheetah-row' ) || element.hasClass( 'ba-cheetah-col' ) || element.hasClass( 'ba-cheetah-module' ) ) {
								$( link ).on( 'click', BACheetahLayout._scrollToElementOnLinkClick );
							}
							if ( element.hasClass( 'ba-cheetah-accordion-item' ) ) {
								$( link ).on( 'click', BACheetahLayout._scrollToAccordionOnLinkClick );
							}
							if ( element.hasClass( 'ba-cheetah-tabs-panel' ) ) {
								$( link ).on( 'click', BACheetahLayout._scrollToTabOnLinkClick );
							}
						}
					}
					catch( e ) {}
				}
			}
		},

		/**
		 * Scrolls to an element when an anchor link is clicked.
		 *

		 * @access private
		 * @method _scrollToElementOnLinkClick
		 * @param {Object} e An event object.
		 * @param {Function} callback A function to call when the scroll is complete.
		 */
		_scrollToElementOnLinkClick: function( e, callback )
		{
			var element = $( '#' + $( this ).attr( 'href' ).split( '#' ).pop() );

			BACheetahLayout._scrollToElement( element, callback );

			e.preventDefault();
		},

		/**
		 * Scrolls to an element.
		 *

		 * @access private
		 * @method _scrollToElement
		 * @param {Object} element The element to scroll to.
		 * @param {Function} callback A function to call when the scroll is complete.
		 */
		_scrollToElement: function( element, callback )
		{
			var config  = BACheetahLayoutConfig.anchorLinkAnimations,
				dest    = 0,
				win     = $( window ),
				doc     = $( document );

			if ( element.length > 0 ) {

				if ( element.offset().top > doc.height() - win.height() ) {
					dest = doc.height() - win.height();
				}
				else {
					dest = element.offset().top - config.offset;
				}

				$( 'html, body' ).animate( { scrollTop: dest }, config.duration, config.easing, function() {

					if ( 'undefined' != typeof callback ) {
						callback();
					}

					if ( undefined != element.attr( 'id' ) ) {

						if ( history.pushState ) {
							history.pushState( null, null, '#' + element.attr( 'id' ) );
						}
						else {
							window.location.hash = element.attr( 'id' );
						}
					}
				} );
			}
		},

		/**
		 * Scrolls to an accordion item when a link is clicked.
		 *

		 * @access private
		 * @method _scrollToAccordionOnLinkClick
		 * @param {Object} e An event object.
		 */
		_scrollToAccordionOnLinkClick: function( e )
		{
			var element = $( '#' + $( this ).attr( 'href' ).split( '#' ).pop() );

			if ( element.length > 0 ) {

				var callback = function() {
					if ( element ) {
						element.find( '.ba-cheetah-accordion-button' ).trigger( 'click' );
						element = false;
					}
				};

				BACheetahLayout._scrollToElementOnLinkClick.call( this, e, callback );
			}
		},

		/**
		 * Scrolls to a tab panel when a link is clicked.
		 *

		 * @access private
		 * @method _scrollToTabOnLinkClick
		 * @param {Object} e An event object.
		 */
		_scrollToTabOnLinkClick: function( e )
		{
			var element 		= $( '#' + $( this ).attr( 'href' ).split( '#' ).pop() ),
				tabs			= null,
				label   		= null,
				responsiveLabel = null;

			if ( element.length > 0 ) {

				tabs 			= element.closest( '.ba-cheetah-tabs' );
				responsiveLabel = element.find( '.ba-cheetah-tabs-panel-label' );
				tabIndex 		= responsiveLabel.data( 'index' );
				label 			= tabs.find( '.ba-cheetah-tabs-labels .ba-cheetah-tabs-label[data-index=' + tabIndex + ']' );

				if ( responsiveLabel.is( ':visible' ) ) {

					var callback = function() {
						if ( element ) {
							responsiveLabel.trigger( 'click' );
							element = false;
						}
					};

					BACheetahLayout._scrollToElementOnLinkClick.call( this, e, callback );
				}
				else {
					label[0].click();
					BACheetahLayout._scrollToElement( element );
				}

				e.preventDefault();
			}
		},

		/**
		 * Initializes all builder forms on a page.
		 *

		 * @access private
		 * @method _initForms
		 */
		_initForms: function()
		{
			if ( ! BACheetahLayout._hasPlaceholderSupport ) {
				$( '.ba-cheetah-form-field input' ).each( BACheetahLayout._initFormFieldPlaceholderFallback );
			}

			$( '.ba-cheetah-form-field input' ).on( 'focus', BACheetahLayout._clearFormFieldError );
		},

		/**
		 * Checks to see if the current device has HTML5
		 * placeholder support.
		 *

		 * @access private
		 * @method _hasPlaceholderSupport
		 * @return {Boolean}
		 */
		_hasPlaceholderSupport: function()
		{
			var input = document.createElement( 'input' );

			return 'undefined' != input.placeholder;
		},

		/**
		 * Initializes the fallback for when placeholders aren't supported.
		 *

		 * @access private
		 * @method _initFormFieldPlaceholderFallback
		 */
		_initFormFieldPlaceholderFallback: function()
		{
			var field       = $( this ),
				val         = field.val(),
				placeholder = field.attr( 'placeholder' );

			if ( 'undefined' != placeholder && '' === val ) {
				field.val( placeholder );
				field.on( 'focus', BACheetahLayout._hideFormFieldPlaceholderFallback );
				field.on( 'blur', BACheetahLayout._showFormFieldPlaceholderFallback );
			}
		},

		/**
		 * Hides a fallback placeholder on focus.
		 *

		 * @access private
		 * @method _hideFormFieldPlaceholderFallback
		 */
		_hideFormFieldPlaceholderFallback: function()
		{
			var field       = $( this ),
				val         = field.val(),
				placeholder = field.attr( 'placeholder' );

			if ( val == placeholder ) {
				field.val( '' );
			}
		},

		/**
		 * Shows a fallback placeholder on blur.
		 *

		 * @access private
		 * @method _showFormFieldPlaceholderFallback
		 */
		_showFormFieldPlaceholderFallback: function()
		{
			var field       = $( this ),
				val         = field.val(),
				placeholder = field.attr( 'placeholder' );

			if ( '' === val ) {
				field.val( placeholder );
			}
		},

		/**
		 * Clears a form field error message.
		 *

		 * @access private
		 * @method _clearFormFieldError
		 */
		_clearFormFieldError: function()
		{
			var field = $( this );

			field.removeClass( 'ba-cheetah-form-error' );
			field.siblings( '.ba-cheetah-form-error-message' ).hide();
		}
	};

	/* Initializes the builder layout. */
	$(function(){
		BACheetahLayout.init();
	});

})(jQuery);
