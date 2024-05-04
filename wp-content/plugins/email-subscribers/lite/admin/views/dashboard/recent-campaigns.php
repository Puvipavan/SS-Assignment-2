<?php
global $wpdb;
$campaign_url = admin_url( 'admin.php?page=es_campaigns' );
if ( ! empty( $campaigns ) && count( $campaigns ) > 0 ) { ?>
	<table class="mt-2 w-full bg-white rounded-md overflow-hidden " style="<?php echo ! empty( $upsell ) ? 'filter:blur(1px);' : ''; ?>">
		<tbody>
			<?php
			$allowed_html_tags = ig_es_allowed_html_tags_in_esc();
			if ( ! empty( $campaigns ) ) {
				foreach ( $campaigns as $campaign_id => $campaign ) {
					?>
					<tr class=" 
					<?php 
					if ( count($campaigns) - 1 !== $campaign_id ) {
						?>
 border-b border-gray-200 <?php } ?> text-sm leading-5" >
						<td class="py-3 text-gray-500" style="display: flex">
						<div class="avatar" style="padding-right: 10px">
							<?php
							if ( IG_CAMPAIGN_TYPE_NEWSLETTER === $campaign['type'] ) {
								$img_name = 'broadcast';
							} elseif ( IG_CAMPAIGN_TYPE_WORKFLOW === $campaign['type'] ) {
								$img_name = 'sequences';
							} elseif ( IG_CAMPAIGN_TYPE_POST_NOTIFICATION === $campaign['type'] ) {
								$img_name = 'notification';
							}
							?>
							<div class='dash-avatar'>
								<img src="<?php echo esc_html__(WP_PLUGIN_URL, 'email-subscribers'); ?>/email-subscribers/lite/admin/images/new/<?php echo esc_attr( $img_name ); ?>.svg" alt="">
							</div>
						</div>
						<div class="font-medium">
							<?php echo "<a class='dash-recent-p es-ellipsis-text' href='admin.php?page=es_campaigns#!/campaign/edit/" . esc_html( $campaign['id'] ) . "' target='_blank'>" . esc_html( $campaign['name'] ) . '</a>'; ?>
						</div>
						</td>
						<td class="pl-1 py-3 text-gray-600 text-right"> 
							<div style="margin-left: auto;margin-right: auto;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;
							-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;
							justify-content: center;gap: 0.375rem;text-align: center;">
							<?php
							if ( IG_ES_CAMPAIGN_STATUS_ACTIVE  === (int) $campaign['status']) { 
								echo wp_kses("<p class='text-green-600 font-medium dash-status'><span class='bg-green-600 dashboard_dot'></span> Active</p>", $allowed_html_tags);
							} elseif (IG_ES_CAMPAIGN_STATUS_IN_ACTIVE === (int) $campaign['status']) {
								echo wp_kses("<p class='text-indigo-600 font-medium dash-status'><span class='bg-indigo-600 dashboard_dot'></span>Draft</p>", $allowed_html_tags);
							}
							?>
							</div>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr><td><?php echo esc_html__( 'No audience activities found.', 'email-subscribers' ); ?></td></tr>
				<?php
			}
			?>
		</tbody>
	</table>
	
	<a href="<?php echo esc_url( $campaign_url ); ?>" class="inline-flex justify-center py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-indigo-500 rounded-md cursor-pointer select-none focus:outline-none focus:shadow-outline-indigo focus:shadow-lg hover:bg-indigo-500 hover:text-white  hover:shadow-md md:px-1 lg:px-3 xl:px-3 ml-2 mt-4" style="position: absolute;bottom: 5%;">
		<span>
			<?php echo esc_html__( 'Create new campaign', 'email-subscribers' ); ?>
		</span>
	</a>
<?php 
} else {
	?>
	<p class="px-2 py-2 text-sm leading-5 text-gray-900">
		<?php echo esc_html__( 'There is no active or draft campaign found.', 'email-subscribers' ); ?>
	</p>
	<a href="<?php echo esc_url( $campaign_url ); ?>" class="inline-flex justify-center py-1 text-sm font-medium leading-5 text-white transition duration-150 ease-in-out bg-indigo-600 border border-indigo-500 rounded-md cursor-pointer select-none focus:outline-none focus:shadow-outline-indigo focus:shadow-lg hover:bg-indigo-500 hover:text-white  hover:shadow-md md:px-1 lg:px-3 xl:px-3 ml-2">
		<span>
			<?php echo esc_html__( 'Create campaign', 'email-subscribers' ); ?>
		</span>
	</a>
	<?php
}
?>
