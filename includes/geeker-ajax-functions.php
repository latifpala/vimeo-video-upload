<?php
function geeker_vimeo_store_upload_callback() {
	global $wpdb;
	$data_id = $_POST['data_id'];

	$table = $wpdb->prefix.'geeeker_formdata';
	$qry = "SELECT * FROM ".$table.' WHERE ID='.$data_id;
	$res = $wpdb->get_results($qry, ARRAY_A);

	$data = maybe_unserialize($res[0]['formdata']);
	$files = maybe_unserialize($res[0]['files']);
	$user_id = $res[0]['user_id'];
	
	
	$title       = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : __( 'Untitled', 'wp-vimeo-videos' );
	$description = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
	$size        = isset( $_POST['size'] ) ? intval( $_POST['size'] ) : false;
	$uri         = sanitize_text_field( $_POST['uri'] );
	$video_id    = geeker_vimeo_wvv_uri_to_id( $uri );

	$featured_image_id = $files['featured_image_id'];
	$cover_image_id = $files['cover_image_id'];


	$vimeo_video_title = isset($data['vimeo_video_title']) ? sanitize_text_field($data['vimeo_video_title']) : '';
	$vimeo_video_description = isset($data['vimeo_video_description']) ? sanitize_text_field($data['vimeo_video_description']) : '';
	$vimeo_video_recording_date = isset($data['vimeo_video_recording_date']) ? sanitize_text_field($data['vimeo_video_recording_date']) : '';
	
	$video_organization = isset($data['video_organization'])?$data['video_organization']:'';
	$video_organization_city = isset($data['video_organization_city']) ? sanitize_text_field($data['video_organization_city']) : '';
	$video_organization_state = isset($data['video_organization_state']) ? sanitize_text_field($data['video_organization_state']) : '';
	
	$video_organization_denomination = isset($data['video_organization_denomination']) ? sanitize_text_field($data['video_organization_denomination']) : '';
	$video_organization_denomination_ids = isset($data['video_organization_denomination_ids']) ? sanitize_text_field($data['video_organization_denomination_ids']) : array();
	
	$video_search_tags = isset($data['video_search_tags']) ? $data['video_search_tags'] : array();
	
	
	$primary_speaker_user_name = isset($data['primary_speaker_user_name']) ? sanitize_text_field($data['primary_speaker_user_name']) : '';
	$primary_speaker_first_name = isset($data['primary_speaker_first_name']) ? sanitize_text_field($data['primary_speaker_first_name']) : '';
	$primary_speaker_last_name = isset($data['primary_speaker_last_name']) ? sanitize_text_field($data['primary_speaker_last_name']) : '';
	
	$second_speaker_user_name = isset($data['second_speaker_user_name']) ? sanitize_text_field($data['second_speaker_user_name']) : '';
	$second_speaker_first_name = isset($data['second_speaker_first_name']) ? sanitize_text_field($data['second_speaker_first_name']) : '';
	$second_speaker_last_name = isset($data['second_speaker_last_name']) ? sanitize_text_field($data['second_speaker_last_name']) : '';
	$second_speaker_name = isset($data['second_speaker_name']) ? sanitize_text_field($data['second_speaker_name']) : '';


	$third_speaker_user_name = isset($data['third_speaker_user_name']) ? sanitize_text_field($data['third_speaker_user_name']) : '';
	$third_speaker_first_name = isset($data['third_speaker_first_name']) ? sanitize_text_field($data['third_speaker_first_name']) : '';
	$third_speaker_last_name = isset($data['third_speaker_last_name']) ? sanitize_text_field($data['third_speaker_last_name']) : '';
	$third_speaker_name = isset($data['third_speaker_name']) ? sanitize_text_field($data['third_speaker_name']) : '';

	$folder_uri = isset($data['vimeo_folder']) ? sanitize_text_field($data['vimeo_folder']) : '';
	$new_vimeo_folder = isset($data['new_vimeo_folder']) ? sanitize_text_field($data['new_vimeo_folder']) : '';

	$benefit_1 = isset($data['benefit-1']) ? $data['benefit-1']:'';
	$benefit_2 = isset($data['benefit-2']) ? $data['benefit-2']:'';
	$benefit_3 = isset($data['benefit-3']) ? $data['benefit-3']:'';
	$benefits = array(
		$benefit_1,
		$benefit_2,
		$benefit_3,
	);

	$payment_type = isset($data['payment_type']) ? sanitize_text_field($data['payment_type']) : 'free';
	
	$vimeo_video_price = isset($data['vimeo_video_price']) ? sanitize_text_field($data['vimeo_video_price']) : '';
	$other_price = isset($data['other_price']) ? sanitize_text_field($data['other_price']) : '';
	
	$who_can_shop = isset($data['who_can_shop']) ? sanitize_text_field($data['who_can_shop']) : '';
	
	$max_vimeo_denomination = isset($data['max_vimeo_denomination']) ? sanitize_text_field($data['max_vimeo_denomination']) : array();
	$max_vimeo_groups = isset($data['max_vimeo_groups']) ? sanitize_text_field($data['max_vimeo_groups']) : array();
	
	$folder_id = 0;

	if($folder_uri != 'vimeo_add_new' || $folder_uri!=""){
		$folder_uri_parts = explode( '/', $folder_uri );
		$folder_id = end( $folder_uri_parts );
		geeker_move_video_to_folder($folder_id, $video_id);
	}

	if($folder_uri == 'vimeo_add_new'){
		$folder_uri = geeker_create_folder($new_vimeo_folder);
		$folder_uri_parts = explode( '/', $folder_uri );
		$folder_id = end( $folder_uri_parts );
		geeker_move_video_to_folder($folder_id, $video_id);
	}
	$video_url = 'https://vimeo.com/'.$video_id;

	$product_id = geeker_manage_wc_product($vimeo_video_title, $vimeo_video_description, $vimeo_video_price);
	
	$course_id = geeker_manage_lms($vimeo_video_title, $vimeo_video_description, $video_organization_denomination, $video_search_tags, $video_url, $payment_type, $vimeo_video_price, $product_id);
	
	$buddyboss_app_id = geeker_manage_buddyboss_product($vimeo_video_title, $vimeo_video_description, $benefits, $payment_type, $vimeo_video_price, $course_id);
	
	// start here
	if(isset($files['buddyboss_image'])){
		$iap_product_meta_table = $wpdb->prefix.'bbapp_iap_productmeta';
		$iap_data = array(
			'iap_id' => $buddyboss_app_id,
			'meta_key' => 'iap_product_image',
			'meta_value' => maybe_serialize($files['buddyboss_image'])
		);
		$wpdb->insert($iap_product_meta_table, $iap_data);
	}

	$video_entry_id = geeker_video_entry($product_id, $course_id, $buddyboss_app_id);

	if($featured_image_id > 0){
		update_post_meta($course_id, '_thumbnail_id', $featured_image_id);
	}
	if($cover_image_id > 0){
		update_post_meta($course_id, 'sfwd-courses_course-cover-image_thumbnail_id', $cover_image_id);
	}

	// for now stored in course meta. can be changed to product meta
	update_post_meta($product_id, 'vimeo_video_recording_date', $vimeo_video_recording_date);
	update_post_meta($product_id, 'video_organization', $video_organization);
	update_post_meta($product_id, 'video_organization_city', $video_organization_city);
	update_post_meta($product_id, 'video_organization_state', $video_organization_state);
	update_post_meta($product_id, 'video_organization_denomination', $video_organization_denomination);
	update_post_meta($product_id, 'video_organization_denomination_ids', $video_organization_denomination_ids);
	update_post_meta($product_id, 'video_search_tags', $video_search_tags);
	
	update_post_meta($product_id, 'primary_speaker_user_id', $primary_speaker_user_name);
	update_post_meta($product_id, 'primary_speaker_first_name', $primary_speaker_first_name);
	update_post_meta($product_id, 'primary_speaker_last_name', $primary_speaker_last_name);
	
	update_post_meta($product_id, 'second_speaker_user_id', $second_speaker_user_name);
	update_post_meta($product_id, 'second_speaker_first_name', $second_speaker_first_name);
	update_post_meta($product_id, 'second_speaker_last_name', $second_speaker_last_name);
	update_post_meta($product_id, 'second_speaker_name', $second_speaker_name);

	update_post_meta($product_id, 'third_speaker_user_id', $third_speaker_user_name);
	update_post_meta($product_id, 'third_speaker_first_name', $third_speaker_first_name);
	update_post_meta($product_id, 'third_speaker_last_name', $third_speaker_last_name);
	update_post_meta($product_id, 'third_speaker_name', $third_speaker_name);
	
	
	update_post_meta($product_id, 'vimeo_folder', $folder_uri);
	update_post_meta($product_id, 'new_vimeo_folder', $new_vimeo_folder);
	update_post_meta($product_id, 'folder_id', $folder_id);
	
	update_post_meta($product_id, 'benefit-1', $benefit_1);
	update_post_meta($product_id, 'benefit-2', $benefit_2);
	update_post_meta($product_id, 'benefit-3', $benefit_3);
		
	update_post_meta($product_id, 'payment_type', $payment_type);
	update_post_meta($product_id, 'vimeo_video_price', $vimeo_video_price);

	update_post_meta($product_id, 'who_can_shop', $who_can_shop);
	update_post_meta($product_id, 'max_vimeo_groups', $max_vimeo_groups);
	update_post_meta($product_id, 'max_vimeo_denomination', $max_vimeo_denomination);
	
	update_post_meta($product_id, 'video_id', $video_id);

	update_post_meta($product_id, 'buddyboss_app_product_id', $buddyboss_app_id);
	update_post_meta($product_id, 'course_id', $course_id);

	wp_send_json_success( array(
		'message' => __( 'Video uploaded successfully.', 'wp-vimeo-videos' ),
		'course_id' => $course_id,
		'id' => $video_entry_id
	) );
	exit;
}
add_action( 'wp_ajax_dgv_store_upload', 'geeker_vimeo_store_upload_callback' );
add_action( 'wp_ajax_nopriv_dgv_store_upload', 'geeker_vimeo_store_upload_callback' );


add_action( 'wp_ajax_geeker_get_organization_details', 'geeker_get_organization_details_callback' );
add_action( 'wp_ajax_nopriv_geeker_get_organization_details', 'geeker_get_organization_details_callback' );
function geeker_get_organization_details_callback(){
    global $wpdb;
    $organization_table = $wpdb->prefix.'geodir_gd_place_detail';
    $organization_id = sanitize_text_field($_POST['organization_id']);
    $get_organization_details_qry = "SELECT post_id, city, region, post_category FROM {$organization_table} WHERE post_id=".$organization_id;
    $get_organization_details = $wpdb->get_results($get_organization_details_qry);

    $state = $get_organization_details[0]->region;
    $city = $get_organization_details[0]->city;
    $categories = $get_organization_details[0]->post_category;
    $category_names = '';
    if($categories!='' && $categories!=NULL){
        $terms = get_the_terms( $organization_id, 'gd_placecategory' );
        $category_names = join(', ', wp_list_pluck($terms, 'name'));
    }
    wp_send_json_success( array(
		'city' => $city,
		'state' => $state,
        'category_ids' => $categories,
        'category_names' => $category_names
	) );
    exit;
}
add_action( 'wp_ajax_geeker_store_data_before_upload', 'geeker_store_data_before_upload_callback' );
add_action( 'wp_ajax_nopriv_geeker_store_data_before_upload', 'geeker_store_data_before_upload_callback' );
function geeker_store_data_before_upload_callback(){
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    global $wpdb;
    $table = $wpdb->prefix.'geeeker_formdata';
    $files = array('cover_image_id' => 0, 'featured_image_id' => 0, 'buddyboss_image' => array());
    
    if(isset($_FILES)){
        // upload it for buddyboss as well
        if(class_exists('BuddyBossApp\Admin\InAppPurchases\ProductHelper')){
            $buddyboss_app_obj = new BuddyBossApp\Admin\InAppPurchases\ProductHelper();
            $files['buddyboss_image'] = $buddyboss_app_obj->bbapp_iap_uploaded_feature_image($_FILES, 'vimeo_video_img', time());
        }

        if(isset($_FILES['vimeo_video_img'])){
            if($_FILES['vimeo_video_img']['name'] != ''){
                $resFile = wp_handle_upload( $_FILES['vimeo_video_img'], array( 'test_form' => false ) );
			    if ( $resFile && !isset( $resFile['error'] ) ){
                    //File is valid, and was successfully uploaded
                    $attachment = array(
                        'guid'           => $resFile['url'], 
                        'post_mime_type' => $resFile['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $_FILES["vimeo_video_img"]["name"]  ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
                    $featured_image_id =  wp_insert_attachment( $attachment, $resFile['file']);
                    $attach_data = wp_generate_attachment_metadata( $featured_image_id, $resFile['file'] );
                    wp_update_attachment_metadata( $featured_image_id, $attach_data );

                    $files['featured_image_id'] = $featured_image_id;
                }
            }
        }

        if(isset($_FILES['vimeo_video_cover_img'])){
            if($_FILES['vimeo_video_cover_img']['name'] != ''){
                $resFile = wp_handle_upload( $_FILES['vimeo_video_cover_img'], array( 'test_form' => false ) );
			    if ( $resFile && !isset( $resFile['error'] ) ){
                    //File is valid, and was successfully uploaded
                    $attachment = array(
                        'guid'           => $resFile['url'], 
                        'post_mime_type' => $resFile['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $_FILES["vimeo_video_cover_img"]["name"]  ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
                    $cover_image_id =  wp_insert_attachment( $attachment, $resFile['file']);
                    $attach_data = wp_generate_attachment_metadata( $cover_image_id, $resFile['file'] );
                    wp_update_attachment_metadata( $cover_image_id, $attach_data );

                    $files['cover_image_id'] = $cover_image_id;
                }
            }
        }
    }
    // ALTER TABLE `wp_geeeker_formdata` ADD `files` TEXT NOT NULL AFTER `formdata`; 
    $data = array(
        'formdata' => maybe_serialize($_POST),
        'files' => maybe_serialize($files),
        'date' => date('Y-m-d H:i:s'),
        'user_id' => get_current_user_id()
    );
    $wpdb->insert($table, $data);
    $id = $wpdb->insert_id;
    wp_send_json_success( array(
		'id' => $id
	) );
    exit;
}

add_action( 'wp_ajax_geeker_delete_video', 'geeker_delete_video_callback' );
add_action( 'wp_ajax_nopriv_geeker_delete_video', 'geeker_delete_video_callback' );
function geeker_delete_video_callback(){
	global $wpdb;
    $table = $wpdb->prefix."geeker_videos";
	$video_rec_id = sanitize_text_field($_POST['id']);
	$qry = "SELECT * FROM {$table} WHERE video_id=".$video_rec_id;
	$video_rec_obj = $wpdb->get_results($qry);
	$video_rec = $video_rec_obj[0];
	$product_id = $video_rec->product_id;

	$video_id = get_post_meta($product_id, 'video_id', true);
	
	if(get_current_user_id() != $video_rec->user_id){
		// check video ownership
		wp_send_json_success( array(
			'status' => 'failed',
			'message' => 'You are not authorized to delete this video',
		));	
		die;
	}

	//$is_video_deleted = geeker_delete_video($video_id);
	if($is_video_deleted){
		// delete product
		// delete LMS information
		// delete bp_app
		// update record in geeker_videos table. Change update date and is_deleted to 1
		
		$ret = $wpdb->update($table, array('is_deleted' => 1, 'updated_date' => date('Y-m-d H:i:s')), array('video_id' =>  $video_rec_id));
		wp_send_json_success( array(
			'status' => 'success',
			'message' => 'Video is deleted',
		));
	}
	die;
}

add_action( 'wp_ajax_geeker_update_data_without_video', 'geeker_update_data_without_video_callback' );
add_action( 'wp_ajax_nopriv_geeker_update_data_without_video', 'geeker_update_data_without_video_callback' );
function geeker_update_data_without_video_callback(){
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    $data = $_POST;
	$cover_image_id = isset($_POST['cover_image_id'])?$_POST['cover_image_id']:0;
	$featured_image_id = isset($_POST['featured_image_id'])?$_POST['featured_image_id']:0;
	$buddyboss_image = array();

	if(isset($_FILES)){
		if(isset($_FILES['vimeo_video_img'])){
            if($_FILES['vimeo_video_img']['name'] != ''){

				// upload it for buddyboss as well
				if(class_exists('BuddyBossApp\Admin\InAppPurchases\ProductHelper')){
					$buddyboss_app_obj = new BuddyBossApp\Admin\InAppPurchases\ProductHelper();
					$buddyboss_image = $buddyboss_app_obj->bbapp_iap_uploaded_feature_image($_FILES, 'vimeo_video_img', time());
				}

                $resFile = wp_handle_upload( $_FILES['vimeo_video_img'], array( 'test_form' => false ) );
			    if ( $resFile && !isset( $resFile['error'] ) ){
                    //File is valid, and was successfully uploaded
                    $attachment = array(
                        'guid'           => $resFile['url'], 
                        'post_mime_type' => $resFile['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $_FILES["vimeo_video_img"]["name"]  ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
                    $featured_image_id =  wp_insert_attachment( $attachment, $resFile['file']);
                    $attach_data = wp_generate_attachment_metadata( $featured_image_id, $resFile['file'] );
                    wp_update_attachment_metadata( $featured_image_id, $attach_data );

                }
            }
        }

        if(isset($_FILES['vimeo_video_cover_img'])){
            if($_FILES['vimeo_video_cover_img']['name'] != ''){
                $resFile = wp_handle_upload( $_FILES['vimeo_video_cover_img'], array( 'test_form' => false ) );
			    if ( $resFile && !isset( $resFile['error'] ) ){
                    //File is valid, and was successfully uploaded
                    $attachment = array(
                        'guid'           => $resFile['url'], 
                        'post_mime_type' => $resFile['type'],
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $_FILES["vimeo_video_cover_img"]["name"]  ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );
                    $cover_image_id =  wp_insert_attachment( $attachment, $resFile['file']);
                    $attach_data = wp_generate_attachment_metadata( $cover_image_id, $resFile['file'] );
                    wp_update_attachment_metadata( $cover_image_id, $attach_data );
                }
            }
        }
	}
	$video_id = isset($data['video_id']) ? sanitize_text_field($data['video_id']) : 0;
	$product_id = isset($data['product_id']) ? sanitize_text_field($data['product_id']) : 0;
	$course_id = isset($data['course_id']) ? sanitize_text_field($data['course_id']) : 0;
	$bb_app_id = isset($data['bb_app_id']) ? sanitize_text_field($data['bb_app_id']) : 0;
	$lesson = learndash_get_lesson_list($course_id);
	$lesson_id = $lesson[0]->ID;

	$video_url = 'https://vimeo.com/'.$video_id;

	$vimeo_video_title = isset($data['vimeo_video_title']) ? sanitize_text_field($data['vimeo_video_title']) : '';
	$vimeo_video_description = isset($data['vimeo_video_description']) ? sanitize_text_field($data['vimeo_video_description']) : '';
	$vimeo_video_recording_date = isset($data['vimeo_video_recording_date']) ? sanitize_text_field($data['vimeo_video_recording_date']) : '';
	
	$video_organization = isset($data['video_organization'])?$data['video_organization']:'';
	$video_organization_city = isset($data['video_organization_city']) ? sanitize_text_field($data['video_organization_city']) : '';
	$video_organization_state = isset($data['video_organization_state']) ? sanitize_text_field($data['video_organization_state']) : '';
	
	$video_organization_denomination = isset($data['video_organization_denomination']) ? sanitize_text_field($data['video_organization_denomination']) : '';
	$video_organization_denomination_ids = isset($data['video_organization_denomination_ids']) ? sanitize_text_field($data['video_organization_denomination_ids']) : array();
	
	$video_search_tags = isset($data['video_search_tags']) ? $data['video_search_tags'] : array();
	
	
	$primary_speaker_user_name = isset($data['primary_speaker_user_name']) ? sanitize_text_field($data['primary_speaker_user_name']) : '';
	$primary_speaker_first_name = isset($data['primary_speaker_first_name']) ? sanitize_text_field($data['primary_speaker_first_name']) : '';
	$primary_speaker_last_name = isset($data['primary_speaker_last_name']) ? sanitize_text_field($data['primary_speaker_last_name']) : '';
	
	$second_speaker_user_name = isset($data['second_speaker_user_name']) ? sanitize_text_field($data['second_speaker_user_name']) : '';
	$second_speaker_first_name = isset($data['second_speaker_first_name']) ? sanitize_text_field($data['second_speaker_first_name']) : '';
	$second_speaker_last_name = isset($data['second_speaker_last_name']) ? sanitize_text_field($data['second_speaker_last_name']) : '';
	$second_speaker_name = isset($data['second_speaker_name']) ? sanitize_text_field($data['second_speaker_name']) : '';


	$third_speaker_user_name = isset($data['third_speaker_user_name']) ? sanitize_text_field($data['third_speaker_user_name']) : '';
	$third_speaker_first_name = isset($data['third_speaker_first_name']) ? sanitize_text_field($data['third_speaker_first_name']) : '';
	$third_speaker_last_name = isset($data['third_speaker_last_name']) ? sanitize_text_field($data['third_speaker_last_name']) : '';
	$third_speaker_name = isset($data['third_speaker_name']) ? sanitize_text_field($data['third_speaker_name']) : '';

	$folder_uri = isset($data['vimeo_folder']) ? sanitize_text_field($data['vimeo_folder']) : '';
	$new_vimeo_folder = isset($data['new_vimeo_folder']) ? sanitize_text_field($data['new_vimeo_folder']) : '';

	$benefit_1 = isset($data['benefit-1']) ? $data['benefit-1']:'';
	$benefit_2 = isset($data['benefit-2']) ? $data['benefit-2']:'';
	$benefit_3 = isset($data['benefit-3']) ? $data['benefit-3']:'';
	$benefits = array(
		$benefit_1,
		$benefit_2,
		$benefit_3,
	);

	$payment_type = isset($data['payment_type']) ? sanitize_text_field($data['payment_type']) : 'free';

	
	$vimeo_video_price = isset($data['vimeo_video_price']) ? sanitize_text_field($data['vimeo_video_price']) : '';
	$other_price = isset($data['other_price']) ? sanitize_text_field($data['other_price']) : '';
	
	$who_can_shop = isset($data['who_can_shop']) ? sanitize_text_field($data['who_can_shop']) : '';
	
	$max_vimeo_denomination = isset($data['max_vimeo_denomination']) ? sanitize_text_field($data['max_vimeo_denomination']) : array();
	$max_vimeo_groups = isset($data['max_vimeo_groups']) ? sanitize_text_field($data['max_vimeo_groups']) : array();

	geeker_update_video_data($video_id, $vimeo_video_title, $vimeo_video_description); // working
	
	geeker_manage_wc_product($vimeo_video_title, $vimeo_video_description, $vimeo_video_price, $product_id); // working
	
	geeker_manage_lms($vimeo_video_title, $vimeo_video_description, $video_organization_denomination, $video_search_tags, $video_url, $payment_type, $vimeo_video_price, $product_id, $course_id, $lesson_id); // working

	geeker_manage_buddyboss_product($vimeo_video_title, $vimeo_video_description, $benefits, $payment_type, $vimeo_video_price, $course_id, $bb_app_id); // working

	if(!empty($buddyboss_image)){
		$iap_product_meta_table = $wpdb->prefix.'bbapp_iap_productmeta';
		$iap_data = array(
			'meta_value' => maybe_serialize($buddyboss_image),
		);
		$where = array(
			'iap_id' => $bb_app_id,
			'meta_key' => 'iap_product_image',
		);
		$wpdb->update($iap_product_meta_table, $iap_data, $where);
	}


	if($featured_image_id > 0){
		update_post_meta($course_id, '_thumbnail_id', $featured_image_id);
	}
	if($cover_image_id > 0){
		update_post_meta($course_id, 'sfwd-courses_course-cover-image_thumbnail_id', $cover_image_id);
	}

	update_post_meta($product_id, 'vimeo_video_recording_date', $vimeo_video_recording_date);
	update_post_meta($product_id, 'video_organization', $video_organization);
	update_post_meta($product_id, 'video_organization_city', $video_organization_city);
	update_post_meta($product_id, 'video_organization_state', $video_organization_state);
	update_post_meta($product_id, 'video_organization_denomination', $video_organization_denomination);
	update_post_meta($product_id, 'video_organization_denomination_ids', $video_organization_denomination_ids);
	update_post_meta($product_id, 'video_search_tags', $video_search_tags);
	
	update_post_meta($product_id, 'primary_speaker_user_id', $primary_speaker_user_name);
	update_post_meta($product_id, 'primary_speaker_first_name', $primary_speaker_first_name);
	update_post_meta($product_id, 'primary_speaker_last_name', $primary_speaker_last_name);
	
	update_post_meta($product_id, 'second_speaker_user_id', $second_speaker_user_name);
	update_post_meta($product_id, 'second_speaker_first_name', $second_speaker_first_name);
	update_post_meta($product_id, 'second_speaker_last_name', $second_speaker_last_name);
	update_post_meta($product_id, 'second_speaker_name', $second_speaker_name);

	update_post_meta($product_id, 'third_speaker_user_id', $third_speaker_user_name);
	update_post_meta($product_id, 'third_speaker_first_name', $third_speaker_first_name);
	update_post_meta($product_id, 'third_speaker_last_name', $third_speaker_last_name);
	update_post_meta($product_id, 'third_speaker_name', $third_speaker_name);
	
	
	update_post_meta($product_id, 'vimeo_folder', $folder_uri);
	update_post_meta($product_id, 'new_vimeo_folder', $new_vimeo_folder);
	update_post_meta($product_id, 'folder_id', $folder_id);
	
	update_post_meta($product_id, 'benefit-1', $benefit_1);
	update_post_meta($product_id, 'benefit-2', $benefit_2);
	update_post_meta($product_id, 'benefit-3', $benefit_3);
		
	update_post_meta($product_id, 'payment_type', $payment_type);
	update_post_meta($product_id, 'vimeo_video_price', $vimeo_video_price);

	update_post_meta($product_id, 'who_can_shop', $who_can_shop);
	update_post_meta($product_id, 'max_vimeo_groups', $max_vimeo_groups);
	update_post_meta($product_id, 'max_vimeo_denomination', $max_vimeo_denomination);
	
	update_post_meta($product_id, 'video_id', $video_id);

	update_post_meta($product_id, 'buddyboss_app_product_id', $buddyboss_app_id);
	update_post_meta($product_id, 'course_id', $course_id);

	wp_send_json_success( array(
		'status' => 'success',
		'message' => 'Video is updated',
		'bb_app_id' => $bb_app_id
	));
	die;
}


?>