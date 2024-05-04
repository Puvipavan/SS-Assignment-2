(function($){

	/**
	 * Helper class for dealing with the post edit screen.
	 *
	 * @class BACheetahAdminPosts

	 * @static
	 */
	BACheetahAdminPosts = {

		/**
		 * Initializes the builder for the post edit screen.
		 *

		 * @method init
		 */
		init: function()
		{
			$('.ba-cheetah-enable-editor').on('click', this._enableEditorClicked);
			$('.ba-cheetah-enable-builder').on('click', this._enableBuilderClicked);
			$('.ba-cheetah-launch-builder').on('click', this._launchBuilderClicked);

			/* WPML Support */
			$('#icl_cfo').on('click', this._wpmlCopyClicked);

			this._hideBACheetahAdminButtons();
			this._hideBlockEditorInserter();
		},

		/**
		 * Fires when the text editor button is clicked
		 * and switches the current post to use that
		 * instead of the builder.
		 *

		 * @access private
		 * @method _enableEditorClicked
		 */
		_enableEditorClicked: function()
		{
			if ( ! $( 'body' ).hasClass( 'ba-cheetah-enabled' ) ) {
				return;
			}
			if ( confirm( BACheetahAdminPostsStrings.switchToEditor ) ) {

				$('.ba-cheetah-admin-tabs a').removeClass('ba-cheetah-active');
				$(this).addClass('ba-cheetah-active');

				BACheetahAdminPosts.ajax({
					action: 'ba_cheetah_disable',
				}, BACheetahAdminPosts._enableEditorComplete);
			}
		},

		/**
		 * Callback for enabling the editor.
		 *

		 * @access private
		 * @method _enableEditorComplete
		 */
		_enableEditorComplete: function()
		{
			$('body').removeClass('ba-cheetah-enabled');
			$(window).resize();
		},

		/**
		 * Callback for enabling the editor.
		 *

		 * @access private
		 * @method _enableBuilderClicked
		 */
		_enableBuilderClicked: function()
		{
			if($('body').hasClass('ba-cheetah-enabled')) {
				return;
			}
			else {
				$('.ba-cheetah-admin-tabs a').removeClass('ba-cheetah-active');
				$(this).addClass('ba-cheetah-active');
				BACheetahAdminPosts._launchBuilder();
			}
		},

		/**
		 * Fires when the page builder button is clicked
		 * and switches the current post to use that
		 * instead of the text editor.
		 *

		 * @access private
		 * @method _launchBuilderClicked
		 * @param {Object} e An event object.
		 */
		_launchBuilderClicked: function(e)
		{
			e.preventDefault();

			BACheetahAdminPosts._launchBuilder();
		},

		/**
		 * Callback for enabling the builder.
		 *

		 * @access private
		 * @method _launchBuilder
		 */
		_launchBuilder: function()
		{
			var postId = $('#post_ID').val(),
			    title  = $('#title');

			if(typeof title !== 'undefined' && title.val() === '') {
			    title.val('Post #' + postId);
			}

			$(window).off('beforeunload');
			$('body').addClass('ba-cheetah-enabled');
			$('.ba-cheetah-loading').show();
			$('form#post').append('<input type="hidden" name="ba-cheetah-redirect" value="' + postId + '" />');
			$('form#post').submit();
		},

		/**
		 * Fires when the WPML copy button is clicked.
		 *

		 * @access private
		 * @method _wpmlCopyClicked
		 * @param {Object} e An event object.
		 */
		_wpmlCopyClicked: function(e)
		{
			var originalPostId = $('#icl_translation_of').val();

			if(typeof originalPostId !== 'undefined') {

				$('.ba-cheetah-loading').show();

				BACheetahAdminPosts.ajax({
					action: 'ba_cheetah_duplicate_wpml_layout',
					original_post_id: originalPostId
				}, BACheetahAdminPosts._wpmlCopyComplete);
			}
		},

		/**
		 * Callback for when the WPML copy button is clicked.
		 *

		 * @access private
		 * @method _wpmlCopyComplete
		 * @param {String} response The JSON encoded response.
		 */
		_wpmlCopyComplete: function(response)
		{
			response = JSON.parse(response);

			$('.ba-cheetah-loading').hide();

			if(response.has_layout && response.enabled) {
				$('body').addClass('ba-cheetah-enabled');
			}
		},

		/**
		 * Hide the Page Builder Admin Buttons if Content Editor is hidden in the ACF Field Settings.
		 *

		 * @access private
		 * @method _hideBACheetahAdminButtons
		 */
		_hideBACheetahAdminButtons: function()
		{
			if ( $( '.acf-postbox' ).is( ':visible' ) && $( '#postdivrich' ).is( ':hidden' ) && ! $( '.ba-cheetah-enable-builder' ).hasClass('ba-cheetah-active') ){
				$( '.ba-cheetah-admin' ).hide();
			}
		},

		/**
		 * Hide the Gutenberg Block Editor Inserter button.
		 *

		 * @access private
		 * @method _hideBlockEditorInserter
		 */
		_hideBlockEditorInserter: function()
		{
			setTimeout( function(){
				if ( $( 'body' ).hasClass( 'ba-cheetah-enabled' ) ) {
					$( '.block-editor-inserter' ).hide();
					$( '.wp-block-paragraph' ).parent().remove();
                    $( '.wp-block[data-type="core/paragraph"]' ).hide();
				}
			}, 100 );
		},

		/**
		 * Makes an AJAX request.
		 *

		 * @method ajax
		 * @param {Object} data An object with data to send in the request.
		 * @param {Function} callback A function to call when the request is complete.
		 */
		ajax: function(data, callback)
		{
			// Add the post ID to the data.
			data.post_id = $('#post_ID').val();

			// Show the loader.
			$('.ba-cheetah-loading').show();

			// Send the request.
			$.post(ajaxurl, data, function(response) {

				BACheetahAdminPosts._ajaxComplete();

				if(typeof callback !== 'undefined') {
					callback.call(this, response);
				}
			});
		},

		/**
		 * Generic callback for when an AJAX request is complete.
		 *

		 * @access private
		 * @method _ajaxComplete
		 */
		_ajaxComplete: function()
		{
			$('.ba-cheetah-loading').hide();
		}
	};

	/* Initializes the post edit screen. */
	$(function(){
		BACheetahAdminPosts.init();
	});

})(jQuery);
