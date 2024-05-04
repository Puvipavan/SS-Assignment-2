<?php

// Default Settings
$defaults = array(
	'data_source' => 'custom_query',
	'post_type'   => 'post',
	'order_by'    => 'date',
	'order'       => 'DESC',
	'offset'      => 0,
	'users'       => '',
);

$tab_defaults = isset( $tab['defaults'] ) ? $tab['defaults'] : array();
$settings     = (object) array_merge( $defaults, $tab_defaults, (array) $settings );
/**
 * Allow extension of default Values
 * @see ba_cheetah_loop_settings
 */
$settings = apply_filters( 'ba_cheetah_loop_settings', $settings );

/**
 * e.g Add custom BACheetah::render_settings_field()
 * @see ba_cheetah_loop_settings_before_form
 */
do_action( 'ba_cheetah_loop_settings_before_form', $settings );

?>
<!--
<div id="ba-cheetah-settings-section-source" class="ba-cheetah-loop-data-source-select ba-cheetah-settings-section">
	<div class="ba-cheetah-settings-section-content">
		<table class="ba-cheetah-form-table">
		<?php

		// Data Source
		/*
		BACheetah::render_settings_field('data_source', array(
			'type'    => 'select',
			'label'   => __( 'Source', 'ba-cheetah' ),
			'default' => 'custom_query',
			'options' => array(
				'custom_query' => __( 'Custom Query', 'ba-cheetah' ),
				'main_query'   => __( 'Main Query', 'ba-cheetah' ),
			),
			'toggle'  => array(
				'custom_query' => array(
					'fields' => array( 'posts_per_page' ),
				),
			),
		), $settings);
		*/
		?>
		</table>
	</div>
</div>
-->
<div class="ba-cheetah-custom-query ba-cheetah-loop-data-source" data-source="custom_query">
	<div id="ba-cheetah-settings-section-general" class="ba-cheetah-settings-section">
		<div class="ba-cheetah-settings-section-header">
			<button class="ba-cheetah-settings-title">
				<?php _e( 'Query', 'ba-cheetah' ); ?>
				<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
			</button>
		</div>
		<div class="ba-cheetah-settings-section-content">
			<table class="ba-cheetah-form-table">
			<?php

			// Post type
			BACheetah::render_settings_field('post_type', array(
				'type'  => 'post-type',
				'label' => __( 'Post Type', 'ba-cheetah' ),
			), $settings);

			// Order
			BACheetah::render_settings_field('order', array(
				'type'    => 'select',
				'label'   => __( 'Order', 'ba-cheetah' ),
				'options' => array(
					'DESC' => __( 'Descending', 'ba-cheetah' ),
					'ASC'  => __( 'Ascending', 'ba-cheetah' ),
				),
			), $settings);

			// Order by
			BACheetah::render_settings_field('order_by', array(
				'type'    => 'select',
				'label'   => __( 'Order By', 'ba-cheetah' ),
				'options' => array(
					'author'         => __( 'Author', 'ba-cheetah' ),
					'comment_count'  => __( 'Comment Count', 'ba-cheetah' ),
					'date'           => __( 'Date', 'ba-cheetah' ),
					'modified'       => __( 'Date Last Modified', 'ba-cheetah' ),
					'ID'             => __( 'ID', 'ba-cheetah' ),
					'menu_order'     => __( 'Menu Order', 'ba-cheetah' ),
					'meta_value'     => __( 'Meta Value (Alphabetical)', 'ba-cheetah' ),
					'meta_value_num' => __( 'Meta Value (Numeric)', 'ba-cheetah' ),
					'rand'           => __( 'Random', 'ba-cheetah' ),
					'title'          => __( 'Title', 'ba-cheetah' ),
					'post__in'       => __( 'Selection Order', 'ba-cheetah' ),
				),
				'toggle'  => array(
					'meta_value'     => array(
						'fields' => array( 'order_by_meta_key' ),
					),
					'meta_value_num' => array(
						'fields' => array( 'order_by_meta_key' ),
					),
				),
			), $settings);

			// Meta Key
			BACheetah::render_settings_field('order_by_meta_key', array(
				'type'  => 'text',
				'label' => __( 'Meta Key', 'ba-cheetah' ),
			), $settings);

			// Offset
			/*
			BACheetah::render_settings_field('offset', array(
				'type'        => 'unit',
				'label'       => _x( 'Offset', 'How many posts to skip.', 'ba-cheetah' ),
				'default'     => '0',
				'placeholder' => '0',
				'sanitize'    => 'absint',
				'slider'      => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 2,
				),
				'help'        => __( 'Skip this many posts that match the specified criteria.', 'ba-cheetah' ),
			), $settings);
			*/

			BACheetah::render_settings_field('exclude_self', array(
				'type'    => 'select',
				'label'   => __( 'Exclude Current Post', 'ba-cheetah' ),
				'default' => 'no',
				'help'    => __( 'Ignore the current page or post from listing ', 'ba-cheetah' ),
				'options' => array(
					'yes' => __( 'Yes', 'ba-cheetah' ),
					'no'  => __( 'No', 'ba-cheetah' ),
				),
			), $settings);

			BACheetah::render_settings_field('posts_per_page', array(
				'type'    => 'unit',
				'label'   => __( 'Posts limit', 'ba-cheetah' ),
				'default' => '9',
				'slider' => array(
					'min' => 1,
					'max' => 20,
					'step' => 1
				)
			), $settings);
			?>
			</table>
		</div>
	</div>
	<div id="ba-cheetah-settings-section-filter" class="ba-cheetah-settings-section ba-cheetah-settings-section-collapsed">
		<div class="ba-cheetah-settings-section-header">
			<button class="ba-cheetah-settings-title">
				<?php _e( 'Filter', 'ba-cheetah' ); ?>
				<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
			</button>
		</div>
		<div class="ba-cheetah-settings-section-content">
			<?php foreach ( BACheetahLoop::post_types() as $slug => $type ) : ?>
			<table class="ba-cheetah-form-table ba-cheetah-custom-query-filter ba-cheetah-custom-query-<?php echo $slug; ?>-filter"<?php echo ( $slug == $settings->post_type ) ? ' style="display:table;"' : ''; ?>>
			<?php

			// Posts
			BACheetah::render_settings_field( 'posts_' . $slug, array(
				'type'     => 'suggest',
				'action'   => 'ba_cheetah_as_posts',
				'data'     => $slug,
				'label'    => $type->label,
				/* translators: %s: type label */
				'help'     => sprintf( __( 'Enter a list of %1$s.', 'ba-cheetah' ), $type->label ),
				'matching' => true,
			), $settings );

			// Taxonomies
			$taxonomies = BACheetahLoop::taxonomies( $slug );

			foreach ( $taxonomies as $tax_slug => $tax ) {

				BACheetah::render_settings_field( 'tax_' . $slug . '_' . $tax_slug, array(
					'type'     => 'suggest',
					'action'   => 'ba_cheetah_as_terms',
					'data'     => $tax_slug,
					'label'    => $tax->label,
					/* translators: %s: tax label */
					'help'     => sprintf( __( 'Enter a list of %1$s.', 'ba-cheetah' ), $tax->label ),
					'matching' => true,
				), $settings );
			}

			?>
			</table>
			<?php endforeach; ?>

			<table class="ba-cheetah-form-table">
			<?php

			// Author
			BACheetah::render_settings_field('users', array(
				'type'     => 'suggest',
				'action'   => 'ba_cheetah_as_users',
				'label'    => __( 'Authors', 'ba-cheetah' ),
				'help'     => __( 'Enter a list of authors usernames.', 'ba-cheetah' ),
				'matching' => true,
			), $settings);

			?>
			</table>
		</div>
	</div>
</div>
<?php
do_action( 'ba_cheetah_loop_settings_after_form', $settings ); // e.g Add custom BACheetah::render_settings_field()
