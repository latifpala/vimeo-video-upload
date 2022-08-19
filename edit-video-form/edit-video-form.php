<?php
if ( is_user_logged_in() ) {

	
	if(isset($_POST['edit_uploaded_video'])){

		$product_id               = $_POST['product_id'];
		/////////////////////// Static  ////////////////////////////
		$data =  get_post_meta($product_id, 'max_vimoe_post_meta', true );

		$vimeo_video_sold_by       = $data['vimeo_video_sold_by'];
		$vimeo_category            = $data['vimeo_category'];
		$vimeo_video_file_id       = $data['vimeo_video_file_id'];
		$org_post_title            = $data['org_post_title'];
		$speaker_first_name_1      = $data['speaker_first_name_1'];
		$speaker_last_name_1       = $data['speaker_last_name_1'];
		$pirmary_speaker_user_name = $data['pirmary_speaker_user_name'];
		$vimeo_video_price         = $data['vimeo_video_price'];
		$product_ation             = $data['product_ation'];
		$who_can_shop              = $data['who_can_shop'];
		$max_vimeo_groups          = $data['max_vimeo_groups'];
		$max_vimeo_denomination    = $data['max_vimeo_denomination'];
		$max_vimeo_date            = $data['max_vimeo_date'];
		$org_city                  = $data['org_city'];
		$org_region                = $data['org_region'];
		$max_alernate_location     = $data['max_alernate_location'];
		$primary_speaker_uid_1     = $data['primary_speaker_uid_1'];
		$primary_speaker_uid_2     = $data['primary_speaker_uid_2'];
		$speaker_first_name_2      = $data['speaker_first_name_2'];
		$speaker_last_name_2       = $data['speaker_last_name_2'];
		$primary_speaker_uid_3     = $data['primary_speaker_uid_3'];
		$speaker_first_name_3      = $data['speaker_first_name_3'];
		$speaker_last_name_3       = $data['speaker_last_name_3'];
		$vimeo_video_id            = $data['vimeo_video_id'];
		$vimeo_image_id            = $data['vimeo_image_id'];
	 
	    //////////////////////////////////////////////////////////////////

		$edit_video_title         = $_POST['edit_video_title'];

		$edit_org_post_title      = $_POST['edit_org_post_title'];
		$edit_alernate_location   = $_POST['edit_alernate_location'];

		$edit_org_city            = $_POST['edit_org_city'];
		$edit_org_region          = $_POST['edit_org_region'];
		$edit_vimeo_search_tag    = $_POST['edit_vimeo_search_tag'];

		$edit_primary_speaker_uid_1      = $_POST['edit_primary_speaker_uid_1'];
		$edit_speaker_first_name_1      = $_POST['edit_speaker_first_name_1'];
		$edit_speaker_last_name_1      = $_POST['edit_speaker_last_name_1'];

		if(!empty($_POST['edit_primary_speaker_uid_2'])){
			$edit_primary_speaker_uid_2      = $_POST['edit_primary_speaker_uid_2'];
		}
		if(!empty($_POST['edit_speaker_first_name_2'])){
			$edit_speaker_first_name_2      = $_POST['edit_speaker_first_name_2'];
		}
		if(!empty($_POST['edit_speaker_last_name_2'])){
			$edit_speaker_last_name_2      = $_POST['edit_speaker_last_name_2'];
		}

		if(!empty($_POST['edit_primary_speaker_uid_3'])){
			$edit_primary_speaker_uid_3      = $_POST['edit_primary_speaker_uid_3'];
		}
		if(!empty($_POST['edit_speaker_first_name_3'])){
			$edit_speaker_first_name_3      = $_POST['edit_speaker_first_name_3'];
		}
		if(!empty($_POST['edit_speaker_last_name_3'])){
			$edit_speaker_last_name_3      = $_POST['edit_speaker_last_name_3'];
		}

		if(!empty($_POST['update_other_price'])){
			$update_video_price  = null;
			$update_other_price  = $_POST['update_other_price'];
		}
		if(!empty($_POST['update_video_price']) &&  $_POST['update_video_price'] != 'other_amount'){
			
			$update_video_price  = $_POST['update_video_price'];
			$update_other_price  = 0;
		}


		if(!empty($_FILES['edit_vimeo_video']['tmp_name'])  ){
		

			$new_video       = $_FILES['edit_vimeo_video']['tmp_name'];
			$vimeo_video_src =  get_post_meta( $product_id, 'max_vimoe_post_meta', true );
			
		    $old_vimeo_video =  $vimeo_video_src['vimeo_video_file_id'];
		    $change_vimeo_old_video = str_replace("video","/videos",$old_vimeo_video);
		  
			//here we updload video and return id
		    require_once(MVA_PLUGIN_DIR_PATH . 'vendor/autoload.php');
			require_once(MVA_PLUGIN_DIR_PATH . 'vendor/vimeo/vimeo-api/src/Vimeo/Vimeo.php');
		    $client_id     = '8a895685bc7652dfea40e76c5720eb4bb8b38a0a';
		    $token         = '7d9c0ba546a97d4f48030ed60d7421fd';
		    $client_secret = 'EdVD4aPuLLNxV5UDPV58WdvGVPbB/L7dzeghAE+th3/GDM1lgyN1qc+u6TUyS20NA2YYDyFfA+7TNAVQnsuEfhW7pAudP6yla+M2kqL797RacXiqvzaUCturo44C6fog';
			$client = new \Vimeo\Vimeo($client_id, $client_secret, $token);

	        //replace the video
		    $id = $client->replace($change_vimeo_old_video, $new_video);

		}
		if(!empty($_FILES['edit_vimeo_thmnail']['tmp_name'])){

			$edit_vimeo_thmnail  = $_FILES['edit_vimeo_thmnail']['tmp_name'];
			$vimeo_video_src =  get_post_meta( $product_id, 'max_vimoe_post_meta', true );
		    $old_vimeo_video =  $vimeo_video_src['vimeo_video_file_id'];
		    $change_vimoe_thumbnail = str_replace("video","/videos",$old_vimeo_video);
		    require_once(MVA_PLUGIN_DIR_PATH . 'vendor/autoload.php');
			require_once(MVA_PLUGIN_DIR_PATH . 'vendor/vimeo/vimeo-api/src/Vimeo/Vimeo.php');
		    $client_id     = '8a895685bc7652dfea40e76c5720eb4bb8b38a0a';
            $token         = '7d9c0ba546a97d4f48030ed60d7421fd';
            $client_secret = 'EdVD4aPuLLNxV5UDPV58WdvGVPbB/L7dzeghAE+th3/GDM1lgyN1qc+u6TUyS20NA2YYDyFfA+7TNAVQnsuEfhW7pAudP6yla+M2kqL797RacXiqvzaUCturo44C6fog';
            $client = new \Vimeo\Vimeo($client_id, $client_secret, $token);
			$client->uploadImage($change_vimoe_thumbnail.'/pictures',$edit_vimeo_thmnail, true);


			//Set product img
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
	        require_once( ABSPATH . 'wp-admin/includes/file.php' );
	        require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$files  = $_FILES['edit_vimeo_thmnail'];

		        $file = array(

		          'name' => $files['name'],
		          'type' => $files['type'],
		          'tmp_name' => $files['tmp_name'],
		          'error' => $files['error'],
		          'size' => $files['size']

		        );

		        $_FILES = array("upload_file" => $file);
	            $thumnail_attachment_id = media_handle_upload("upload_file", 0); 
	            set_post_thumbnail( $product_id, $thumnail_attachment_id );
 
		}

		if($edit_org_post_title == undefined){
			$edit_org_post_title = $org_post_title;
		}

	
	

	

	    $my_post = array(
		      'ID'           => $product_id,
		      'post_title'   => $edit_video_title
		);
		$post_meta_val = array(
				      'vimeo_video_sold_by'       => $vimeo_video_sold_by,
				      'vimeo_category'            => $vimeo_category,
				      'vimeo_video_file_id'       => $vimeo_video_file_id,
					  'org_post_title'            => $edit_org_post_title,
					  'speaker_first_name_1'      => $edit_speaker_first_name_1,

					  'speaker_last_name_1'       => $edit_speaker_last_name_1,
					  'pirmary_speaker_user_name' => $edit_speaker_first_name_1,
					  'vimeo_video_price'         => $update_video_price,
					  'product_ation'             => $product_ation,
					  'who_can_shop'              => $who_can_shop,

					  'max_vimeo_groups'          => $max_vimeo_groups,
					  'max_vimeo_denomination'    => $max_vimeo_denomination,
					  'max_vimeo_date'            => $max_vimeo_date,
					  'org_city'                  => $edit_org_city,
					  'org_region'                => $edit_org_region,

					  'max_alernate_location'     => $edit_alernate_location,
					  'primary_speaker_uid_1'     => $edit_primary_speaker_uid_1,
					  'primary_speaker_uid_2'     => $edit_primary_speaker_uid_2,
					  'speaker_first_name_2'      => $edit_speaker_first_name_2,
					  'speaker_last_name_2'       => $edit_speaker_last_name_2,

					  'primary_speaker_uid_3'     => $edit_primary_speaker_uid_3,
					  'speaker_first_name_3'      => $edit_speaker_first_name_3,
					  'speaker_last_name_3'       => $edit_speaker_last_name_3,
					  'vimeo_video_id'            => $vimeo_video_id,
					  'vimeo_image_id'            => $vimeo_image_id

		); 




		
		update_post_meta( $product_id, '_price', $update_video_price );
		update_post_meta($product_id, 'max_vimoe_post_meta',  $post_meta_val );

		update_post_meta( $product_id, 'vimeo_search_tag', $edit_vimeo_search_tag );
	
		update_post_meta($product_id, 'update_other_price',  $update_other_price );
	
	    wp_update_post( $my_post );
	    $redirect = get_site_url()."/my-account-2/max_my_uploads";
	    ///////////////////////////////////////////////////////////////////////
	    wp_set_object_terms($product_id, $vimeo_category, 'denominations');
	   

 
	    ////////////////////////////////////////////////////////////////////////
	  
        echo "<script> location.href='".$redirect."'; </script>";
        exit;
	    	
	
	   
       
	}

	if(!empty($_GET['id'])){
		


		global $wpdb;
	    $table_name = $wpdb->prefix . "posts"; 
	    $product_id =  $_GET['id'];
	    
	    
	    $product= $wpdb->get_row( "SELECT *  FROM $table_name WHERE ID  = '".$product_id."'" );
	    $thumnail =  get_the_post_thumbnail($product_id); 
	    $vimeo_video_src =  get_post_meta( $product_id, 'max_vimoe_post_meta', true ); 

	  ;

           
        $other_price = 	get_post_meta($product_id, 'update_other_price', true );

	    $location = get_post_meta($product_id, 'max_vimoe_post_meta', true );

	    
	  
	    $org_city = $location['org_city'];

	    $org_region = $location['org_region'];
	    $max_alernate_location = $location['max_alernate_location'];
	    $org_post_title = $location['org_post_title'];
	    $price = $location['vimeo_video_price'];
	    $edit_alernate_location = $location['max_alernate_location'];



	    
	    

	   
	

	    

	   




	    $tags = get_post_meta($product_id, 'vimeo_search_tag', true);

	    $speaker_last_name_1 = $location['speaker_last_name_1'];
	    $speaker_last_name_1 = $location['speaker_last_name_1'];
	    $speaker_last_name_1 = $location['speaker_last_name_1'];

        $primary_speaker_uid_1 = $location['primary_speaker_uid_1'];
	    $primary_speaker_uid_2 = $location['primary_speaker_uid_2'];
	    $primary_speaker_uid_3 = $location['primary_speaker_uid_3'];


	    ///First Speaker
	    global $wpdb; 
    	$table_users = $wpdb->prefix . "users";  
        $user_name_1 = $wpdb->get_row( "SELECT distinct display_name FROM $table_users Where ID = '".$primary_speaker_uid_1."'");
        $pirmary_speaker_user_name            = $user_name_1->display_name;	
	    $speaker_first_name_1      = $location['speaker_first_name_1'];
	    $speaker_last_name_1       = $location['speaker_last_name_1'];



	    if($primary_speaker_uid_2 != 'select_primary_speaker'){
	    	global $wpdb; 
	    	$table_users = $wpdb->prefix . "users";  
            $user_name = $wpdb->get_row( "SELECT distinct display_name FROM $table_users Where ID = '".$primary_speaker_uid_2."'");
            $second_speaker            = $user_name->display_name;	
            $speaker_first_name_2      = $location['speaker_first_name_2'];
            $speaker_last_name_2       = $location['speaker_last_name_2'];
	    }else{
	    	$second_speaker            = '';
	    	$speaker_first_name_2      = '';
            $speaker_last_name_2       = '';
	    }


	    if($primary_speaker_uid_3 != 'select_primary_speaker'){
	    	global $wpdb; 
	    	$table_users = $wpdb->prefix . "users";  
            $user_name = $wpdb->get_row( "SELECT distinct display_name FROM $table_users Where ID = '".$primary_speaker_uid_3."'");
            $third_speaker = $user_name->display_name;
            $speaker_first_name_3       = $location['speaker_first_name_3'];
            $speaker_last_name_3        = $location['speaker_last_name_3'];	
     
	    }else{
	    	$third_speaker              = '';
	    	$speaker_first_name_3       = '';
            $speaker_last_name_3        = '';	
	    }

	}
	
	?>
	<style type="text/css">
		.edit_api .fluid-width-video-wrapper {
		    padding-top: 50% !important;
		    margin-top: 0%;
		    height: 263px;
		    margin-bottom: 10px;
		    
		}

		.inp_grp {
             padding: 0px 14px 0 16px !important;
                }


           .img_out {
    height: 250px;
    margin-bottom: 1.4em;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    width: 100% !important;
    padding-top: 11px;
}

.select2-container--default .select2-selection--multiple .select2-selection__clear {
    padding-left: 4px;
}
img.attachment-post-thumbnail.size-post-thumbnail.wp-post-image{
	height: 250px;
}
	</style>
	<div class="container" >
		<form   method="post"  class="pt-2 max_vimeo_first_form" enctype="multipart/form-data" >
			<input type="hidden"  name="product_id" value="<?php  echo !empty($product_id) ? $product_id :'' ;?>">
		    <!--Vide and thumbnail -->
			<div class="row">
                  <div class="col-md-5 edit_api">
                  
                  	    		 <iframe class="edit_single_video" width="100%" height="auto"   src="https://player.vimeo.com/<?php  echo $vimeo_video_src['vimeo_video_file_id']; ?>" frameborder="0" allow="autoplay; fullscreen" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                  	    	<!-- 	<div class="input-group">
				                      <div class="custom-file">
				                        <input type="file" class="custom-file-input" name=" edit_vimeo_video">
				                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
				                      </div>
				                      <div class="input-group-append">
				                        <span class="input-group-text">Video</span>
				                      </div>
				                </div> --> 

				                <input style="overflow: hidden;" type="file" name="edit_vimeo_video" class="form-control-file border vimeo_video" >  
				                               			
                  </div>
                  <div class="col-md-2"></div>
               
                   <div class="col-md-5">
                                <div class="img_out">
                  	    		<?php  echo !empty($thumnail) ? $thumnail :'' ;?>
                  	    	</div>
                  	    	    <div class="inp_grp">
	                  	    	<!-- 	<div class="input-group" >
					                      <div class="custom-file">
					                        <input type="file" class="custom-file-input" name="edit_vimeo_thmnail">
					                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
					                      </div>
					                      <div class="input-group-append">
					                        <span class="input-group-text">Thumnail</span>
					                      </div>
					                </div> -->
					                <input style="overflow: hidden;" type="file" name="edit_vimeo_thmnail" class="form-control-file border vimeo_video" >  

                  	            </div>
                  		                      			
                  </div>
            </div>
            <!--title of video -->
            <div class="row mt-3">
				<div class="col-md-12">
					<div class="form-group">
					 <input type="text" class="form-control edit_video_title"  name="edit_video_title" value="<?php  echo !empty($product->post_title) ? $product->post_title :'' ;?>">
					</div>
			    </div>	
			</div>
		    <!--search Organization-->
			<div class="row">
			 	<div class="col-md-6 org_main_cont">
			 		 <div class="form-group">  						
					 <!-- 	<input type="text" style="display: none;" name="selected_org_title" id="selected_org_title">	 -->					
					 	<select name="edit_org_post_title"  class="js-data-example-ajax form-control " id="mva_search_bar" >	                       						
					 	</select>															    
					 </div>	
			 		
			 	</div>
			 	<?php
			 	if(!empty($edit_alernate_location) && ($max_alernate_location != 'empty')){
			 			?>
			 			<div class="col-md-6">
					 		 <div class="form-group">					     
							<input  type="text" class="form-control max_alernate_location"  name="edit_alernate_location" value="<?php echo $edit_alernate_location;?>" placeholder="If the location isn’t listed. Type the address here" >					
					        </div>	
					 	</div>
			 		<?php

			 	}else{
			 		?>
			 			<div class="col-md-6">
					 		 <div class="form-group">					     
							<input style="display: none;" type="text" class="form-control max_alernate_location"  name="edit_alernate_location" placeholder="If the location isn’t listed. Type the address here" >					
					        </div>	
					 	</div>
			 		<?php
			 	}
			 	?>
			 
			</div>				

			<!--city and state-->					
			<div class="row">	
				<div class="col-md-6">
					<div class="form-group">								 	
						<input type="text" class="form-control org_city_id"  name="edit_org_city" placeholder="City" value="<?php  echo !empty($org_city) ? $org_city :'' ;?>" >
					 

					</div>						
				</div>						
				<div class="col-md-6">								
					<div class="form-group">								 	
						<input type="text" class="form-control org_region_id"  name="edit_org_region" placeholder="State" value="<?php  echo !empty($org_region) ? $org_region :'' ;?>">								
					</div>						
				</div>					
			</div>	
				<!--- additional fields -->
				   <?php                        
				$table_term = $wpdb->prefix . "terms";                        
				$result = $wpdb->get_results( "SELECT distinct name	FROM $table_term 							WHERE slug like '%orgtag%'");
            ?>  
            <style type="text/css"> 
                ul.select2-selection__rendered {
				    width: 460px !important;
				}                   	                  
            </style>  
			<select  name="edit_vimeo_search_tag[]"   class="form-control js-example-placeholder-single  vimeo_search_tag" multiple="multiple" data-dropdown-css-class="select2-blue"  required >	                	<?php	foreach ($result as  $value) {	                        
			    	?>	             
			    	<option  value="<?php echo $value->name; ?>" <?php echo in_array($value->name, $tags) ? 'selected' : ''?>>
			    	 <?php echo $value->name; ?>	                    
			    	</option>	                    
			    	<?php	                       
			    	 }                           	                     
			    	?>                      
		    </select>				
					               				
		    <div id="accordion" class="mt-3">				  
			    <div class="card">				    
				<div class="card-header">				      
					<a class="card-link" data-toggle="collapse" href="#collapseOne">				        Primary Speaker				      
				   </a>				    
				</div>				    
				<div id="collapseOne" class="collapse show" data-parent="#accordion">			
				<div class="card-body">				       
				 <div class="form-group">          			                
				 	<?php			                    
				 	global $wpdb;    			                    
				 	$table_users = $wpdb->prefix . "users";  			                    
				 	$table_usermeta = $wpdb->prefix . "usermeta"; 			                    
				 	$capabilities = $wpdb->prefix . "capabilities"; 			                 
				 	$role_users = $wpdb->get_results( "SELECT u.ID, u.user_login, u.display_name		FROM $table_users u	WHERE u.id in (select user_id						from $table_usermeta um	where um.meta_key = '".$capabilities."'			and ( um.meta_value like '%organization_basic_role%'					 or um.meta_value like '%leadership_standard_role%' or um.meta_value like '%leadership_basic_role%'	or um.meta_value like '%leadership_premium_role%' )) ORDER BY display_name ASC     ");			                   			                ?>			   
				 	<select  name="edit_primary_speaker_uid_1"  class="form-control primary_speaker_uid_1" required>			                    
				 		<option value="select_primary_speaker">Select Primary Speaker</option>			                    
				 		<option value="speaker_not_listed">Speaker Not Listed</option>			                 <?php			                           
				 		foreach ($role_users as  $value) {			                     $first_name = get_user_meta( $value->ID,'first_name' );
				 		    $last_name = get_user_meta( $value->ID,'last_name' );			                        ?>			          
				 	     <option data-fname="<?php echo $first_name[0]; ?>" data-lname="<?php echo $last_name[0]; ?>"  value="<?php echo $value->ID; ?>"
				 		 <?php echo ($value->display_name == $pirmary_speaker_user_name) ? 'selected' : ''?>> <?php echo $value->display_name; ?>			                   
				 		 </option>			                    
				 		 <?php			                        
				 		 }                           			                     
				 		 ?>    			               
				 		  </select>              						
				 		  </div>												
				 		  <div class="row">							
				 		  	<div class="col-md-6">								
				 		  		<div class="form-group">									 	
				 		  			<input type="text" class="form-control speaker_first_name_1"  name="edit_speaker_first_name_1" placeholder="First Name" value="<?php echo $speaker_first_name_1; ?>" >								
				 		  			</div>							
				 		  			</div>							
				 		  			<div class="col-md-6">								
				 		  				<div class="form-group">									 	
				 		  					<input type="text" class="form-control speaker_last_name_1"  name="edit_speaker_last_name_1" placeholder="Last Name" value="<?php echo $speaker_last_name_1; ?>" >								</div>							</div>						</div>				      </div>				    </div>				  </div>				  <div class="card">				    <div class="card-header">				      <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">				        Second Speaker				      </a>				    </div>				    <div id="collapseTwo" class="collapse" data-parent="#accordion">				      <div class="card-body">				        <div class="form-group">          						                <?php						    			                    global $wpdb;    			                    $table_users = $wpdb->prefix . "users";  			                    $table_usermeta = $wpdb->prefix . "usermeta";  			                    $capabilities = $wpdb->prefix . "capabilities"; 			                    $role_users = $wpdb->get_results( "SELECT u.ID, u.user_login, u.display_name									FROM $table_users u									WHERE u.id in (select user_id								    from $table_usermeta um									where um.meta_key = '".$capabilities."'									and ( um.meta_value like '%organization_basic_role%'								          or um.meta_value like '%leadership_standard_role%'								          or um.meta_value like '%leadership_basic_role%'								          or um.meta_value like '%leadership_premium_role%'								        ))								    ORDER BY display_name ASC     ");						                ?>					<select  name="edit_primary_speaker_uid_2"  class="form-control primary_speaker_uid_2"  >				
				 		  						<option value="select_primary_speaker">Select Second Speaker</option>						
				 		  						<option value="speaker_not_listed">Speaker Not Listed</option>						<?php	
				 		  						foreach ($role_users as  $value) {						 $first_name = get_user_meta( $value->ID,'first_name' );			                    $last_name = get_user_meta( $value->ID,'last_name' );						?>						    <option data-fname="<?php echo $first_name[0]; ?>" data-lname="<?php echo $last_name[0]; ?>"  value="<?php echo $value->ID; ?>" 
				 		  							<?php echo ($value->display_name == $second_speaker) ? 'selected' : ''?>> <?php echo $value->display_name; ?>						    </option>	                    <?php	                        }                           	                     ?>    			        </select>              						</div>						<div class="row">							<div class="col-md-6">								<div class="form-group">									 	<input type="text" class="form-control speaker_first_name_2"  name="edit_speaker_first_name_2" placeholder="First Name" value="<?php echo $speaker_first_name_2; ?>" >								</div>							</div>							<div class="col-md-6">								<div class="form-group">									 	<input type="text" class="form-control speaker_last_name_2"  name="edit_speaker_last_name_2" placeholder="Last Name"  value="<?php echo $speaker_last_name_2; ?>"  >								</div>							</div>						</div>				      </div>				    </div>				  </div>				  <div class="card">				    <div class="card-header">				      <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">				        Third Speaker				      </a>				    </div>				    <div id="collapseThree" class="collapse" data-parent="#accordion">				      <div class="card-body">				        <div class="form-group">          			                <?php			    			                    global $wpdb;    			                    $table_users = $wpdb->prefix . "users";  			                    $table_usermeta = $wpdb->prefix . "usermeta"; 			                    $capabilities = $wpdb->prefix . "capabilities";  			                    $role_users = $wpdb->get_results( "SELECT u.ID, u.user_login, u.display_name
				     FROM $table_users u	WHERE u.id in (select user_id						from $table_usermeta um	where um.meta_key = '".$capabilities."'			and ( um.meta_value like '%organization_basic_role%'								          or um.meta_value like '%leadership_standard_role%'								          or um.meta_value like '%leadership_basic_role%'								          or um.meta_value like '%leadership_premium_role%'								        ))								    ORDER BY display_name ASC     ");			                ?>			                <select  name="edit_primary_speaker_uid_3"  class="form-control primary_speaker_uid_3"  >			                    <option value="select_primary_speaker">Select Third Speaker</option>			                    <option value="speaker_not_listed">Speaker Not Listed</option>			                        <?php			                           foreach ($role_users as  $value) {			                             $first_name = get_user_meta( $value->ID,'first_name' );			                             $last_name = get_user_meta( $value->ID,'last_name' );			                        ?>			                    <option  data-fname="<?php echo $first_name[0]; ?>" data-lname="<?php echo $last_name[0]; ?>" value="<?php echo $value->ID; ?>" 
				     	<?php echo ($value->display_name == $third_speaker) ? 'selected' : ''?>> <?php echo $value->display_name; ?>			                    </option>			                    <?php			                        }                           			                     ?>    			                </select>              						</div>						
				     	<div class="row">							<div class="col-md-6">								
				     		<div class="form-group">									 	
				     		<input type="text" class="form-control speaker_first_name_3"  name="edit_speaker_first_name_3"  value="<?php echo $speaker_first_name_3; ?>" >								</div>							</div>							<div class="col-md-6">								
				     		<div class="form-group">									 	
				     			<input type="text" class="form-control speaker_last_name_3"  name="edit_speaker_last_name_3" value="<?php echo $speaker_last_name_3; ?>"  >								</div>							</div>						</div>				      </div>				    </div>				  </div>				
			</div>	

			<!--For Price-->
			<div class="form-check mt-4">

			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_free" value="free" <?php echo ($price=='free')?'checked':'' ?> />

			  <label class="form-check-label" for="flexRadioDefault1"> Free</label>
			</div>
			<div class="form-check">

			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_second" value="0.99" <?php echo ($price=='0.99')?'checked':'' ?>/>

			  <label class="form-check-label" for="flexRadioDefault1"> $0.99</label>
			</div>
			<div class="form-check">

			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_three" value="1.99" <?php echo ($price=='1.99')?'checked':'' ?>/>

			  <label class="form-check-label" for="flexRadioDefault1"> $1.99</label>
			</div>
			<div class="form-check">

			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_four" value="4.99" <?php echo ($price=='4.99')?'checked':'' ?>/>

			  <label class="form-check-label" for="flexRadioDefault1"> $4.99</label>
			</div>

			<div class="form-check">
			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_five" value="9.99" <?php echo ($price=='9.99')?'checked':'' ?>/>
			  <label class="form-check-label" for="flexRadioDefault1"> $9.99</label>
			</div>

			<div class="form-check">
			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_six" value="19.99" <?php echo ($price=='19.99')?'checked':'' ?>/>
			  <label class="form-check-label" for="flexRadioDefault1"> $19.99</label>
			</div>

			<div class="form-check">
			  <input class="form-check-input" type="radio" name="update_video_price" id="max_update_seven" value="other_amount" 
			  <?php echo  (!empty($other_price))?'checked':'' ?> />
			  <label class="form-check-label" for="flexRadioDefault1"> Other Amount</label>
			</div>
			<?php
			if(!empty($other_price)){
				?>
					<input type="text" class="form-control update_other_price"  name="update_other_price" style="width: 200px;" placeholder="$" value="<?php echo $other_price; ?>">
				<?php

			}else{
				?>
						<input type="text" class="form-control update_other_price"  name="update_other_price" style="width: 200px;display: none;" placeholder="$" 
			 >
				<?php

			}
			?>
		

   
<!-- end --------------------------------------------------------------------------> 

				<button type="submit" name="edit_uploaded_video" class="btn btn-info btn-block upload_video_to_vimoe" data-site_url="<?php echo site_url() ?>" style="background-color: #831EB3; border:none;" >Proceed To Upload</button>

		    </form>
	</div>
    <?php
} else {
    echo ' NOT ALLOW TO ACCESS THIS PAGE DIRECTLY!';
}
?>