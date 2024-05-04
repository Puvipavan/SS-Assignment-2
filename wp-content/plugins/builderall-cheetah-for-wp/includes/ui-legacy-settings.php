<?php

/**
 * PLEASE NOTE: This file is only around for backwards compatibility
 * with third party settings forms that are still being rendered via
 * AJAX. Going forward, all settings forms should be rendered on the
 * frontend using BACheetahSettingsForms.render.
 */

$id = uniqid( 'ba-cheetah-lightbox-content-placeholder' );

?>
<div id="<?php echo $id; ?>"></div>
<script class="ba-cheetah-legacy-settings">

var config = <?php echo json_encode( $form ); ?>,
	wrap   = jQuery( '#<?php echo $id; ?>' ).closest( '.ba-cheetah-lightbox' ),
	id     = wrap.attr( 'data-instance-id' );

config.lightbox = BACheetahLightbox._instances[ id ];

BACheetahSettingsForms.render( config );

</script>
