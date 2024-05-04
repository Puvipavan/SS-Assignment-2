<?php
global $wpdb;
$import_url = admin_url( 'admin.php?page=es_subscribers&action=import' );
?>
<table class="mt-2 w-full bg-white rounded-md overflow-hidden" style="<?php echo ! empty( $upsell ) ? 'filter:blur(1px);' : ''; ?>">
	<tbody> 
		<?php
		$allowed_html_tags = ig_es_allowed_html_tags_in_esc();
		if ( ! empty( $audience_activity ) ) {
			foreach ( $audience_activity as $activitiy_key => $activitiy ) {
				?>
				<tr class=" 
				<?php 
				if ( count($audience_activity) - 1 != $activitiy_key ) {
					?>
 border-b border-gray-200 <?php } ?> text-sm leading-5" >
					<td class="py-3 text-gray-500">
						<span class="es-ellipsis-text">
						<?php echo wp_kses( $activitiy['text'], $allowed_html_tags ); ?>
						</span>
					</td>
					<td class="pl-1 py-3 text-gray-600 text-right">
						<span>
							<?php echo esc_html( $activitiy['time'] ); ?>
						</span>
					</td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr><td><?php echo esc_html__( 'You don\'t have active subscribers yet. Start by importing new subscribers.', 'email-subscribers' ); ?></td></tr>
			<?php
		}
		?>
	</tbody>
</table>

<a href="<?php echo esc_url( $import_url ); ?>" class="inline-flex justify-center py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-indigo-500 rounded-md cursor-pointer select-none focus:outline-none focus:shadow-outline-indigo focus:shadow-lg hover:bg-indigo-500 hover:text-white  hover:shadow-md md:px-1 lg:px-3 xl:px-3" style="position: absolute;bottom: 5%;">
	<span>
		<?php echo esc_html__( 'Import', 'email-subscribers' ); ?>
	</span>
</a>
