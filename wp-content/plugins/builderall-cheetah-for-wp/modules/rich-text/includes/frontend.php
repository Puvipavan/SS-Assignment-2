<div class="ba-cheetah-rich-text">
	<?php

	global $wp_embed;

	echo wpautop( $wp_embed->autoembed( wp_kses_post($settings->text) ) );

	?>
</div>
