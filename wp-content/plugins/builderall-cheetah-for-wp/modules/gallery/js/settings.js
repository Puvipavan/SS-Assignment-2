( function( $ ) {
    BACheetah.registerModuleHelper( 'gallery', {

        init: function() {

            var form   	= document.querySelector('.ba-cheetah-settings'),
                layout 	= $(form).find( 'select[name=layout]' ).val(),
                spacing = $(form).find( 'input[name=spacing]' ),
                maxRowHeight = $(form).find( 'input[name=max_row_height]' );

            if( layout === 'grid') {
                maxRowHeight.on('input', this._previewMaxGridRow);
                spacing.on( 'input', this._previewSpacing );
            } else if (layout === 'mosaic') {
                maxRowHeight.on('input', this._previewMosaic);
                spacing.on( 'input', this._previewMosaic );
            }
        },

        _previewMosaic: function( e ) {
            var preview	     = BACheetah.preview,
                form         = document.querySelector('.ba-cheetah-settings'),
                maxRowHeight = $(form).find('input[name=max_row_height]').val(),
                spacing      = parseInt($(form).find('input[name=spacing]').val());


            if (typeof $.fn.Mosaic !== 'undefined') {

                var spac = spacing ? spacing : 0;

                if (spac) {
                    setTimeout(function () {
                        $('.ba-module__gallery.ba-node-' + preview.nodeId).Mosaic({
                            showTailWhenNotEnoughItemsForEvenOneRow: true,
                            maxRowHeight: maxRowHeight,
                            innerGap: parseInt(spacing)
                        });
                    }, 60);
                } else {
                    preview.delayPreview(e);
                }
            }
        },

        _previewMaxGridRow: function( e ) {

            var preview	= BACheetah.preview,
                form    = $( '.ba-cheetah-settings' ),
                maxRowHeight = form.find( 'input[name=max_row_height]' ).val();

            preview.updateCSSRule( '.ba-module__gallery.ba-node-' + preview.nodeId + '.ba-module__gallery-grid .gallery__item .ba-cheetah-photo-content',
                'height', maxRowHeight + 'px'
            );
        },

        _previewSpacing: function( e ) {

            var preview	= BACheetah.preview,
                form    = $( '.ba-cheetah-settings' ),
                spacing = form.find( 'input[name=spacing]' ).val();

            preview.updateCSSRule( '.ba-module__gallery.ba-node-' + preview.nodeId + '.ba-module__gallery-grid',
                'gap', spacing + 'px'
            );
        },
    } );

} )( jQuery );
