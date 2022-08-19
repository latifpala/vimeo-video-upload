<?php
function geeker_vimeo_video_form_callback(){
	ob_start();
	wp_enqueue_style('geeker-bootstrap-min-css');
	include_once(WP_VIMEO_VIDEOS_PATH."/upload-form/vimeo-upload-form.php");
	return ob_get_clean();
}
add_shortcode('vimeo_video', 'geeker_vimeo_video_form_callback');

function geeker_vimeo_video_list_callback(){
	ob_start();
	wp_enqueue_style('geeker-bootstrap-min-css');
	include_once(WP_VIMEO_VIDEOS_PATH."/upload-form/vimeo-video-listing.php");
	return ob_get_clean();
}
add_shortcode('vimeo_video_listing', 'geeker_vimeo_video_list_callback');


/* function geeker_vimeo_video_form_working_callback(){
	ob_start();
	wp_enqueue_style('geeker-bootstrap-min-css');
	?>
	<div class="wvv-box" style="max-width: 500px;">
	    <form class="wvv-video-upload" enctype="multipart/form-data" method="post" action="/">
			<div class="row mt-3">
				<div class="col-md-12">
					<div class="form-group">
						<label for="vimeo_title"><?php _e( 'Title', 'wp-vimeo-videos' ); ?></label>
						<input type="text" name="vimeo_title" id="vimeo_title" class="form-control" />
					</div>
				</div>
			</div>

	        <div class="form-row">
	            <label for="vimeo_description"><?php _e( 'Description', 'wp-vimeo-videos' ); ?></label>
	            <textarea name="vimeo_description" id="vimeo_description"></textarea>
	        </div>
	        <div class="form-row">
	            <label for="vimeo_video"><?php _e( 'Video File', 'wp-vimeo-videos' ); ?></label>
	            <p><input type="file" name="vimeo_video" id="vimeo_video"></p>
	            <div class="dgv-progress-bar" style="display: none;">
	                <div class="dgv-progress-bar-inner"></div>
	                <div class="dgv-progress-bar-value">0%</div>
	            </div>
	        </div>
	        <div class="form-row with-border">
	            <div class="dgv-loader" style="display:none;"></div>
	            <button type="submit" class="button-primary" name="vimeo_upload" value="1">
					<?php _e( 'Upload', 'wp-vimeo-videos' ); ?>
	            </button>
	        </div>
	    </form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('vimeo_video_working', 'geeker_vimeo_video_form_working_callback'); */