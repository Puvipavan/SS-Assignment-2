<?php
// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

$audience_url              = admin_url( 'admin.php?page=es_subscribers' );
$new_contact_url           = admin_url( 'admin.php?page=es_subscribers&action=new' );
$new_broadcast_url         = admin_url( 'admin.php?page=es_campaigns#!/gallery?campaignType=newsletter' );
$new_post_notification_url = admin_url( 'admin.php?page=es_campaigns#!/gallery?campaignType=post_notification' );
$new_sequence_url          = admin_url( 'admin.php?page=es_sequence&action=new' );
$new_form_url              = admin_url( 'admin.php?page=es_forms&action=new' );
$new_list_url              = admin_url( 'admin.php?page=es_lists&action=new' );
$new_template_url          = admin_url( 'admin.php?page=es_campaigns#!/gallery?manageTemplates=yes' );
$icegram_pricing_url       = 'https://www.icegram.com/email-subscribers-pricing/';
$reports_url               = admin_url( 'admin.php?page=es_reports' );
$templates_url             = admin_url( 'edit.php?post_type=es_template' );
$settings_url              = admin_url( 'admin.php?page=es_settings' );
$facebook_url              = 'https://www.facebook.com/groups/2298909487017349/';


$topics = ES_Common::get_useful_articles();
$allowed_html_tags = ig_es_allowed_html_tags_in_esc(); 

?>
<div class="wrap pt-4 font-sans" id="ig-es-container">
	<header class="mx-auto max-w-7xl">
		<div class="md:flex md:items-center md:justify-between">
			<div class="flex-1 min-w-0">
				<h2 class="text-3xl font-bold leading-7 text-gray-700 sm:leading-9 sm:truncate">
					<?php echo esc_html__( 'Dashboard', 'email-subscribers' ); ?>
				</h2>
			</div>
			<div class="flex mt-4 md:mt-0 md:ml-4">
				
				<span class="ml-3 rounded-md shadow-sm">
				<div id="ig-es-create-button" class="relative inline-block text-left">
						<div>
						  <span class="rounded-md shadow-sm">
							<button type="button" class="w-full ig-es-primary-button">
								<?php echo esc_html__( 'Create', 'email-subscribers' ); ?>
							  <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
							  </svg>
							</button>
						  </span>
						</div>
						<div x-show="open" id="ig-es-create-dropdown" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
							 x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 hidden w-56 mt-2 origin-top-right rounded-md shadow-lg">
						  <div class="bg-white rounded-md shadow-xs">
							<div class="py-1">
							  <a href="<?php echo esc_url( $new_broadcast_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Broadcast', 'email-subscribers' ); ?></a>
								<!-- Start-IG-Code -->
							  <a href="<?php echo esc_url( $new_post_notification_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Post Notification', 'email-subscribers' ); ?></a>
								<!-- End-IG-Code -->
								<?php if ( ES()->is_pro() ) { ?>
								  <a href="<?php echo esc_url( $new_sequence_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Sequence', 'email-subscribers' ); ?></a>
								<?php } else { ?>
								  <a href="<?php echo esc_url( $icegram_pricing_url ); ?>" target="_blank" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Sequence', 'email-subscribers' ); ?>
									  <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full"><?php echo esc_html__( 'Premium', 'email-subscribers' ); ?></span></a>
								<?php } ?>
							</div>
							<div class="border-t border-gray-100"></div>
							<div class="py-1">
									<a href="<?php echo esc_url( $new_template_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Template', 'email-subscribers' ); ?></a>
							</div>
							<div class="border-t border-gray-100"></div>
							<div class="py-1">
									<a href="<?php echo esc_url( $new_form_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Form', 'email-subscribers' ); ?></a>
									<a href="<?php echo esc_url( $new_list_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New List', 'email-subscribers' ); ?></a>
									<a href="<?php echo esc_url( $new_contact_url ); ?>" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"><?php echo esc_html__( 'New Contact', 'email-subscribers' ); ?></a>
							</div>
						  </div>
						</div>
				</div>
			</span>
			</div>
		</div>
	</header>

	<main class="mx-auto max-w-7xl">
		<div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
			<!-- Recent campaigns section -->
			<section class="py-4 my-8 es-w-20 flex-auto bg-white rounded-lg shadow md:flex md:items-start md:justify-between sm:px-4 sm:grid sm:grid-cols-2 relative">
				<div class="flex-auto min-w-0 es-w-65 pr-3">
					<p class="text-lg font-medium leading-6 text-gray-400">
						<?php
							echo esc_html__( 'Recent Campaigns', 'email-subscribers' );
						?>
					</p>
					<?php
						ES_Admin::get_view(
							'dashboard/recent-campaigns', 
							array(
								'campaigns' => $campaigns,
							)
						);
						?>
				</div>
			</section>
			
			<!-- Audience Activity -->
			<section class="py-4 my-8 es-w-20 flex-auto bg-white rounded-lg shadow md:flex md:items-start md:justify-between sm:px-4 sm:grid sm:grid-cols-2 relative">
				<div class="flex-auto min-w-0 es-w-65 pr-3">
					<p class="text-lg font-medium leading-6 text-gray-400">
						<?php
							echo esc_html__( 'Audience Activity', 'email-subscribers' );
						?>
					</p>
					<?php
						ES_Admin::get_view(
							'dashboard/audience-activity', 
							array(
								'audience_activity' => $audience_activity,
							)
						);
						?>
				</div>
			</section>

			<!-- Forms & Lists -->
			<section class="py-4 my-8 es-w-20 flex-auto bg-white rounded-lg shadow md:flex md:items-start md:justify-between sm:px-4 sm:grid sm:grid-cols-2">
				<div class="flex-auto min-w-0 es-w-65 pr-3">
					<div class="flex items-center pr-2 py-2 md:justify-between">
						<p class="text-lg font-medium leading-6 text-gray-400">
							<?php echo esc_html__( 'Forms', 'email-subscribers' ); ?>
						</p>
						<p class="px-2 es_helper_text">
							<a class="hover:underline text-sm font-medium" href="https://www.icegram.com/docs/category/icegram-express/add-subscription-box-to-website" target="_blank">
								<?php echo esc_html__( 'How to add a form?', 'email-subscribers' ); ?>
							</a>
						</p>
					</div>
					<?php
						ES_Admin::get_view(
							'dashboard/forms-and-list', 
							array(
								'forms' => $forms,
								'lists' => $lists,
							)
						);
						?>
				</div>
			</section>
		</div>

		
		<section class="py-4 my-8 bg-white rounded-lg shadow md:flex md:items-start md:justify-between sm:px-4 sm:grid sm:grid-cols-2">
			<!-- Trial-optin form -->
			<div class="flex-auto min-w-0 es-w-35 pr-3">
				<?php
					ES_Admin::get_view(
						'dashboard/trial-optin-ess'
					);
					?>
			</div>
			
			<!-- Email sending service section -->
			<div class="flex-auto min-w-0 es-w-35 pr-3">
				<?php
				if ( ES_Service_Email_Sending::is_onboarding_completed() ) {
					$current_month        = ig_es_get_current_month();
					$service_status      = ES_Service_Email_Sending::get_sending_service_status();
					$ess_data            = get_option( 'ig_es_ess_data', array() );
					$used_limit          = isset( $ess_data['used_limit'][$current_month] ) ? $ess_data['used_limit'][$current_month]: 0;
					$allocated_limit     = isset( $ess_data['allocated_limit'] ) ? $ess_data['allocated_limit']                    : 0;
					$interval            = isset( $ess_data['interval'] ) ? $ess_data['interval']                                  : '';
					$current_mailer_name = ES()->mailer->get_current_mailer_name();

					ES_Admin::get_view(
						'dashboard/ess-account-overview',
						array(
							'service_status'      => $service_status,
							'allocated_limit'     => $allocated_limit,
							'used_limit'          => $used_limit,
							'interval'            => $interval,
							'current_mailer_name' => $current_mailer_name,
							'settings_url'        => $settings_url,
						)
					);
				} else {
					$ess_onboarding_step = get_option( 'ig_es_ess_onboarding_step', 1 );
					$ess_optin           = ig_es_get_request_data( 'ess_optin' );
					ES_Admin::get_view(
						'dashboard/ess-onboarding', 
						array(
							'ess_onboarding_step' => (int) $ess_onboarding_step,
							'ess_optin'           => $ess_optin,
						)
					);
				}
				?>
			</div>
			
			<!--Tips and Trick section -->
			<div class="flex-auto min-w-0 es-w-35 pr-2">
				<p class="text-lg font-medium leading-6 text-gray-400">
					<span>
						<?php 
							echo esc_html__( 'Tips & Tricks', 'email-subscribers' );
						?>
					</span>
				</p>
				<?php
					ES_Admin::get_view(
						'dashboard/tip-and-tricks',
						array(
							'topics' => $topics
						)
					);
					?>
			</div>
		</section>
	</main>
</div>

<script type="text/javascript">

	(function ($) {

		$(document).ready(function () {

			// When we click outside, close the dropdown
			$(document).on("click", function (event) {
				var $trigger = $("#ig-es-create-button");
				if ($trigger !== event.target && !$trigger.has(event.target).length) {
					$("#ig-es-create-dropdown").hide();
				}
			});

			// Toggle Dropdown
			$('#ig-es-create-button').click(function () {
				$('#ig-es-create-dropdown').toggle();
			});

		});

	})(jQuery);

</script>
