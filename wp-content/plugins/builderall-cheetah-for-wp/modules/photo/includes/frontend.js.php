jQuery(function($) {
    <?php 

	// action lightbox

	if ( 'lightbox' === $settings->link_type ) : ?>
    if (typeof $.fn.fancybox !== 'undefined') {
        $().fancybox({
            selector: '.ba-cheetah-node-<?php echo $id; ?> a[data-link-type="lightbox"]',
            caption: function (instance, item) {

                <?php if ( 'below' === $settings->show_caption || 'hover' === $settings->show_caption ) : ?>
                    return $(this).closest( '.ba-cheetah-photo' ).find( '.ba-cheetah-photo-caption' ).text();
                <?php endif; ?>

                return  $(this).data('caption') || '';
            }
        });
    }
    <?php endif; ?>
});

<?php
	BACheetahPopups::render_popup_js(
		"click",
		".ba-cheetah-node-$id img",
		$settings->popup_id,
		$settings->link_type,
		(object) ['video_link' => $settings->video_link]
	);
?>