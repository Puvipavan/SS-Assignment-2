<?php
/*
Plugin Name: Google OAuth Login
Description: Authenticate users with Google OAuth.
Version: 1.0
Author: Puvipavan
*/

require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('267673910603-4op552png13h4aachcr793ai4gg3n479.apps.googleusercontent.com'); // ClientID
$client->setClientSecret('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'); //Client Secret
$client->setRedirectUri('http://localhost:8000/');
$client->addScope('email');
$client->addScope('profile');

function google_oauth_init() {
    add_shortcode('google_login_button', 'render_google_login_button');
    add_action('init', 'handle_google_oauth_callback');
}

function render_google_login_button() {
    global $client;
    $auth_url = $client->createAuthUrl();
    echo '<a class="button button-large" style="float:left;" href="' . esc_url($auth_url) . '">Login with Google</a></br></br></br>';
}

function handle_google_oauth_callback() {
    global $client;
    if (isset($_GET['code'])) {
        $client->authenticate($_GET['code']);
        $access_token = $client->getAccessToken();
        $client->setAccessToken($access_token);

        $oauth_service = new Google_Service_Oauth2($client);
        $user_info = $oauth_service->userinfo->get();
        $email = $user_info->getEmail();
        $first_name = $user_info->getGivenName();
        $last_name = $user_info->getFamilyName();

        // Check if the user exists in WordPress
        $user = get_user_by('email', $email);
        if (!$user) {
            // If the user doesn't exist, create a new WordPress user
            $random_password = wp_generate_password();
            $user_id = wp_create_user($email, $random_password, $email);
            $user = get_user_by('id', $user_id);
        }

        wp_set_auth_cookie($user->ID, true);
        
        // Update fields at each login
        wp_update_user(array('ID' => $user_id, 'first_name' => $first_name, 'last_name' => $last_name ));
        wp_redirect(home_url('/'));
        exit;
    }
}

add_action('login_form', 'render_google_login_button');
add_action('init', 'handle_google_oauth_callback');

