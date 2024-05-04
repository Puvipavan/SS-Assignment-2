

<section class="ba-module__posts ba-cheetah-node-<?php echo $id ?> ba-node-<?php echo $id ?>">
<?php 

add_filter('excerpt_length', function($size) use ($settings) {
    return $settings->excerpt_length ? $settings->excerpt_length : '55';
});

$default_photo = $module->default_photo();

$query = BACheetahLoop::query( $settings );

if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
		
		BACheetah::render_module_html('card', array(
			'id' 				=> "ba-node-$id ba-cheetah-node-$id", // ba-node => card / ba-cheetah-node => button
			'image' 			=> get_post_thumbnail_id() ? get_post_thumbnail_id() : $default_photo['photo'],
			'image_src' 		=> get_post_thumbnail_id() ? get_the_post_thumbnail_url() : $default_photo['photo_src'],
			'title' 			=> get_the_title(),
			'subtitle' 			=> $module->get_subtitle(isset($settings->post_type) ? $settings->post_type : 'post'),
			'text' 				=> $settings->excerpt_length === "0" ? null : get_the_excerpt(),
			'btn_link' 			=> get_the_permalink(),
			'btn_icon'			=> $settings->btn_icon,
			'btn_icon_position'	=> $settings->btn_icon_position,
			'btn_text' 			=> $settings->btn_text,
			)
		);
    }
}
wp_reset_postdata();
?>

</section>