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
// alex_debug(1,0,"has_groups",bp_has_groups());
//  выводится только на странице групп
// global $groups_template;
// var_dump($groups_template);
// alex_debug(0,1,"groups_template",$groups_template);
$group_ids =  groups_get_user_groups( bp_loggedin_user_id() ); 	
// alex_debug(0,1,"grs",$group_ids["groups"] );
foreach($group_ids["groups"] as $group_id) { 
	$group = groups_get_group(array( 'group_id' => $group_id ));
	$grs .= $group->id.':"'.$group->name.'",';
}

// foreach ($groups_template->groups as $group) {
// 	// echo "global gr-templ ".$group->id."-".$group->name."<br>";
// 	// $grs[$group->id] = $group->name;
// 	// var user = '{ "name": "Вася", "age": 35, "isAdmin": false, "friends": [0,1,2,3] }';
// 	// $grs .= '"'.$group->id.'":"'.$group->name.',';
// 	$grs .= $group->id.':"'.$group->name.'",';
// }

$grs = substr($grs,0,-1);
$grs = "{".$grs."}";

// alex_debug(0,0,"grs",$grs);

// move groups ids and name in javascript timeliner
echo "<script>var grs = $grs;</script>";
$user_id_gr = bp_displayed_user_id();

// alex_debug(0,0,"user_id",$user_id_gr);

// alex_debug(0,1,"get_groups",groups_get_group(array("load_users"=>1)));
// var_dump(groups_get_group(1));
// <h3>my heading</h3>
// pip while ( bp_groups() ) : bp_the_group();
// pip if( 'public' == bp_get_group_status() ) {
// <input type=checkbox name="groups[]" value=" pip bp_group_id(); " pip bp_group_name();
?>
<?php /*  echo "standard templ loop"; while ( bp_groups("&user_id=1") ) : bp_the_group();   */ ?>
	<!-- <a href="<?php // bp_group_permalink(); ?>"><?php //bp_group_name(); ?></a><br> -->
<?php  //endwhile; ?>



<?php /* -------------------- */ ?>

<?php if ( bp_has_profile() ) : ?>

	<?php
		function groups_user(){
			global $bp;
			$quest_id = $bp->displayed_user->id;
			// groups for auth and noauth user
			$user_groups =  groups_get_user_groups( $quest_id ); 
			$html = "<h4>Causes</h4>";	
			$html .= '<div class="bp-widget">';
			foreach($user_groups["groups"] as $group_id) { 
				$group = groups_get_group(array( 'group_id' => $group_id ));
				$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
				$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
				$gr_avatar = bp_core_fetch_avatar($avatar_options);
				$html .='<div id="alex_groups_user">
							<a href="'.$group_permalink.'"><img src="'.$gr_avatar.'"/></a>
							<a href="'.$group_permalink.'">'.$group->name.'</a>
					  	</div>';
			}
			$html .="</div>";
			// alex_debug(1,1,"grs",$grs_notimeline);
			echo $html;
		}
	?>

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
			$gr_name = bp_get_the_profile_group_name(); 
			// return 0 or 1
			$gr_social = preg_match("#social#i", $gr_name);
			// var_dump($gr_social);
			$gr_basic_info = preg_match("#info#i", $gr_name);
			// if($gr_social) $gr_social = "social"; else $gr_social = false;
			// if($gr_basic_info) $gr_basic_info = "social"; else $gr_basic_info = false;
 
			// echo "debug ".$group_name;
			//if( $gr_social != "social") : 
			if( (bool)$gr_social == false) : ?>

				<h4><?php bp_the_profile_group_name(); ?></h4>
				
				<table class="profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
							<?php //echo $gr_basic_info;?>
						<?php //if ( bp_field_has_data() && $gr_social != "social" ): ?>
						<?php if ( bp_field_has_data() && (bool)$gr_social == false ): ?>
								
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
			<?php if( (bool)$gr_basic_info == true) groups_user(); ?>


				<!--<h4><?php echo bp_get_the_profile_group_name();?></h4>-->

				<?php //if(bp_get_the_profile_group_name() == "SOCIAL"): ?>
				<?php //if(bp_get_the_profile_group_name() == "2. Timeline"): ?>
				<?php if($i == 4): ?>
					<h4>Timeline</h4>
					<div id="timeliner">
					  <ul class="columns alex_timeline_wrap">
					      <?php
							global $wpdb;
					 		$user = wp_get_current_user();
					 		// var_dump($user);
							// $member_id = $user->ID;
							// alex_debug(1,0,'loggedin user',$member_id);
							global $bp;
							$quest_id = $bp->displayed_user->id;
							// alex_debug(1,0,'quest',$quest_id);

							/* select timeline data */

							$fields = $wpdb->get_results( $wpdb->prepare(
								"SELECT ID, post_title, post_content, post_excerpt,post_name,menu_order
								FROM {$wpdb->posts}
								WHERE post_parent = %d
								    AND post_type = %s
								ORDER BY ID ASC",
								intval( $quest_id ),
								"alex_timeline"
							) );

							foreach ($fields as $field):?>
							<?php //
								// $post_name = trim($field->post_name);
								$group = groups_get_group($field->menu_order);
								// alex_debug(0,1,"grccc",$group);
								// alex_debug(0,0,"gr",$group->name);
								// echo $group_permalink = trailingslash( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' );
								$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
								$avatar_options = array ( 'item_id' => $group->id, 'object' => 'group', 'type' => 'full', 'avatar_dir' => 'group-avatars', 'alt' => 'Group avatar', 'css_id' => 1234, 'class' => 'avatar', 'width' => 50, 'height' => 50, 'html' => false );
								$gr_avatar = bp_core_fetch_avatar($avatar_options);
								// alex_debug(0,0,"link",$group_permalink);
								// alex_debug(0,0,"avatar",$gr_avatar);

							 ?>
						      <li>
						          <div class="timeliner_element <?php echo !empty($field->post_name) ? $field->post_name : "teal"; ?>">
									<!-- <span class="timeliner_element2 <?php // echo $group->name;?>"></span> -->						        
						              <div class="timeliner_title">
						                  <span class="timeliner_label"><?php echo $field->post_title;?></span>
						                  <span class="timeliner_date"><?php echo $field->post_excerpt;?></span>
						              </div>
						              <div class="content">
						              	  <?php echo $field->post_content;?>
						              </div>
						              <div class="readmore">
						              	  <?php if($gr_avatar):?> <div id="alex_gr_avatar"><?php echo $gr_avatar;?></div><?php endif;?>
						              	  <?php if($group_permalink):?> <div id="alex_gr_link"><?php echo $group_permalink;?></div><?php endif;?>
						              	  <?php if($group->name):?> 
						              	  	 <div id="alex_gr_name_select"><?php echo $group->name;?></div>
						              	  <?php endif;?>
						              	  <?php if($group->id):?> <div id="alex_gr_id_select"><?php echo $group->id;?></div><?php endif;?>
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