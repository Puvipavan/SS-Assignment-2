(function ($) {

    $(function () {
        <?php if ( 'mosaic' === $settings->layout ) : ?>
        if (typeof $.fn.Mosaic !== 'undefined') {
            jQuery(document).ready(function () {
                setTimeout(function () {
                    $('.ba-module__gallery.ba-node-<?php echo $id; ?>').Mosaic({
                        showTailWhenNotEnoughItemsForEvenOneRow: true,
                        maxRowHeight: <?php echo $settings->max_row_height ?>,
                        innerGap: <?php echo $settings->spacing ?>
                    });
                }, 60);
            });
        }
        <?php endif; ?>

        <?php if ( 'lightbox' === $settings->click_action ) : ?>
        if (typeof $.fn.fancybox !== 'undefined') {

            var body = $('body'),
                preview = parent.document.querySelector('.ba-cheetah--preview-actions');

            if (preview) {
                body.addClass('fancybox-responsive-preview');
            } else {
                body.removeClass('fancybox-responsive-preview');
            }

            $().fancybox({
                selector: '.ba-node-<?php echo $id; ?> .gallery__item [data-link-type="lightbox"]',
                caption: function (instance, item) {
                    var caption = $(this).data('caption') || '';
                    var title = $(this).data('title') || '';
                    var description = $(this).data('description') || '';

                    if (caption || title) {
                        caption = '<span>' + (caption.length ? caption : title) + '</span>';
                    }

                    if (description) {
                        caption += '<p>' + description + '</p>';
                    }

                    return caption;
                }
            });
        }
        <?php endif; ?>

    })
})(jQuery);
