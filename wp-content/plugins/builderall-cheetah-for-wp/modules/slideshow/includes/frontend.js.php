    <?php

$source = $module->get_source();

if ( !empty($source) ) :

?>
YUI({'logExclude': {'yui': true}}).use('ba-cheetah-slideshow', function (Y) {

    var slideshow = new Y.BA.Slideshow({
            autoPlay: <?php echo $settings->auto_play; ?>,
            <?php if ( $settings->crop ) : ?>
            crop: true,
            <?php endif; ?>
            height: <?php echo $settings->height; ?>,
            imageNavEnabled: <?php echo $settings->image_nav; ?>,
            <?php
                if('buttons' === $settings->nav_type) {
                    echo "navType: 'buttons',";
                    $leftNav = $centerNav = $rightNav = [];

                    $arrayNavOption = BACheetahSlideshowModule::$array_nav_types;
                    
                    foreach($arrayNavOption as $opt => $value) {
                        $test = eval('return "none" != $settings->nav_icon_' . $opt . ';');
                        eval('$name = $settings->nav_icon_' . $opt . ';');
                        
                        if($test) {
                            eval('array_push($'.$name.'Nav, "\'$opt\'");');
                        }
                    }

                    if($leftNav != [])
                        echo 'navButtonsLeft: [' . implode(",", $leftNav) . '],';
                    if($centerNav != [])
                        echo 'navButtons: [' . implode(",", $centerNav) . '],';
                    if($rightNav != [])
                        echo 'navButtonsRight: [' . implode(",", $rightNav) . '],';
                } elseif('thumbs' === $settings->nav_type) {
                    echo "navType: 'thumbs',";
                } else {
                    echo "navType: 'none',";
                }
                
            ?>
            navPosition: '<?php echo $settings->nav_position; ?>',
            navOverlay: <?php echo $settings->nav_overlay; ?>,
            overlayHideDelay: <?php echo $settings->nav_overlay_delay * 1000; ?>,
            overlayHideOnMousemove: <?php echo $settings->nav_overlay_hide_move; ?>,
            thumbsHorizontalSpacing: <?php echo $settings->thumbs_horizontal_spacing; ?>,
            thumbsVerticalSpacing: <?php echo $settings->thumbs_vertical_spacing; ?>,
            thumbsPauseOnClick: <?php echo $settings->thumbs_pause_click; ?>,
            thumbsTransitionDuration: <?php echo $settings->thumbs_transition_duration * 1000; ?>,
            thumbsImageWidth: <?php echo $settings->thumbs_image_width; ?>,
            thumbsImageHeight: <?php echo $settings->thumbs_image_height; ?>,
            captionLessLinkText: '<?php echo $settings->caption_less_link_text; ?>',
            captionMoreLinkText: '<?php echo $settings->caption_more_link_text; ?>',
            captionTextLength: <?php echo $settings->caption_text_length; ?>,
            captionStripTags: <?php echo $settings->caption_strip_tags; ?>,
            protect: <?php echo $settings->protect; ?>,
            randomize: <?php echo $settings->randomize; ?>,
            <?php if ( $global_settings->responsive_enabled ) : ?>
            responsiveThreshold: <?php echo $global_settings->responsive_breakpoint; ?>,
            <?php endif; ?>
            source: [{<?php echo $source; ?>}],
            speed: <?php echo $settings->speed * 1000; ?>,
            transition: '<?php echo $settings->transition; ?>',
            transitionDuration: <?php echo $settings->transitionDuration; ?>
        });

    slideshow.render('.ba-cheetah-node-<?php echo $id; ?> .ba-module__slideshow');

    Y.one('.ba-cheetah-node-<?php echo $id; ?> .ba-module__slideshow').setStyle('height', 'auto');
});
<?php endif; ?>
