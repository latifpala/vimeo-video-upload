<?php
/**
 * Plugin Name:       Video Uploads for Vimeo
 * Plugin URI:        https://geekerhub.com
 * Description:       Embed and upload videos to Vimeo directly from WordPress
 * Version:           1.0.0
 * Author:            Momin Iqbal
 * Author URI:        https://geekerhub.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-vimeo-videos
 * Domain Path:       /languages


Client ID : 8a895685bc7652dfea40e76c5720eb4bb8b38a0a
Client Secret : EdVD4aPuLLNxV5UDPV58WdvGVPbB/L7dzeghAE+th3/GDM1lgyN1qc+u6TUyS20NA2YYDyFfA+7TNAVQnsuEfhW7pAudP6yla+M2kqL797RacXiqvzaUCturo44C6fog
Access Token : 7d9c0ba546a97d4f48030ed60d7421fd


myclient ID : c18ad2888f1e0708aef588028b6db7075c2ded8e
myclient Secret : Jkn7TmwyRJoGuNCrCnx6SE67zStntY21FcMNiI/aPyNjPjEpXzsFO3h4GunLrstSwP9YH4zVATE9a9J08Yw4TXg6dYX8MzPlLGvReCeL7mgir8kRi1+1TjLuwx1Oou5K
access Token : 8d5f0b1aec06f5815026afa92d44e101
*/

define('WP_VIMEO_VIDEOS_VERSION', '1.8.0');
define('WP_VIMEO_VIDEOS_PATH', plugin_dir_path(__FILE__));
define('WP_VIMEO_VIDEOS_URL', plugin_dir_url(__FILE__));
define('WP_VIMEO_VIDEOS_BASENAME', plugin_basename(__FILE__));
define('WP_VIMEO_VIDEOS_MIN_PHP_VERSION', '5.5.0');
define('WP_VIMEO_USER_ID', '171867308');
define('WP_VIMEO_ACCESS_TOKEN', '8d5f0b1aec06f5815026afa92d44e101');

include_once(WP_VIMEO_VIDEOS_PATH."/includes/geeker-functions.php");
include_once(WP_VIMEO_VIDEOS_PATH."/includes/geeker-vimeo-api-functions.php");
include_once(WP_VIMEO_VIDEOS_PATH."/includes/geeker-ajax-functions.php");
include_once(WP_VIMEO_VIDEOS_PATH."/includes/geeker-shortcodes.php");


function geeker_vimeo_enqueue_scripts_callback() {
	// bootstrap
	wp_register_style('geeker-bootstrap-min-css', WP_VIMEO_VIDEOS_URL . 'assets/css/bootstrap.min.css');
	
	// select2
	wp_register_style('select2-css', WP_VIMEO_VIDEOS_URL . 'assets/css/select2.min.css');
	
	wp_register_style('select2-bootstrap-css', WP_VIMEO_VIDEOS_URL . 'assets/css/select2-bootstrap.css');

	// Sweetalert
	wp_register_script( 'geeker-swal', WP_VIMEO_VIDEOS_URL . 'assets/resources/sweetalert2/sweetalert2.min.js', null, '11.1.4', true );

	// TUS
	wp_register_script( 'geeker-tus', WP_VIMEO_VIDEOS_URL . 'assets/resources/tus-js-client/tus.min.js', null, '1.8.0' );


	// Select 2
	wp_register_script( 'select2-js', WP_VIMEO_VIDEOS_URL . 'assets/js/select2.min.js', null, '4
	0', false );

	// Uploader
	wp_register_script( 'geeker-uploader', WP_VIMEO_VIDEOS_URL . 'assets/js/uploader.js', array( 'geeker-tus' ), time() );
	
	// Public
	wp_register_script( 'geeker-vimeo', WP_VIMEO_VIDEOS_URL . 'assets/js/admin.js', array( 'jquery', 'geeker-uploader' ), time(), true );

	
	// Public
	wp_register_script( 'geeker-script', WP_VIMEO_VIDEOS_URL . 'assets/js/script.js', array( 'jquery', 'geeker-uploader' ), time(), true );
	
	wp_enqueue_style('select2-css');
	wp_enqueue_style('select2-bootstrap-css');
	wp_enqueue_script('select2-js');

	// Sweetalert
	wp_enqueue_script( 'geeker-swal' );
	// TUS
	wp_enqueue_script( 'geeker-tus' );
	// Uploader
	wp_enqueue_script( 'geeker-uploader' );
	// Admin
	wp_enqueue_script( 'geeker-vimeo' );
	wp_enqueue_script( 'geeker-script' );
	wp_localize_script( 'geeker-script', 'geeker_obj_script', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	));
	wp_localize_script( 'geeker-vimeo', 'DGV', array(
		'nonce'               => wp_create_nonce( 'dgvsecurity' ),
		'ajax_url'            => admin_url( 'admin-ajax.php' ),
		'access_token'        => WP_VIMEO_ACCESS_TOKEN,
		'api_scopes'          => array(
										'private',
										'purchased',
										'create',
										'edit',
										'delete',
										'interact',
										'upload',
										'promo_codes',
										'video_files',
										'scim',
										'public'
									),
		'default_privacy'     => apply_filters( 'dgv_default_privacy', 'anybody' ),
		'uploading'           => sprintf( '%s %s', '<img src="' . admin_url( 'images/spinner.gif' ) . '">', __( 'Uploading video. Please wait...', 'wp-vimeo-videos' ) ),
		'sorry'               => __( 'Sorry', 'wp-vimeo-videos' ),
		'upload_invalid_file' => __( 'Please select valid video file.', 'wp-vimeo-videos' ),
		'success'             => __( 'Success', 'wp-vimeo-videos' ),
		'cancel'              => __( 'Cancel', 'wp-vimeo-videos' ),
		'confirm'             => __( 'Confirm', 'wp-vimeo-videos' ),
		'close'               => __( 'Close', 'wp-vimeo-videos' ),
		'correct_errors'      => __( 'Please correct the following errors', 'wp-vimeo-videos' ),
		'problem_solution'    => __( 'Problem solution' ),
		'phrases'             => array(
			'select2' => array(
				'errorLoading'    => __( 'The results could not be loaded.', 'wp-vimeo-videos' ),
				'inputTooLong'    => __( 'Please delete {number} character', 'wp-vimeo-videos' ),
				'inputTooShort'   => __( 'Please enter {number} or more characters', 'wp-vimeo-videos' ),
				'loadingMore'     => __( 'Loading more results...', 'wp-vimeo-videos' ),
				'maximumSelected' => __( 'You can only select {number} item', 'wp-vimeo-videos' ),
				'noResults'       => __( 'No results found', 'wp-vimeo-videos' ),
				'searching'       => __( 'Searching...', 'wp-vimeo-videos' ),
				'removeAllItems'  => __( 'Remove all items', 'wp-vimeo-videos' ),
				'removeItem'      => __( 'Remove item', 'wp-vimeo-videos' ),
				'search'          => __( 'Search', 'wp-vimeo-videos' ),
			)
		)
	) );
}

add_action( 'wp_enqueue_scripts', 'geeker_vimeo_enqueue_scripts_callback' );





/* function geeker_get_logtag( $context ) {
	if ( $context === 'backend' ) {
		$tag = 'DGV-ADMIN-HOOKS';
	} else if ( $context === 'frontend' ) {
		$tag = 'DGV-FRONTEND-HOOKS';
	} else {
		$tag = 'DGV-INTERNAL-HOOKS';
	}
	return $tag;
} */