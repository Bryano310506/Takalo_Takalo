<?php

/**********************************************
 *         Application Environment            *
 **********************************************/

date_default_timezone_set('UTC');

error_reporting(E_ALL);

// Character encoding
if (function_exists('mb_internal_encoding') === true) {
	mb_internal_encoding('UTF-8');
}

if (function_exists('setlocale') === true) {
	setlocale(LC_ALL, 'en_US.UTF-8');
}

/**********************************************
 *           FlightPHP Core Settings          *
 **********************************************/

// Get the $app var to use below
if (empty($app) === true) {
	$app = Flight::app();
}

$app->path(__DIR__ . $ds . '..' . $ds . '..');

$app->set('flight.base_url', '/',);
$app->set('flight.case_sensitive', false);    // Set true for case sensitive routes. Default: false
$app->set('flight.log_errors', true);         // Log errors to file. Recommended: true in production
$app->set('flight.handle_errors', false);     // Let Tracy handle errors if false. Set true to use Flight's error handler
$app->set('flight.views.path', __DIR__ . $ds . '..' . $ds . 'views'); // Path to views/templates
$app->set('flight.views.extension', '.php');  // View file extension (e.g., '.php', '.latte')
$app->set('flight.content_length', false);    // Send content length header. Usually false unless required by proxy

$nonce = bin2hex(random_bytes(16));
$app->set('csp_nonce', $nonce);

/**********************************************
 *           User Configuration               *
 **********************************************/
return [
	/**************************************
	 *         Database Settings          *
	 **************************************/
	'database' => [
		'host'     => '127.0.0.1',      // Database host (e.g., 'localhost', 'db.example.com')
		//'port'	   => 5432,
		
		//'dbname'   => 'mini_template',   // Database name (e.g., 'flightphp')
		//'user'     => 'root',  // Database user (e.g., 'root')
		//'password' => '',  // Database password (never commit real passwords)

		'dbname'   => 'takalo_takalo',
		'user'     => 'root',
		'password' => '', 
	
	],

	// Google OAuth Credentials
	// 'google_oauth' => [
	//     'client_id'     => 'your_client_id',     // Google API client ID
	//     'client_secret' => 'your_client_secret', // Google API client secret
	//     'redirect_uri'  => 'your_redirect_uri',  // Redirect URI for OAuth callback
	// ],

	// Add more configuration sections below as needed
];
