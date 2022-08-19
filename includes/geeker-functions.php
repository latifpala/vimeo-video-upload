<?php
function geeker_get_organizations(){
    $args = array(
        'post_type' => 'gd_place',
        'posts_per_page' => 100,
        'order' => 'ASC',
        'post_status' => 'publish'
    );
    $organizations = get_posts($args);
    return $organizations;
}
function geeker_get_prices(){
    $prices = array();
    $i = 0.99;
    $prices[] = 0.99;
    while($i < 99){
        if($i<49){
            $i++;
        }elseif($i>49 && $i<99){
            $i = $i+5; 
        }
        /* elseif($i>149 && $i<249){
            $i = $i+10; 
        }elseif($i>249 && $i<499){
            $i = $i+50; 
        }elseif($i>499 && $i<999){
            $i = $i+100;
        } */
        $prices[] = $i;
    }
    return $prices;
}

function geeker_manage_wc_product( $product_title, $product_description, $price, $product_id = 0 ){

    if($product_id == 0){
        $product_id = wp_insert_post( array(
            'post_title' => $product_title,
            'post_type' => 'product',
            'post_status' => 'publish',
            'post_content' => $product_description,
            'post_author' => get_current_user_id()
        ));
    }else{
        wp_update_post( array(
            'ID' => $product_id,
            'post_title' => $product_title,
            'post_type' => 'product',
            'post_status' => 'publish',
            'post_content' => $product_description,
            'post_author' => get_current_user_id()
        ));
    }
    $product = wc_get_product( $product_id );
    
    wp_set_object_terms( $product_id, 'simple', 'product_type');

    $product->set_virtual('yes');
    
    $product->set_regular_price($price);
    $product->set_price($price);
    $product->save();
    wp_remove_object_terms( $product_id, 'simple', 'product_type' );
    wp_set_object_terms( $product_id, 'course', 'product_type');


    return $product_id;
}

function geeker_manage_lms($video_title, $video_description, $categories = array(), $tags = array(), $video_url, $price_type, $price, $product_id = 0, $course_id = 0, $lesson_id = 0){
    $is_edit = false;
    if($course_id!=0 && $lesson_id!=0){
        $is_edit = true;
    }
    
    $price_type = 'closed';

    $lesson_args = array(
		'post_title' => $video_title,
		'post_content' => $video_url,
		'post_author' => get_current_user_id(),
		'post_type' => 'sfwd-lessons',
		'post_status' => 'publish',
	);
    if($is_edit){
        $lesson_args['ID'] = $lesson_id;
        wp_update_post($lesson_args);
    }else{
	    $lesson_id = wp_insert_post($lesson_args);
    }
    
    if($is_edit){
        // remove all tags and categories
        $taxonomies = array( 'category', 'post_tag' );
        wp_delete_object_term_relationships($lesson_id, $taxonomies);
    }
    
    // add categories and tags here 
    if(!empty($categories)){
        foreach($categories as $category){
            wp_set_object_terms($lesson_id, $category, 'category', true);
        }
    }

    if(!empty($tags)){
        foreach($tags as $tag){
            wp_set_object_terms($lesson_id, $tag, 'post_tag', true);
        }
    }

    $course_args = array(
		'post_title' => $video_title,
		'post_content' => $video_description,
		'post_author' => get_current_user_id(),
		'post_type' => 'sfwd-courses',
		'post_status' => 'publish',
	);
    if($is_edit){
        $course_args['ID'] = $course_id;
        wp_update_post($course_args);
    }else{
        $course_id = wp_insert_post($course_args);
    }

    $steps_array = geeker_get_steps_array($course_id, $lesson_id);
    $courses_array = geeker_get_courses_common_args();
    $lesson_arr = array(
        '0' => '',
        'sfwd-lessons_course' => $course_id
    );

    /* Update lesson keys */
    update_post_meta($lesson_id, 'course_id', $course_id);
    update_post_meta($lesson_id, '_sfwd-lessons', $lesson_arr);
    

    update_post_meta($course_id,    'ld_course_steps', $steps_array);
    update_post_meta($course_id, '_ld_course_steps_count', 1);
    
    if($price_type=='free'){
        update_post_meta($course_id, '_ld_price_type', 'free'); 
        $courses_array['sfwd-courses_course_price_type'] = 'free';
    }elseif($price_type=='one-time'){
        update_post_meta($course_id, '_ld_price_type', 'paynow');
        $courses_array['sfwd-courses_course_price_type'] = 'paynow';
        $courses_array['sfwd-courses_course_price'] = $price;
        
        /* 
        _sfwd-courses
        [sfwd-courses_course_price_type] => paynow
        [sfwd-courses_course_price] => 9.99
        [sfwd-courses_course_price_type_paynow_enrollment_url] =>  */
    }elseif($price_type=="recurring"){
        update_post_meta($course_id, '_ld_price_type', 'subscribe');
        update_post_meta($course_id, 'course_price_billing_p3', '24');
        update_post_meta($course_id, 'course_price_billing_t3', 'M');
        
        $courses_array['sfwd-courses_course_price_type'] = 'subscribe';
        $courses_array['sfwd-courses_course_price_billing_p3'] = 24;
        $courses_array['sfwd-courses_course_trial_duration_p1'] = 0;
        $courses_array['sfwd-courses_course_price_billing_t3'] = 'M';
        $courses_array['sfwd-courses_course_price'] = $price;
        $courses_array['sfwd-courses_course_price_billing_cycle'] = '';
        $courses_array['sfwd-courses_course_no_of_cycles'] = '';
        $courses_array['sfwd-courses_course_price_type_subscribe_enrollment_url'] = '';
        /*
        _sfwd-courses
        [sfwd-courses_course_price_type] => subscribe
        [sfwd-courses_course_price_billing_p3] => 24
        [sfwd-courses_course_trial_duration_p1] => 0
        [sfwd-courses_course_price_billing_t3] => M
        [sfwd-courses_course_price] => 10.99
        [sfwd-courses_course_price_type_paynow_enrollment_url] => 
        [sfwd-courses_course_price_billing_cycle] => 
        [sfwd-courses_course_no_of_cycles] => 
        [sfwd-courses_course_price_type_subscribe_enrollment_url] =>  */
    }else{
        // pass blank to add woocommerce product

        update_post_meta($course_id, '_ld_price_type', 'closed');
        $courses_array['sfwd-courses_course_price_type'] = 'closed';
        $courses_array['sfwd-courses_course_price'] = $price;
    }
    
    update_post_meta($course_id, '_sfwd-courses', $courses_array);

    if($product_id > 0){
        // update product with course id
        update_post_meta($product_id, '_related_course', array($course_id));

        // update product URL in LMS
        $courses_array = get_post_meta($course_id, '_sfwd-courses', true);
        $courses_array['sfwd-courses_custom_button_url'] = site_url().'/?add-to-cart='.$product_id;
        update_post_meta($course_id, '_sfwd-courses', $courses_array);
    }

    return $course_id;
}
function geeker_get_steps_array($course_id, $lesson_id){
    $steps_array['steps']['h']['sfwd-lessons'][$lesson_id] = array(
        'sfwd-topic' => array(),
        'sfwd-quiz' => array()
    );
    $steps_array['steps']['h']['sfwd-quiz'] = array();
    
    $steps_array['course_id'] = $course_id;
    $steps_array['version'] = '4.1.0';
    $steps_array['empty'] = '';
    $steps_array['course_builder_enabled'] = 1;
    $steps_array['course_shared_steps_enabled'] = '';
    $steps_array['steps_count'] = 1;
    return $steps_array;
}

function geeker_get_courses_common_args(){
    $common_args = array(
        '0' => '',
        'sfwd-courses_course_materials_enabled' => '',
        'sfwd-courses_course_materials' => '',
        'sfwd-courses_certificate' => '',
        'sfwd-courses_exam_challenge' => 0,
        'sfwd-courses_course_disable_content_table' => '',
        'sfwd-courses_course_lesson_per_page' => '',
        'sfwd-courses_course_lesson_per_page_custom' => '',
        'sfwd-courses_course_topic_per_page_custom' => '',
        'sfwd-courses_course_lesson_order_enabled' => '',
        'sfwd-courses_course_lesson_orderby' => 'menu_order',
        'sfwd-courses_course_lesson_order' => 'ASC',
        'sfwd-courses_course_prerequisite_enabled' => '',
        'sfwd-courses_course_prerequisite' => '',
        'sfwd-courses_course_prerequisite_compare' => 'ANY',
        'sfwd-courses_course_points_enabled' => '',
        'sfwd-courses_course_points' => '',
        'sfwd-courses_course_points_access' => '',
        'sfwd-courses_expire_access' => '',
        'sfwd-courses_expire_access_days' => 0,
        'sfwd-courses_expire_access_delete_progress' => '',
        'sfwd-courses_course_price_billing_p3' => '',
        'sfwd-courses_course_trial_price' => '',
        'sfwd-courses_course_trial_duration_t1' => '',
        'sfwd-courses_course_trial_duration_p1' => '',
        'sfwd-courses_course_price_billing_t3' => '',
        'sfwd-courses_course_price' => '',
        'sfwd-courses_custom_button_url' => '',
        'sfwd-courses_course_disable_lesson_progression' => '',
        'sfwd-courses_course_price_type_paynow_enrollment_url' => ''
    );
    return $common_args;
}

function geeker_manage_buddyboss_product($name, $description, $benefits, $price_type, $price, $course_id, $bb_app_id = 0){
    $is_edit = false;
    if($bb_app_id != 0){
        $is_edit = true;
    }
    if($price_type=='free'){
        $price_type_app = 'free';
        $store_product_data['store_product_types'] = array(
            'ios' => '',
            'android' => ''
        );
        $store_product_data['store_product_ids'] = array(
            'ios' => '',
            'android' => ''
        );
    }else{
        $price_type_app = 'paid';
        if($price_type=='one-time'){
            $store_product_data['store_product_types'] = array(
                'ios' => 'consumable', //auto_renewable
                'android' => 'consumable'
            );
        }else{
            $store_product_data['store_product_types'] = array(
                'ios' => 'auto_renewable', 
                'android' => 'auto_renewable'
            );
        }

        $store_product_data['store_product_ids'] = geeker_get_buddyboss_device_products($price, $price_type);
    }

    $miscSettings = array(
        'integration_type' => 'learndash-course',
        'benefits' => $benefits,
        'global_subscription' => 0,
        'course_access' => 0
    );
    
    $storeData = array(
        'bbapp_product_type' => $price_type_app, 
        'device_platforms' => array(
            'ios' => 'ios',
            'android' => 'android',
        ),
        'store_product_types' => $store_product_data['store_product_types'],
        'store_product_ids' => $store_product_data['store_product_ids']
    );  
    
    $integrationData['learndash-course'] = array(
        $course_id.":".get_the_title($course_id)
    );

    $bb_app_arr = array(
        'name'             => trim( $name ),
        'tagline'          => trim( $description ),
        'description'      => trim( $description ),
        'misc_settings'    => serialize( $miscSettings ),
        'store_data'       => serialize( $storeData ),
        'integration_data' => serialize( $integrationData ),
        'iap_group'        => 0,
    );
    // The function is from buddyboss app purchase plugin
    if($is_edit){
        if(function_exists('bbapp_iap_update_product')){
            $is_updated = bbapp_iap_update_product( $bb_app_id, $bb_app_arr );
        }
    }else{
        if(function_exists('bbapp_iap_create_product')){
            $createProduct = bbapp_iap_create_product( $bb_app_arr );
            return $createProduct['id'];
        }
    }
    return 0;
}

function geeker_get_buddyboss_device_products($price, $price_type){
    $price_array = "";
    $android_product = $ios_product = '';
    $all_products = array(
        '0.99' => array(
            'android_non_consumable' => '0000000001',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000001',
            'ios_renewable' => '0000000002',
        ),
        '1.99' => array(
            'android_non_consumable' => '0000000002',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000004',
            'ios_renewable' => '0000000003',
        ),
        '2.99' => array(
            'android_non_consumable' => '0000000003',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000022',
            'ios_renewable' => '0000000021',
        ),
        '3.99' => array(
            'android_non_consumable' => '0000000004',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000013',
            'ios_renewable' => '0000000023',
        ),
        '4.99' => array(
            'android_non_consumable' => '0000000005',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000025',
            'ios_renewable' => '0000000024',
        ),
        '5.99' => array(
            'android_non_consumable' => '0000000006',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000005',
            'ios_renewable' => '0000000006',
        ),
        '6.99' => array(
            'android_non_consumable' => '0000000007',
            'android_renewable' => '',
            'ios_non_consumable' => 'com.myworshipfinder.tier7',
            'ios_renewable' => 'com.myworshipfinder.tier7n',
        ),
        '7.99' => array(
            'android_non_consumable' => '0000000008',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000020',
            'ios_renewable' => '0000000019',
        ),
        '8.99' => array(
            'android_non_consumable' => '0000000009',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000028',
            'ios_renewable' => '0000000026',
        ),
        '9.99' => array(
            'android_non_consumable' => '0000000010',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000029',
            'ios_renewable' => '0000000027',
        ),
        '10.99' => array(
            'android_non_consumable' => '0000000011',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000007',
            'ios_renewable' => '0000000008',
        ),
        '11.99' => array(
            'android_non_consumable' => '0000000012',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '12.99' => array(
            'android_non_consumable' => '0000000013',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '13.99' => array(
            'android_non_consumable' => '0000000014',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '14.99' => array(
            'android_non_consumable' => '0000000015',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000009',
            'ios_renewable' => '0000000010',
        ),
        '15.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000030',
            'ios_renewable' => '',
        ),
        '16.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000031',
            'ios_renewable' => '',
        ),
        '17.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000032',
            'ios_renewable' => '',
        ),
        '18.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000033',
            'ios_renewable' => '',
        ),
        '19.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000034',
            'ios_renewable' => '',
        ),
        '20.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000035',
            'ios_renewable' => '',
        ),
        '21.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000036',
            'ios_renewable' => '',
        ),
        '22.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000037',
            'ios_renewable' => '',
        ),
        '23.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000038',
            'ios_renewable' => '',
        ),
        '24.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000039',
            'ios_renewable' => '',
        ),
        '25.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000040',
            'ios_renewable' => '',
        ),
        '26.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000041',
            'ios_renewable' => '',
        ),
        '27.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000042',
            'ios_renewable' => '',
        ),
        '28.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000043',
            'ios_renewable' => '',
        ),
        '29.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000044',
            'ios_renewable' => '',
        ),
        '30.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000045',
            'ios_renewable' => '',
        ),
        '31.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '0000000046',
            'ios_renewable' => '',
        ),
        '32.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '33.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '34.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '35.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '36.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '37.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '38.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '39.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '40.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '41.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '42.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '43.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '44.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '45.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '46.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '47.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '48.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '49.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '54.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '59.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '64.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '69.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '74.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '79.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '84.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '89.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '94.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
        '99.99' => array(
            'android_non_consumable' => '',
            'android_renewable' => '',
            'ios_non_consumable' => '',
            'ios_renewable' => '',
        ),
    );
    
    if($price_type=="one-time"){
        $ios_product = $all_products["{$price}"]['ios_non_consumable'];
        $android_product = $all_products["{$price}"]['android_non_consumable'];
    }else{
        $ios_product = $all_products["{$price}"]['ios_renewable'];
        $android_product = $all_products["{$price}"]['android_renewable'];
    }
    
    return array(
        'ios' => $ios_product,
        'android' => $android_product
    );
}  

function geeker_video_entry($product_id = 0, $course_id = 0, $bb_app_id = 0){
    global $wpdb;
    $table = $wpdb->prefix."geeker_videos";
    $data = array(
        'user_id' => get_current_user_id(),
        'product_id' => $product_id,
        'course_id' => $course_id,
        'bb_app_id' => $bb_app_id,
        'date' => date("Y-m-d H:i:s")
    );
    $wpdb->insert($table, $data);
    return $wpdb->insert_id;
}



function geeker_vimeo_wvv_uri_to_id( $uri ) {

	if ( is_array( $uri ) ) {
		if ( isset( $uri['body']['uri'] ) ) {
			$uri = $uri['body']['uri'];
		}
	}

	if ( ! is_string( $uri ) ) {
		return $uri;
	}

	$parts = explode( '/', $uri );
	return end( $parts );
}


if(isset($_REQUEST['testing']))
    add_action('init', 'test_array');

function test_array(){
    global $wpdb;
    
    $res = geeker_delete_video(718354502);
    geeker_print($res);
    die;
    if(class_exists('BuddyBossApp\Admin\InAppPurchases\ProductHelper')){
        $obj = new BuddyBossApp\Admin\InAppPurchases\ProductHelper();
        $test = $obj->testing_obj();
        echo $test;
    }
    die;

    /* global $wpdb;
    $table = $wpdb->prefix.'geeeker_formdata';

    $qry = "SELECT * FROM {$table} where id=37";
    $res = $wpdb->get_results($qry);
    
    $formdata = maybe_unserialize($res[0]->formdata);

    geeker_print($formdata); */

    /* Array
(
    [bbapp_product_type] => paid
    [device_platforms] => Array
        (
            [ios] => ios
            [android] => android
        )

    [store_product_types] => Array
        (
            [ios] => consumable
            [android] => consumable
        )

    [store_product_ids] => Array
        (
            [ios] => 0000000005
            [android] => tier6.nc
        )

)
 */
    //geeker_print($misc_settings);
    /* Array
    (
        [integration_type] => learndash-course
        [benefits] => Array
            (
                [0] => Full Course Access
                [1] => New Lessons Every Week
            )
    
        [global_subscription] => 0
        [course_access] => 0
    ) */

   // geeker_print($integration_data);
   /*  [learndash-course] => Array
        (
            [0] => 151806:Embrace Today\'s Glory
        )
 */
   // geeker_print($res);
    die;
}
function geeker_print($res, $die = false){
    echo "<pre>";
    print_r($res);
    echo "</pre>";
    if($die){
        die;
    }
}