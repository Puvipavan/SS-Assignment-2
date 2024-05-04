<?php
if ($ba_cheetah_footer_id != 'disabled') {
	BACheetah::render_content_by_id($ba_cheetah_footer_id, 'footer');
	BACheetah::render_edit_link($ba_cheetah_footer_id, 'Edit footer');
};
?>
<?php wp_footer(); ?>

</div><!-- #page -->

</body>

</html>