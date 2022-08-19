<div class="container ">
	<div class="row">
		<div class="col-md-8 ">
			<form method="post" class="pt-2 max_vimeo_first_form" enctype="multipart/form-data" action="<?php echo site_url('/video-woocommerce-form/') ?>">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control video_title" placeholder="Video Title" name="video_title" required> </div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="date" class="form-control max_vimeo_date" placeholder="mm/dd/yyyy" name="max_vimeo_date" required> </div>
					</div>
				</div>
				<!--search Organization-->
				<div class="row">
					<div class="col-md-6 org_main_cont">
						<div class="form-group">
							<input type="text" style="display: none;" name="selected_org_title" id="selected_org_title">
							<select name="org_post_title" class="js-data-example-ajax form-control " id="mva_search_bar"> </select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input style="display: none;" type="text" class="form-control max_alernate_location" name="max_alernate_location" placeholder="If the location isn’t listed. Type the address here"> </div>
					</div>
				</div>
				<!--city and state-->
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control org_city_id" name="org_city" placeholder="City" required> </div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control org_region_id" name="org_region" placeholder="State" required> </div>
					</div>
				</div>
				<!--Denomination-->
				<div class="row">
					<div class="col-md-12">
						<div class="form-group ">
							<?php
								global $wpdb;
								$table_term = $wpdb->prefix . "terms";
								$term_taxonomy = $wpdb->prefix . "term_taxonomy";
								$result = $wpdb->get_results(" SELECT distinct t.name	FROM $table_term AS t INNER JOIN $term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('gd_placecategory')ORDER BY t.name ");
								?>
								<select name="vimeo_category" class="form-control vimeo_category" required>
									<option value="0">Denomination</option>
									<?php foreach ($result as $value){ ?>
										<option value="<?php echo $value->name; ?>">
											<?php echo $value->name; ?>
										</option>
										<?php } ?>
								</select>
						</div>
					</div>
				</div>
				<!--search tags-->
				<div class="select2-blue mb-3">
					<?php
						$table_term = $wpdb->prefix . "terms";
						$result = $wpdb->get_results("SELECT distinct name	FROM $table_term WHERE slug like '%orgtag%'");
					?>
						<select name="vimeo_search_tag[]" class="form-control js-example-placeholder-single  vimeo_search_tag" multiple="multiple" data-dropdown-css-class="select2-blue" required>
							<?php foreach ($result as $value){ ?>
								<option value="<?php echo $value->name; ?>">
									<?php echo $value->name; ?>
								</option>
								<?php } ?>
						</select>
				</div>
				
				<div id="accordion">
					<div class="card">
						<div class="card-header"> <a class="card-link" data-toggle="collapse" href="#collapseOne">				        Primary Speaker				      </a> </div>
						<div id="collapseOne" class="collapse show" data-parent="#accordion">
							<div class="card-body">
								<div class="form-group">
									<?php global $wpdb;
										$table_users = $wpdb->prefix . "users";
										$table_usermeta = $wpdb->prefix . "usermeta";
										$capabilities = $wpdb->prefix . "capabilities";
										$role_users = $wpdb->get_results("SELECT u.ID, u.user_login, u.display_name									FROM $table_users u									WHERE u.id in (select user_id								    from $table_usermeta um									where um.meta_key = '" . $capabilities . "'									and ( um.meta_value like '%organization_basic_role%'								          or um.meta_value like '%leadership_standard_role%'								          or um.meta_value like '%leadership_basic_role%'								          or um.meta_value like '%leadership_premium_role%'								        ))								    ORDER BY display_name ASC     "); 
									?>
										<select name="primary_speaker_uid_1" class="form-control primary_speaker_uid_1" required>
											<option value="select_primary_speaker">Select Primary Speaker</option>
											<option value="speaker_not_listed">Speaker Not Listed</option>
											<?php foreach ($role_users as $value)
												{
												    $first_name = get_user_meta($value->ID, 'first_name');
												    $last_name = get_user_meta($value->ID, 'last_name'); ?>
													<option data-fname="<?php echo $first_name[0]; ?>" data-lname="<?php echo $last_name[0]; ?>" value="<?php echo $value->ID; ?>">
														<?php echo $value->display_name; ?>
													</option>
											<?php
												} ?>
										</select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control speaker_first_name_1" name="speaker_first_name_1" placeholder="First Name" required> </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control speaker_last_name_1" name="speaker_last_name_1" placeholder="Last Name" required> </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header"> <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">				        Second Speaker				      </a> </div>
						<div id="collapseTwo" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<div class="form-group">
									<?php global $wpdb;
										$table_users = $wpdb->prefix . "users";
										$table_usermeta = $wpdb->prefix . "usermeta";
										$capabilities = $wpdb->prefix . "capabilities";
										$role_users = $wpdb->get_results("SELECT u.ID, u.user_login, u.display_name									FROM $table_users u									WHERE u.id in (select user_id								    from $table_usermeta um									where um.meta_key = '" . $capabilities . "'									and ( um.meta_value like '%organization_basic_role%'								          or um.meta_value like '%leadership_standard_role%'								          or um.meta_value like '%leadership_basic_role%'								          or um.meta_value like '%leadership_premium_role%'								        ))								    ORDER BY display_name ASC     "); ?>
										<select name="primary_speaker_uid_2" class="form-control primary_speaker_uid_2">
											<option value="select_primary_speaker">Select Second Speaker</option>
											<option value="speaker_not_listed">Speaker Not Listed</option>
											<?php foreach ($role_users as $value)
{
    $first_name = get_user_meta($value->ID, 'first_name');
    $last_name = get_user_meta($value->ID, 'last_name'); ?>
												<option data-fname="<?php echo $first_name[0]; ?>" data-lname="<?php echo $last_name[0]; ?>" value="<?php echo $value->ID; ?>">
													<?php echo $value->display_name; ?>
												</option>
												<?php
} ?>
										</select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control speaker_first_name_2" name="speaker_first_name_2" placeholder="First Name"> </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control speaker_last_name_2" name="speaker_last_name_2" placeholder="Last Name"> </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header"> <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">				        Third Speaker				      </a> </div>
						<div id="collapseThree" class="collapse" data-parent="#accordion">
							<div class="card-body">
								<div class="form-group">
									<?php global $wpdb;
$table_users = $wpdb->prefix . "users";
$table_usermeta = $wpdb->prefix . "usermeta";
$capabilities = $wpdb->prefix . "capabilities";
$role_users = $wpdb->get_results("SELECT u.ID, u.user_login, u.display_name									FROM $table_users u									WHERE u.id in (select user_id								    from $table_usermeta um									where um.meta_key = '" . $capabilities . "'									and ( um.meta_value like '%organization_basic_role%'								          or um.meta_value like '%leadership_standard_role%'								          or um.meta_value like '%leadership_basic_role%'								          or um.meta_value like '%leadership_premium_role%'								        ))								    ORDER BY display_name ASC     "); ?>
										<select name="primary_speaker_uid_3" class="form-control primary_speaker_uid_3">
											<option value="select_primary_speaker">Select Third Speaker</option>
											<option value="speaker_not_listed">Speaker Not Listed</option>
											<?php foreach ($role_users as $value)
{
    $first_name = get_user_meta($value->ID, 'first_name');
    $last_name = get_user_meta($value->ID, 'last_name'); ?>
												<option data-fname="<?php echo $first_name[0]; ?>" data-lname="<?php echo $last_name[0]; ?>" value="<?php echo $value->ID; ?>">
													<?php echo $value->display_name; ?>
												</option>
												<?php
} ?>
										</select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control speaker_first_name_3" name="speaker_first_name_3" placeholder="First Name"> </div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control speaker_last_name_3" name="speaker_last_name_3" placeholder="Last Name"> </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group mt-3">
					<textarea class="form-control" rows="5" name="vimeo_description" placeholder=" Video Description " required> </textarea>
				</div>
				<button type="submit" name="upload_video_to_vimoe" class="btn btn-info btn-block upload_video_to_vimoe" data-site_url="<?php echo site_url() ?>" style="background-color: #831EB3; border:none;">Proceed To Upload</button>
			</form>
		</div>
	</div>
</div>
