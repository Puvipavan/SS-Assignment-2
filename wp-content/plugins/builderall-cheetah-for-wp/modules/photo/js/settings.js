(function($){

	BACheetah.registerModuleHelper( 'photo', {

		init: function() {

			this._getPopups()

			var form   		= $( '.ba-cheetah-settings' ),
				source 		= form.find( 'select[name=photo_source]' ),
				attachment 	= form.find( 'select[name=photo_src]' ),
				url 		= form.find( 'input[name=photo_url]' ),
				crop 		= form.find( 'select[name=crop]' );

			this._sourceChanged();

			source.on( 'change', this._sourceChanged );
			source.on( 'change', this._previewImage );
			attachment.on( 'change', this._previewImage );
			url.on( 'keyup', this._previewImage );
			crop.on( 'change', this._cropChanged );
		},

		_getPopups: function() {
			BACheetah.getSelectOptions('get_popups', 'popup_id');
		},

		_sourceChanged: function() {
			var form     = $( '.ba-cheetah-settings' ),
				source 	 = form.find( 'select[name=photo_source]' ).val(),
				linkType = form.find( 'select[name=link_type]' );

			linkType.find( 'option[value=page]' ).remove();

			if( source === 'library' ) {
				linkType.append( '<option value="page">' + BACheetahStrings.photoPage + '</option>' );
			}
		},

		_previewImage: function( e ) {
			var preview		= BACheetah.preview,
				node		= preview.elements.node,
				img			= null,
				form        = $( '.ba-cheetah-settings' ),
				source 		= form.find( 'select[name=photo_source]' ).val(),
				attachment 	= form.find( 'select[name=photo_src]' ),
				url 		= form.find( 'input[name=photo_url]' ),
				crop 		= form.find( 'select[name=crop]' ).val();

			if ( '' === crop ) {
				img = node.find( '.ba-cheetah-photo-img' );
				img.show();
				img.removeAttr( 'height' );
				img.removeAttr( 'width' );
				img.removeAttr( 'srcset' );
				img.removeAttr( 'sizes' );
				if ( 'library' === source ) {
					img.attr( 'src', attachment.val() );
				} else {
					img.attr( 'src', url.val() );
				}
			} else {
				preview.delayPreview( e );
			}
		},

		_cropChanged: function() {
			var form = $( '.ba-cheetah-settings' ),
				crop = form.find( 'select[name=crop]' ),
				radius = form.find( '.ba-cheetah-border-field-radius' );

			if ( 'circle' === crop.val() ) {
				radius.hide();
			} else {
				radius.show();
			}
		},
	} );

} )( jQuery );
