<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_before_profile_loop_content' ); ?>

<!-- <h3>==Maintenance Mode==</h3>
<hr>
 -->
<?php 
	// $user = wp_get_current_user();
	// echo $member_id = $user->ID;
	// var_dump($member_id);
	// global $bp;
	// echo "<hr>";
	// var_dump($bp->version);
	// echo "<hr>";
	// echo $bp->displayed_user->id;
	// echo "<hr>";
	// print_r($bp);
?>

<?php if ( bp_has_profile() ) : ?>

	<?php $i=0; while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

			<?php
			 //$group_name = "Social"; 			
			// $group_name = mb_strtolower($group_name); 
			// echo "<h4>debug</h4> ";
			?>
			<?php 
			$group_name = bp_get_the_profile_group_name(); 
			$group_name = preg_match("#social#i", $group_name);
			if($group_name) $group_name = "social"; else $group_name = "d";
 
			// echo "debug ".$group_name;
			if( $group_name != "social") : ?>

				<h4><?php bp_the_profile_group_name(); ?></h4>


				<table class="profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

						<?php if ( bp_field_has_data() && $group_name != "social" ): ?>

							<tr<?php bp_field_css_class(); ?>>

								<td class="label"><?php bp_the_profile_field_name(); ?></td>

								<td class="data"><?php bp_the_profile_field_value(); ?></td>
	
							</tr>

						<?php endif; ?>

						<?php

						/**
						 * Fires after the display of a field table row for profile data.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						do_action( 'bp_profile_field_item' ); ?>

					<?php endwhile;?>

				</table>

			<?php endif; // if not SOCIAL ?>


				<!--<h4><?php echo bp_get_the_profile_group_name();?></h4>-->

				<?php //if(bp_get_the_profile_group_name() == "SOCIAL"): ?>
				<?php //if(bp_get_the_profile_group_name() == "2. Timeline"): ?>
				<?php if($i < 1): ?>
					<!-- <h4>TIMELINE</h4> -->
					<div id="timeliner">
					  <ul class="columns alex_timeline_wrap">
 <!-- 					      <li>
					          <div class="timeliner_element teal">
					              <div class="timeliner_title">
					                  <span class="timeliner_label">Event Title</span><span class="timeliner_date">03 Nov 2014</span>
					              </div>
					              <div class="content">
					                  <b>1 Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen
					              </div>
					              <div class="readmore">
					                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
					                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
					                  <a href="#" class="btn btn-info">
					                      Read More <i class="fa fa-arrow-circle-right"></i>
					                  </a>
					              </div>
					          </div>
					      </li>
 --><!-- 					      <li>
					          <div class="timeliner_element green">
					              <div class="timeliner_title">
					                  <span class="timeliner_label">Event Title</span><span class="timeliner_date">11 Nov 2014</span>
					              </div>
					              <div class="content">
					                  <b>2 Lorem Ipsum</b> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
					              </div>
					              <div class="readmore">
					                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
					                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
					                  <a href="#" class="btn btn-info">
					                      Read More <i class="fa fa-arrow-circle-right"></i>
					                  </a>
					              </div>
					          </div>
					      </li>
					-->
					      <?php
							global $wpdb;
							// echo "==debug==<br>";
					 		$user = wp_get_current_user();
					 		// var_dump($user);
							$member_id = $user->ID;
							if($member_id < 1){
								global $bp;
								$member_id = $bp->displayed_user->id;
							}

							/* select timeline data */

							$fields = $wpdb->get_results( $wpdb->prepare(
								"SELECT ID, post_title, post_content, post_excerpt,post_name
								FROM {$wpdb->posts}
								WHERE post_parent = %d
								    AND post_type = %s
								ORDER BY ID ASC",
								intval( $member_id ),
								"alex_timeline"
							) );

							foreach ($fields as $field):?>
							<?php //$post_name = trim($field->post_name); ?>
						      <li>
						          <div class="timeliner_element <?php echo !empty($field->post_name) ? $field->post_name : "teal"; ?>">
						        
						              <div class="timeliner_title">
						                  <span class="timeliner_label"><?php echo $field->post_title;?></span><span class="timeliner_date"><?php echo $field->post_excerpt;?></span>
						              </div>
						              <div class="content">
						              	  <?php echo $field->post_content;?>
						              </div>
						              <div class="readmore">
						              	  <span class="alex_item_id"><?php echo $field->ID;?></span>
						                  <a class="btn btn-primary" href="javascript:void(0);" ><i class="fa fa-pencil fa fa-white"></i></a>
						                  <a class="btn btn-bricky" href="javascript:void(0);" ><i class="fa fa-trash fa fa-white"></i></a>
						                  <a href="#" class="btn btn-info">
						                      Read More <i class="fa fa-arrow-circle-right"></i>
						                  </a>
						              </div>
						          </div>
						      </li>
					      <?php endforeach;?>
					   </ul> 
					</div>
<!-- debug -->
					<?php

						// echo "<pre>";
						// print_r($fields);
						// echo "</pre>";
						// $last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
						// echo $last_post_id;

						// $wpdb->insert(
						// 	$wpdb->posts,
							// array( 'ID' => $last_post_id+1, 'post_title' => 'Title', 'post_type' => 'alex_timeline', 'post_parent'=> $member_id),
						// 	array( '%d','%s','%s','%d' )
						// );

						 // if (current_user_can('administrator')){
						 //   echo "<pre>";
						 //   print_r($wpdb->queries);
						 //   echo "</pre>";
						 // }
					?>

				<?php endif;  ?> 
				<!-- end timeline -->


			</div>

			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php  $i++; endwhile; ?>

	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' ); ?>


 
 <!-- 4:05 -->