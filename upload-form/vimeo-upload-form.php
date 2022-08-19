<?php 
	global $wpdb;
	$vimeo_video_title = $vimeo_video_description = $vimeo_video_recording_date = $video_organization = $video_organization_city = $video_organization_state = $video_organization_denomination = $video_organization_denomination_ids = $video_search_tags_selected = $primary_speaker_user_name = $primary_speaker_first_name = $primary_speaker_last_name = $second_speaker_user_name = $second_speaker_first_name = $second_speaker_last_name = $second_speaker_name = $third_speaker_user_name = $third_speaker_first_name = $third_speaker_last_name = $third_speaker_name = $vimeo_folder_selected = $new_vimeo_folder = $benefit_1 = $benefit_2 = $benefit_3 = $payment_type = $vimeo_video_price = $who_can_shop = "";
	
	$uploaded_video_id = $folder_id = $featured_image_id = $cover_image_id = $video_record_id = 0;
	
	$max_vimeo_groups = $max_vimeo_denomination = array();

	$is_edit = false;
	
	if(isset($_REQUEST['id'])){
		//ALTER TABLE `wp_geeker_videos` ADD `updated_date` DATETIME NULL DEFAULT NULL AFTER `date`;
		$is_edit = true;

		$video_record_id = sanitize_text_field($_REQUEST['id']);
		$videos_table = $wpdb->prefix.'geeker_videos';
		$video_record_qry = "SELECT * FROM {$videos_table} WHERE video_id=".$video_record_id;
		$video_data = $wpdb->get_results($video_record_qry);
		$product_id = $video_data[0]->product_id;
		$course_id = $video_data[0]->course_id;
		$bb_app_id = $video_data[0]->bb_app_id;

		$product_data = get_post($product_id);
		$vimeo_video_title = $product_data->post_title;
		$vimeo_video_description = $product_data->post_content;

		$vimeo_video_recording_date = get_post_meta($product_id, 'vimeo_video_recording_date', true);
		$video_organization = get_post_meta($product_id, 'video_organization', true);
		$video_organization_city = get_post_meta($product_id, 'video_organization_city', true);
		$video_organization_state = get_post_meta($product_id, 'video_organization_state', true);
		$video_organization_denomination = get_post_meta($product_id, 'video_organization_denomination', true);
		$video_organization_denomination_ids = get_post_meta($product_id, 'video_organization_denomination_ids', true);
		$video_search_tags_selected = get_post_meta($product_id, 'video_search_tags', true);

		$primary_speaker_user_id = get_post_meta($product_id, 'primary_speaker_user_id', true);
		
		$second_speaker_user_id = get_post_meta($product_id, 'second_speaker_user_id', true);
		$second_speaker_first_name = get_post_meta($product_id, 'second_speaker_first_name', true);
		$second_speaker_last_name = get_post_meta($product_id, 'second_speaker_last_name', true);
		$second_speaker_name = get_post_meta($product_id, 'second_speaker_name', true);

		$third_speaker_user_id = get_post_meta($product_id, 'third_speaker_user_id', true);
		$third_speaker_first_name = get_post_meta($product_id, 'third_speaker_first_name', true);
		$third_speaker_last_name = get_post_meta($product_id, 'third_speaker_last_name', true);
		$third_speaker_name = get_post_meta($product_id, 'third_speaker_name', true);

		$featured_image_id = get_post_meta($course_id, '_thumbnail_id', true);
		$cover_image_id = get_post_meta($course_id, 'sfwd-courses_course-cover-image_thumbnail_id', true);

		$vimeo_folder_selected = get_post_meta($product_id, 'vimeo_folder', true);
		$new_vimeo_folder = get_post_meta($product_id, 'new_vimeo_folder', true);
		$folder_id = get_post_meta($product_id, 'folder_id', true);
		
		$benefit_1 = get_post_meta($product_id, 'benefit-1', true);
		$benefit_2 = get_post_meta($product_id, 'benefit-2', true);
		$benefit_3 = get_post_meta($product_id, 'benefit-3', true);
		
		$payment_type = get_post_meta($product_id, 'payment_type', true);
		$vimeo_video_price = get_post_meta($product_id, 'vimeo_video_price', true);
		
		$who_can_shop = get_post_meta($product_id, 'who_can_shop', true);
		$max_vimeo_groups = get_post_meta($product_id, 'max_vimeo_groups', true);
		$max_vimeo_denomination = get_post_meta($product_id, 'max_vimeo_denomination', true);

		$uploaded_video_id = get_post_meta($product_id, 'video_id', true);
	}

	$organizations = geeker_get_organizations();
	$vimeo_folders = geeker_get_folders();
	$prices = geeker_get_prices();

	$current_user_id = get_current_user_id();
	$current_user = get_user_by('ID', $current_user_id);
	$first_name = $current_user->first_name;
	$last_name = $current_user->last_name;
	$display_name = $current_user->display_name;

	$other_users_args = array(
		'exclude' => array($current_user_id),
		'orderby' => 'display_name',
		'order' => 'ASC'
	);
	$users = get_users($other_users_args);

?>

<div class="container">
	<?php
	if($is_edit){ ?>
    	<form class="wvv-video-upload-edit" enctype="multipart/form-data" method="post" action="/">
		<input type="hidden" name="data-id" id="data-id" value="<?php echo $video_record_id; ?>" />
		<input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>" />
		<input type="hidden" name="course_id" id="course_id" value="<?php echo $course_id; ?>" />
		<input type="hidden" name="bb_app_id" id="bb_app_id" value="<?php echo $bb_app_id; ?>" />
	<?php
	}else{ ?>
		<form class="wvv-video-upload" enctype="multipart/form-data" method="post" action="/">
	<?php } ?>
		
        <div class="form-group mt-5">
            <label for="vimeo_video_title"><?php _e( 'Video Title', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="vimeo_video_title" id="vimeo_video_title" class="form-control" value="<?php echo $vimeo_video_title; ?>" />
        </div>

		<div class="form-group">
            <label for="vimeo_video_description"><?php _e( 'Description', 'wp-vimeo-videos' ); ?></label>
            <textarea name="vimeo_video_description" id="vimeo_video_description" class="form-control"><?php echo $vimeo_video_description; ?></textarea>
        </div>

		<div class="form-group">
            <label for="vimeo_video_recording_date"><?php _e( 'Video Recording Date', 'wp-vimeo-videos' ); ?></label>
            <input type="date" name="vimeo_video_recording_date" id="vimeo_video_recording_date" class="form-control" value="<?php echo $vimeo_video_recording_date; ?>" />
        </div>

		<!-- Organization Data -->
		
        <div class="form-group">
            <label for="video_organization"><?php _e( 'Location', 'wp-vimeo-videos' ); ?></label>
            <select name="video_organization" id="video_organization" class="form-control select2" data-placeholder="Select Location">
				<option value="" >Select Location</option>
				<?php
				if(!empty($organizations)):
					foreach($organizations as $organization):
						?>
						<option value="<?php echo $organization->ID; ?>" <?php echo selected($organization->ID,$video_organization); ?>><?php echo $organization->post_title; ?></option>
					 <?php
					 endforeach;
				endif; ?>
			</select>
        </div>

		<div class="form-group">
            <label for="video_organization_city"><?php _e( 'City', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="video_organization_city" id="video_organization_city" class="form-control" readonly value="<?php echo $video_organization_city; ?>" />
        </div>

		<div class="form-group">
            <label for="video_organization_state"><?php _e( 'State', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="video_organization_state" id="video_organization_state" class="form-control" readonly value="<?php echo $video_organization_state; ?>" />
        </div>
		
		<div class="form-group">
            <label for="video_organization_denomination"><?php _e( 'Denomination', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="video_organization_denomination" id="video_organization_denomination" class="form-control" readonly value="<?php echo $video_organization_denomination; ?>" />
            <input type="hidden" name="video_organization_denomination_ids" id="video_organization_denomination_ids" class="form-control" value="<?php echo $video_organization_denomination_ids; ?>" />
        </div>

		<!-- Search Tags --> 
		<?php
		//gd_place_tags
			$video_search_tags = get_terms( array( 
				//'taxonomy'	=> 'gd_place_tags',
				'taxonomy'	=> 'post_tag',
				'orderby'	=> 'name',
				'order'		=> 'ASC',
				'hide_empty' => false
			) );
		?>
		<div class="form-group">
            <label for="video_search_tags"><?php _e( 'Search Tags', 'wp-vimeo-videos' ); ?></label>
            <select name="video_search_tags[]" id="video_search_tags" class="select2 form-control select2-multiple" data-placeholder="<?php _e('Search Tags', 'wp-vimeo-videos'); ?>" multiple>
				<?php
				if(!empty($video_search_tags)):
					foreach($video_search_tags as $tags):
					?>
					<option value="<?php echo $tags->name; ?>" <?php echo selected($video_search_tags_selected, $tags->name); ?>><?php echo $tags->name; ?></option>
					<?php
					endforeach;
				endif;
				?>
			</select>
        </div>

        <h5 class="mt-5">Name of PRIMARY Speaker <span>*</span></h5>
		<div class="form-group">
            <label for="primary_speaker_user_name"><?php _e( 'PRIMARY Speaker', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="primary_speaker_user_name" id="primary_speaker_user_name" value="<?php echo $display_name; ?>" class="form-control" readonly />
        </div>
        <div class="form-group">
            <label for="primary_speaker_first_name"><?php _e( 'First Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="primary_speaker_first_name" id="primary_speaker_first_name" value="<?php echo $first_name; ?>" class="form-control" readonly />
        </div>
		<div class="form-group">
            <label for="primary_speaker_last_name"><?php _e( 'Last Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="primary_speaker_last_name" id="primary_speaker_last_name" value="<?php echo $last_name; ?>" class="form-control" readonly />
        </div>


		<h5 class="mt-5">Name of Second Speaker </h5>
		<div class="form-group">
            <label for="second_speaker_user_name"><?php _e( 'Second Speaker', 'wp-vimeo-videos' ); ?></label>
            <select name="second_speaker_user_name" id="second_speaker_user_name" class="form-control select-speaker" data-speaker-type="second">
				<option value="">Select Speaker</option>
				<option value="0" <?php echo selected($second_speaker_user_id, 0); ?>>Speaker Not Listed - Add Name</option>

			<?php
			if(!empty($users)): 
				foreach($users as $user): 
					?>
					<option value="<?php echo $user->ID; ?>" data-firstname="<?php echo $user->first_name; ?>" data-lastname="<?php echo $user->last_name; ?>" <?php echo selected($second_speaker_user_id, $user->ID); ?>><?php echo $user->display_name; ?></option>
				<?php
				endforeach;
			endif; ?>
			 
			</select>
        </div>
        <div class="form-group second-speaker-field" style="<?php echo ($second_speaker_user_id==0 || $second_speaker_user_id=="")?'display:none;':''; ?>">
            <label for="second_speaker_first_name"><?php _e( 'First Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="second_speaker_first_name" id="second_speaker_first_name" class="form-control" value="<?php echo $second_speaker_first_name; ?>" />
        </div>
		<div class="form-group second-speaker-field" style="<?php echo ($second_speaker_user_id==0 || $second_speaker_user_id=="")?'display:none;':''; ?>>
            <label for="second_speaker_last_name"><?php _e( 'Last Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="second_speaker_last_name" id="second_speaker_last_name" class="form-control" value="<?php echo $second_speaker_last_name; ?>" />
        </div>
		<div class="form-group second-speaker-enter-field" style="<?php echo ($second_speaker_user_id!=0 || $second_speaker_user_id=="")?'display:none;':''; ?>">
            <label for="second_speaker_name"><?php _e( 'Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="second_speaker_name" id="second_speaker_name" class="form-control" value="<?php echo $second_speaker_name; ?>" />
        </div>

        <h4 class="mt-5">Name of Third Speaker</h4>
		<div class="form-group">
            <label for="third_speaker_user_name"><?php _e( 'Third Speaker', 'wp-vimeo-videos' ); ?></label>
            <select name="third_speaker_user_name" id="third_speaker_user_name" class="form-control select-speaker" data-speaker-type="third">
				<option value="">Select Speaker</option>
				<option value="0" <?php echo selected($third_speaker_user_id, 0); ?>>Speaker Not Listed - Add Name</option>
			<?php
			if(!empty($users)): 
				foreach($users as $user): 
					?>
					<option value="<?php echo $user->ID; ?>" data-firstname="<?php echo $user->first_name; ?>" data-lastname="<?php echo $user->last_name; ?>" <?php echo selected($third_speaker_user_id, $user->ID); ?>><?php echo $user->display_name; ?></option>
				<?php
				endforeach;
			endif; ?>
			</select>
        </div>
        <div class="form-group third-speaker-field" style="<?php echo ($third_speaker_user_id==0 || $third_speaker_user_id=="")?'display:none;':''; ?>">
            <label for="third_speaker_first_name"><?php _e( 'First Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="third_speaker_first_name" id="third_speaker_first_name" class="form-control"  value="<?php echo $third_speaker_first_name; ?>" />
        </div>
		<div class="form-group third-speaker-field"  style="<?php echo ($third_speaker_user_id==0 || $third_speaker_user_id=="")?'display:none;':''; ?>">
            <label for="third_speaker_last_name"><?php _e( 'Last Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="third_speaker_last_name" id="third_speaker_last_name" class="form-control"  value="<?php echo $third_speaker_last_name; ?>" />
        </div>
		<div class="form-group third-speaker-enter-field"  style="<?php echo ($third_speaker_user_id!=0 || $third_speaker_user_id=="")?'display:none;':''; ?>">
            <label for="third_speaker_name"><?php _e( 'Name', 'wp-vimeo-videos' ); ?></label>
            <input type="text" name="third_speaker_name" id="third_speaker_name" class="form-control"  value="<?php echo $third_speaker_name; ?>" />
        </div>
        
		<div class="form-group mt-5">
            <label for="vimeo_video"><?php _e( 'Video File', 'wp-vimeo-videos' ); ?></label>
            <input type="file" name="vimeo_video" id="vimeo_video" class="form-control-file border" style="width:100%; display:block;" />
        </div>
		<div class="form-group mt-2">
			<?php
			if($is_edit):
				$video_url = 'https://player.vimeo.com/video/'.$uploaded_video_id; ?>
				<iframe src="<?php echo $video_url; ?>" width="320px" height="150px" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
				<input type="hidden" name="video_id" id="video_id" value="<?php echo $uploaded_video_id; ?>" />
			<?php
			endif; ?>
		</div>
		
		<div class="form-group">
            <label for="vimeo_video_img"><?php _e( 'Featured Image', 'wp-vimeo-videos' ); ?></label>
            <input type="file" aria-describedby="vimeo_video_img_help" name="vimeo_video_img" class="form-control-file border" style="width:100%; display:block;"/>
			<small id="vimeo_video_img_help" class="form-text text-muted">A thumbnail is the image that you want to display on your video uploads <br />in the store. For example, upload an image of: Your logo, sign, or symbol.</small>
			<?php
			if($is_edit): 
				$featured_image = wp_get_attachment_image_src($featured_image_id);
				if(!empty($featured_image)): ?>
				<div class="mt-2">
				<img src="<?php echo $featured_image[0]; ?>" class="img-responsive" height="150" />
				<input type="hidden" name="featured_image_id" id="featured_image_id" value="<?php echo $featured_image_id; ?>" />
				</div>
				<?php
				endif; 
			endif; ?>
        </div>

		<div class="form-group">
            <label for="vimeo_video_cover_img"><?php _e( 'Cover Photo', 'wp-vimeo-videos' ); ?></label>
            <input type="file" aria-describedby="vimeo_video_cover_img_help" name="vimeo_video_cover_img" class="form-control-file border" style="width:100%; display:block;"/>
			<?php
			if($is_edit): 
				$cover_image = wp_get_attachment_image_src($cover_image_id);
				if(!empty($cover_image)): ?>
				<div class="mt-2">
				<img src="<?php echo $cover_image[0]; ?>" class="img-responsive" height="150" />
				<input type="hidden" name="cover_image_id" id="cover_image_id" value="<?php echo $cover_image_id; ?>" />
				</div>
				<?php
				endif; 
			endif; ?>
        </div>
		
		<div class="form-group mt-5">
			<label for="vimeo_folder"><?php _e( 'Video Series', 'wp-vimeo-videos' ); ?></label>
			<select name="vimeo_folder" id="vimeo_folder" class="form-control select2" aria-describedby="vimeo_folder_help">
				<option value="vimeo_add_new" >Create New Video Series</option>
				<?php
				if(!empty($vimeo_folders)): 
					foreach($vimeo_folders as $folder):
				?>
					<option value="<?php echo $folder['uri']; ?>" <?php selected($vimeo_folder_selected, $folder['uri']); ?>><?php echo $folder['folder_name']; ?></option>
				<?php 
					endforeach;
				endif; ?>
				
			</select>
			<small id="vimeo_folder_help" class="form-text text-muted">Add this video to an existing video series. Or create a new video series to add this video too</small>
		</div>
		
		<div class="form-group" style="display:none" id="new_vimeo_folder_wrapper"> 
			<label for="new_vimeo_folder"><?php _e('New Video Series Title', 'wp-vimeo-videos'); ?></label>
			<input type="text" name="new_vimeo_folder" id="new_vimeo_folder" class="form-control" />
		</div>

		<div class="form-group"> 
			<label for="benefit-1"><?php _e('Course Details #1', 'wp-vimeo-videos'); ?></label>
			<input type="text" name="benefit-1" id="benefit-1" class="form-control" value="<?php echo $benefit_1; ?>" />
		</div>
		<div class="form-group"> 
			<label for="benefit-2"><?php _e('Course Details #2', 'wp-vimeo-videos'); ?></label>
			<input type="text" name="benefit-2" id="benefit-2" class="form-control" value="<?php echo $benefit_2; ?>" />
		</div>
		<div class="form-group"> 
			<label for="benefit-3"><?php _e('Course Details #3', 'wp-vimeo-videos'); ?></label>
			<input type="text" name="benefit-3" id="benefit-3" class="form-control" value="<?php echo $benefit_3; ?>" />
		</div>
		<div class="form-group mt-5">
			<h6>Payment Type<span>*</span></h6>
			<div class="form-check">
				<input class="form-check-input payment_type" type="radio" value="free"  name="payment_type" required autocomplete="off" <?php echo checked("free", $payment_type); ?> <?php echo checked("", $payment_type); ?> />
				<label class="form-check-label">Free</label>
			</div>
			<div class="form-check">
				<input class="form-check-input payment_type" type="radio" value="one-time"  name="payment_type" required autocomplete="off" <?php echo checked("one-time", $payment_type); ?> />
				<label class="form-check-label">One Time</label>
			</div>
			<div class="form-check">
				<input class="form-check-input payment_type" type="radio" value="recurring" name="payment_type" required autocomplete="off" <?php echo checked("recurring", $payment_type); ?> />
				<label class="form-check-label">Recurring Monthly Payment</label>
			</div>
		</div>
		
		<div class="form-group" style="<?php echo ($payment_type=='free' || $payment_type=='')?'display:none;':''; ?>" id="video_price_wrapper">
			<h6 >Price Of Video<span>*</span></h6>

			<select name="vimeo_video_price" class="select2 form-control " aria-hidden="true" data-placeholder="Price">
				<?php foreach ($prices as $price) { ?>
						<option  value="<?php echo $price; ?>" <?php echo selected($vimeo_video_price, $price); ?>> <?php echo '$'.$price; ?></option>
				<?php } ?> 
			</select>
		</div>


				
		<div class="form-group mt-5">
			<h6>Who Can Shop For This Video<span>*</span></h6>
			<div class="form-check">
				<input class="form-check-input" type="radio" value="anyone"  name="who_can_shop" required <?php echo checked("anyone", $who_can_shop); ?> <?php echo checked("", $who_can_shop); ?> autocomplete="off" /> 
				<label class="form-check-label">Anyone</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" value="members_only" name="who_can_shop" <?php echo checked("members_only", $who_can_shop); ?> autocomplete="off" />
				<label class="form-check-label">Members Only</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" value="group_only" name="who_can_shop" <?php echo checked("group_only", $who_can_shop); ?> autocomplete="off" />
				<label class="form-check-label">Groups Only</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" value="denomination_only" name="who_can_shop" <?php echo checked("denomination_only", $who_can_shop); ?> autocomplete="off" />
				<label class="form-check-label">Denomination Only</label>
			</div>
		</div>
		<div class="select2_prodcut_cat_id max_vimeo_groups form-group"  style="<?php echo ($who_can_shop=='group_only')?'display:block':'display:none'; ?>">
			<?php
				$table_name = $wpdb->prefix . "bp_groups";
				$result = $wpdb->get_results("SELECT name FROM $table_name");
			?>
			<select name="max_vimeo_groups[]" class="select2 form-control select2-multiple" multiple="multiple" aria-hidden="true" data-placeholder="Select Groups">
				<?php foreach ($result as $value) { ?>
						<option  value="<?php echo $value->name; ?>"> <?php echo $value->name; ?></option>
				<?php } ?> 
			</select>
		</div>

		<div class="select2-blue select2_prodcut_cat_id max_vimeo_denomination form-group" style="display: none;">
			<?php
				$denomination_table = $wpdb->prefix . "geodir_gd_place_detail";
				$result = $wpdb->get_results("SELECT  distinct  post_title FROM $denomination_table  where post_status = 'publish' ORDER BY post_title ASC limit 50");
			?>
			<select name="max_vimeo_denomination[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Select Denominations">
				<option value="">Select Denomination</option>
				<?php foreach ($result as $value) { ?>
					<option  value="<?php echo $value->post_title; ?>"> <?php echo $value->post_title; ?></option>
				<?php } ?> 
			</select>
		</div>
		<div class="row mb-5">
			<div class="col-md-12">
				<div class="progress" id="progress-bar-wrapper" style="display:none; height:1.5rem;">
					<div class="progress-bar progress-bar-striped" id="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
				</div>
			</div>
		</div>
        <div class="form-group with-border">
            <div class="dgv-loader" style="display:none;"></div>
            <button type="submit" class="btn btn-primary" name="vimeo_upload" value="1" id="geeker-vimeo-submit">
				<?php _e( 'Upload', 'wp-vimeo-videos' ); ?>
            </button>
        </div>
    </form>
</div>