<?php
/**
 * @package WordPress
 * @subpackage BuddyApp
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since BuddyApp 1.0
 */

/**
 * BuddyApp Child Theme Functions
 * Add custom code below
*/ 

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
 
function new_mail_from($old) {
return 'volunteer@dugoodr.com';
}
function new_mail_from_name($old) {
return 'DuGoodr Scout';
}


/* ********** code for member and groups not related,only styles and icons ********** */ 
/* ********** for page members ********** */ 

//Social Media Icons based on the profile user info
function member_social_extend(){
		// $dmember_id = $bp->displayed_user->id;
		$user = wp_get_current_user();
		$dmember_id = $user->ID;

		$website_info = xprofile_get_field_data('Website', $dmember_id);
		$fb_info = xprofile_get_field_data('Facebook Profile', $dmember_id);
		$google_info = xprofile_get_field_data('Google+', $dmember_id);
		$instagram_info = xprofile_get_field_data('Instagram', $dmember_id);
		$twitter_info = xprofile_get_field_data('Twitter', $dmember_id);
		$linkedin_info = xprofile_get_field_data('LinkedIn Profile', $dmember_id);
		echo '<div class="member-social">';

		if ($website_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/website.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $website_info); ?>
		</span>
	<?php
	}
		if ($fb_info) {
		?>
		<span class="fb-info">
		<?php
		// $img = '<img src="'.bloginfo('wpurl').'/wp-content/themes/buddyapp-child/images/f.png" />';
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/fb.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $fb_info); ?>
		</span>
	<?php
	}
		?>
		<?php
		if ($google_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/google+.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $google_info);
		  ?>
		</span>
	<?php
	}
		?>
		<?php
		if ($instagram_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/instagram.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $instagram_info);
		  ?>
		</span>
	<?php
	}
	?>
	<?php
		if ($twitter_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/twitter.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $twitter_info);
		  ?>
		</span>
	<?php
	}
	?>
	<?php
		if ($linkedin_info) {
		?>
		<span class="fb-info">
		<?php
		$img = '<img src="http://'.$_SERVER["HTTP_HOST"].'/wp-content/themes/buddyapp-child/images/linkedin.png" />';
		 echo $res = preg_replace("/>[^<]+/i", " target='blank'>$img", $linkedin_info);
		  ?>
		</span>
	<?php
	}
	echo '</div>';
}
add_filter( 'bp_before_member_header_meta', 'member_social_extend' ); 

/* ********** soclinks for page groups ********** */ 

function alex_display_social_groups() {

	global $wpdb;
	$gid = bp_get_group_id();
	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID, post_title, post_content
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		ORDER BY ID ASC",
		intval( $gid ),
		"alex_gfilds"
	) );

	if(!empty($fields)) echo "<div class='wrap_soclinks'>";

	foreach ($fields as $field) {

        if(!empty($field->post_content)) $data = trim($field->post_content); 
        else $data = false;

        if( !empty($data) ){

        	switch ($field->post_title) {
        		case 'Website':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/website.png" />';
        			break;  		
        		case 'Facebook':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/fb.png" />';
        			break;  		
        		case 'Google+':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/google+.png" />';
        			break;
        		case 'Twitter':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/twitter.png" />';
        			break;
        		case 'Instagram':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/instagram.png" />';
        			break;
        		case 'Linkedin':
					$img = '<img src="'.$home.'/wp-content/themes/buddyapp-child/images/linkedin.png" />';
        			break;
        	}

	        // and now display field content
	        echo '<span class="fb-info groups-soc-links"><a href="'.sanitize_text_field($data).'" target="_blank">'.$img.'</a></span>';
        }

    }
    if(!empty($fields)) echo "</div>";

    // display city/state on group page
    $table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
	$city = $wpdb->get_results( $wpdb->prepare(
		"SELECT meta_value
		FROM {$table_grmeta}
		WHERE group_id = %d
		    AND meta_key = %s
		",
		intval( $gid ),
		"city_state"
	) );
	if( !empty($city[0]->meta_value) ) {
		$html = '<div class="city_state">';
		$html .= $city[0]->meta_value;
		$html .= '</div>';
		echo $html;
	}
}

// add fields social links on page site.ru/causes/create/step/group-details/ and site.ru/causes/group_name/admin/edit-details/
add_action( 'bp_before_group_header_meta', 'alex_display_social_groups');

function alex_edit_group_fields(){

	global $bp,$wpdb;
	$gid = $bp->groups->current_group->id;

	if( !bp_is_group_creation_step( 'group-details' ) ){
	    $table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
		$city = $wpdb->get_results( $wpdb->prepare(
			"SELECT meta_value
			FROM {$table_grmeta}
			WHERE group_id = %d
			    AND meta_key = %s
			",
			intval( $gid ),
			"city_state"
		) );

		echo '<label class="" for="city_state">City, Province/State</label>';
		echo '<input id="city_state" name="city_state" type="text" value="' . esc_attr($city[0]->meta_value) . '" />';
	}

	// info about all groups
	$groups = groups_get_groups();
	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	// var_dump($a);
	$fields = $wpdb->get_results( $wpdb->prepare(
		"SELECT ID, post_title, post_content, post_excerpt
		FROM {$wpdb->posts}
		WHERE post_parent = %d
		    AND post_type = %s
		ORDER BY ID ASC",
		intval( $gid ),
		"alex_gfilds"
	) );
	foreach ($fields as $field) {

		echo '<label class="" for="alex-'.$field->ID.'">'.$field->post_title.'</label>';
		echo '<input id="alex-'.$field->ID.'" name="alex-'.$field->ID.'" type="url" value="' . esc_attr( $field->post_content ) . '" />';
	}
}

// display all fields on page manage->details
add_action( 'groups_custom_group_fields_editable', 'alex_edit_group_fields');

function alex_edit_group_fields_save(){

		global $wpdb;
		
		foreach ( $_POST as $data => $value ) {
			if ( substr( $data, 0, 5 ) === 'alex-' ) {
				$to_save[ $data ] = $value;
			}
		}

		foreach ( $to_save as $ID => $value ) {
				$ID = substr( $ID, 5 );

				$wpdb->update(
					$wpdb->posts,
					array(
						'post_content' => $value,    // [data]
					),
					array( 'ID' => $ID ),           // [where]
					array( '%s' ),                  // data format
					array( '%d' )                   // where format
				);
		}
		// update city for group page
		$gid = (int)$_POST['group-id'];
		$table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
		$res = $wpdb->update($table_grmeta,array(
				'meta_value' => sanitize_text_field($_POST['city_state']),    
			),
			array( 'meta_key' => 'city_state', 'group_id' => $gid),          
			array('%s'), array('%s','%d')                   
		);
}

add_action( 'groups_group_details_edited', 'alex_edit_group_fields_save' );

// without hook,for reused code
function alex_get_postid_and_fields( $wpdb = false){

	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	// $fields  = array("Website","Facebook","Twitter","Instagram","Google+","Linkedin");
	$fields  = array("Website","Facebook","Twitter","Instagram","Google","Linkedin");
	$id = $last_post_id+1;
	$id_and_fields = array($id,$fields);
	return $id_and_fields;
}

function alex_add_soclinks_for_all_groups_db(){

	global $wpdb;
	$groups = groups_get_groups();
	$k = 0;
	foreach ($groups['groups'] as $gr) {
		// echo $gr->id;s
		$gid[$k] = $gr->id;
		$k++;
	}

	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$postid = $postid_and_fields[0]+1;
	$fields = $postid_and_fields[1];

	$g = 0;
	$total_group = count($gid);
	for( $i=0; $i < $total_group; $i++){
		foreach ($fields as $field_name) {
			if(preg_match("#google#i", $field_name) === 1) $field_name = $field_name."+";
			$wpdb->insert(
				$wpdb->posts,
				array( 'ID' => $postid, 'post_title' => $field_name, 'post_type' => 'alex_gfilds', 'post_parent'=>$gid[$g]),
				array( '%d','%s','%s','%d' )
			);
			$postid++; 
		} 
		$g++;
	}
	echo "Fields for groups has been successfully imported! Total group: ".$total_group;

}

// IMPORTANT !!! execute only 1 time !!! add all fields social links for groups in data base
// add_action("wp_head","alex_add_soclinks_for_all_groups_db");
// add_action( 'bp_before_group_body','alex_add_soclinks_for_all_groups_db');

/* *********** */

function add_soclinks_only_for_one_group_db(){

	global $wpdb;
	$gr_last_id = $wpdb->get_row("SELECT id FROM `{$wpdb->prefix}bp_groups` ORDER BY date_created DESC");
	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$postid = $postid_and_fields[0]+1;
	$fields = $postid_and_fields[1];

	alex_debug(0,1,"fie",$fields);

	foreach ($fields as $field_name) {

		if( !empty($_COOKIE['alex-'.$field_name]) ) {
			echo $post_content = sanitize_text_field($_COOKIE['alex-'.$field_name]);
		}
		else $post_content = '';
		if(preg_match("#google#i", $field_name) === 1) $field_name = $field_name."+";
		
		echo $field_name." - ";

		// if( !empty($post_content)){
			$wpdb->insert(
				$wpdb->posts,
				array( 'ID' => $postid, 'post_title' => $field_name, 'post_type' => 'alex_gfilds', 'post_parent'=>$gr_last_id->id, 'post_content'=> $post_content),
				array( '%d','%s','%s','%d', '%s' )
			);
			$postid++; 
		// }
	} 

	foreach ($fields as $field_name) {
		// unset($_COOKIE['alex-'.$field_name]);
		// delete cookie
		setcookie( 'alex-'.$field_name, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}
}

// Fires after the group has been successfully created (variation 1)
add_action( 'groups_group_create_complete','add_soclinks_only_for_one_group_db');

add_action( 'groups_create_group_step_save_group-details','alex_save_socialinks_cookies' );
function alex_save_socialinks_cookies(){
	foreach ($_POST as $k => $v) {
		$k = str_replace("+", "", $k);
		$v = sanitize_text_field($v);
		if(  preg_match("#^alex-#i", $k) === 1) setcookie($k, $v,8 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN,is_ssl());	
	}
}

// add fieldss social links on page site.ru/causes/create/step/group-details/
add_action( 'bp_after_group_details_creation_step',"alex_group_create_add_socialinks" );

function alex_group_create_add_socialinks(){

	global $bp,$wpdb;

	// get fields social links
	$postid_and_fields = alex_get_postid_and_fields($wpdb);
	$fields = $postid_and_fields[1];

	foreach ($fields as $field) {

		if( !empty($_COOKIE['alex-'.$field]) ) {
			$user_fill = $_COOKIE['alex-'.$field];
		}
		else $user_fill = '';

		if(preg_match("#google#i", $field) === 1) $field = $field."+";

		echo '<label class="" for="alex-'.$field.'">'.$field.'</label>';
		echo '<input id="alex-'.$field.'" name="alex-'.$field.'" type="url" value="'.$user_fill.'" />';
	}
}

// delete fields for social links when group deleted 
add_action( 'groups_delete_group', 'alex_group_delete_fields_soclinks');
function alex_group_delete_fields_soclinks(){
	global $wpdb;
	$gid = (int)$_GET['gid'];
    $wpdb->delete( $wpdb->posts, array('post_type'=>'alex_gfilds','post_parent'=> $gid), array('%s','%d') );
}

add_action( 'groups_create_group_step_save_group-details','alex_add_city_for_group' );
function alex_add_city_for_group(){
	global $bp,$wpdb;
	$city_state = sanitize_text_field($_POST['city_state']);
	$wpdb->insert(
		$wpdb->prefix."bp_groups_groupmeta",
		array( 'group_id' => $bp->groups->new_group_id, 'meta_key' => 'city_state', 'meta_value'=> $city_state),
		array( '%d','%s','%s' )
	);
}

function buddyapp_search_shortcode() {
    $context = sq_option( 'search_context', '' );
    echo kleo_search_form(array('context' => $context));
}

// add_shortcode('buddyapp_search_shortcode','buddyapp_search_shortcode');
// use [buddyapp_search_shortcode]


/* ****** modification searchbox and signin/register form for landing page ******* */

// add_shortcode( 'kleo_search_form', 'kleo_search_form' );
add_shortcode( 'alex_search_form', 'alex_search_form' );
function alex_search_form( $atts = array(), $content = null ) {

	$form_style = $type = $placeholder = $context = $hidden = $el_class = '';
	
	extract(shortcode_atts(array(
		'form_style' => 'default',
		// 'form_style' => 'groups',
		'type' => 'both',
		// 'context' => '',
		'context' => array('groups','members'),
		'action' => home_url( '/' )."members",
		'el_id' => 'searchform',
		'el_class' => 'search-form',
		'input_id' => 'main-search',
		'input_class' => 'header-search',
		'input_name' => 's',
		'input_placeholder' => __( 'Search', 'buddyapp' ),
		'button_class' => 'header-search-button',
		'hidden' => '',
	), $atts));

	$el_class .= ' kleo-search-wrap kleo-search-form ';

	if ( is_array( $context ) ) {
		$context = implode( ',', $context );
	}

	$ajax_results = 'yes';
	$search_page = 'yes';

	if ( $type == 'ajax' ) {
		$search_page = 'no';
	} elseif ( $type == 'form_submit' ) {
		$ajax_results = 'no';
	}

	$output = '<div class="search">
				<i> </i>
	<div class="s-bar">
	<form id="' . $el_id . '" class="' . $el_class . ' second-menu" method="get" ' . ( $search_page == 'no' ? ' onsubmit="return false;"' : '' ) . ' action="' . $action . '" data-context="' . $context  .'">';
	$output .= '<input id="' . $input_id . '" class="' . $input_class . ' ajax_s" autocomplete="off" type="text" name="' . $input_name . '" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Search\';}" value="Find your cause...">';
	$output .= '<input type="submit" class="' . $button_class . '" value="Search" />';
	if ( $ajax_results == 'yes' ) {
		$output .= '<div class="kleo_ajax_results search-style-' . $form_style . '"></div>';
	}
	$output .= $hidden;
	$output .= '</form>
	</div>
	</div>';

	return $output;
}


add_shortcode( 'alex_nothome_search_form', 'alex_nothome_search_form' );
function alex_nothome_search_form( $atts = array(), $content = null ) {

	$form_style = $type = $placeholder = $context = $hidden = $el_class = '';
	extract(shortcode_atts(array(
		'form_style' => 'default',
		'type' => 'both',
		'context' => array('groups','members'),
		'action' => home_url( '/' )."members",
		'el_id' => 'searchform',
		'el_class' => 'search-form',
		'input_id' => 'main-search',
		'input_class' => 'header-search',
		'input_name' => 's',
		'input_placeholder' => __( 'Search', 'buddyapp' ),
		'button_class' => 'header-search-button',
		'hidden' => '',
	), $atts));

	$el_class .= ' kleo-search-wrap kleo-search-form ';

	if ( is_array( $context ) ) {
		$context = implode( ',', $context );
	}

	$ajax_results = 'yes';
	$search_page = 'yes';

	if ( $type == 'ajax' ) {
		$search_page = 'no';
	} elseif ( $type == 'form_submit' ) {
		$ajax_results = 'no';
	}

	if ( function_exists('bp_is_active') && $context == 'members' ) {
		//Buddypress members form link
		$action = bp_get_members_directory_permalink();

	} elseif ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) && $context == 'groups' ) {
		//Buddypress group directory link
		$action = bp_get_groups_directory_permalink();

	} elseif ( class_exists('bbPress') && $context == 'forum' ) {
		$action = bbp_get_search_url();
		$input_name = 'bbp_search';

	} elseif ( $context == 'product' ) {
		$hidden .= '<input type="hidden" name="post_type" value="product">';
	}

	$output = '<form id="' . $el_id . '" class="' . $el_class . '" method="get" ' . ( $search_page == 'no' ? ' onsubmit="return false;"' : '' ) . ' action="' . $action . '" data-context="' . $context  .'">';
	$output .= '<input id="' . $input_id . '" class="' . $input_class . ' ajax_s" autocomplete="off" type="text" name="' . $input_name . '" value="" placeholder="' . $input_placeholder . '">';
	$output .= '<button type="submit" class="' . $button_class . '"></button>';
	if ( $ajax_results == 'yes' ) {
		$output .= '<div class="kleo_ajax_results search-style-' . $form_style . '"></div>';
	}
	$output .= $hidden;
	$output .= '</form>';

	return $output;
}

add_action('bp_before_register_page' ,"alex_add_icon_close_for_register_page");
function alex_add_icon_close_for_register_page(){
	echo '<div class="wrap-reg-close"><a class="reg-close" href="/">×</a></div>';
}

// all $args value show buddypress function bp_has_members()
// show first 20 exists members if value serach empty for click search button 
function my_bp_loop_querystring( $query_string, $object ) {

	$search = mb_strtolower( strip_tags( trim($_REQUEST['s']) ) );
    if ( ! empty( $search ) and ($search == 'search') ) {
	    $query_string .= '&search_terms=';
	    $query_string .= '&user_ids=1,2,3,4,5,6,7,8,9.10,11,12,13,14,15,16,17,18,19,20';
    }
 
    return $query_string;
}
add_action( 'bp_legacy_theme_ajax_querystring', 'my_bp_loop_querystring', 100, 2 );

/* вывод системных данных в форматированном виде */
function alex_debug ( $show_text = false, $is_arr = false, $title = false, $var, $sep = "| "){

	// Example: alex_debug(1,0,'s',$search);
	$debug_text = "<br>========Debug MODE==========<br>";
	if( boolval($show_text) ) echo $debug_text;
	if( boolval($is_arr) ){
		echo "<br>".$title."-";
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		echo "<hr>";
	} else echo $title."-".$var;
	if($sep == "l") echo "<hr>"; else echo $sep;
}
/* вывод системных данных в форматированном виде */


add_action("wp_head","alex_include_css_js",90);

function alex_include_css_js(){
	if( is_front_page() ){
		echo '<link href="'.get_stylesheet_directory_uri().'/search-templ/css/style2.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/search-templ/css/style.css" rel="stylesheet" type="text/css" media="all"/>';
	}

	if( !bp_has_profile() ) return;

	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }

    // $member_name = bp_core_get_username($user_id_islogin);
    $member_name = bp_core_get_username($user_id_isnotlogin);

	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view = preg_match("#^/members/[a-z0-9_]+/profile/$#i", $url_s);

	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/members/".$member_name."/$#i", $url_s);

	if($profile_view or $profile_view_notdefault){

		/* *** disable standart wordpress style ***** */

		function alex_dequeue_default_css() {
		  wp_dequeue_style('bootstrap');
		  wp_deregister_style('bootstrap');
		}
		add_action('wp_enqueue_scripts','alex_dequeue_default_css',100);

		/* *** disable standart wordpress style ***** */

		echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">';
		echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/css/jquery-timeliner.css" rel="stylesheet" type="text/css" media="all"/>';
		echo '<link href="'.get_stylesheet_directory_uri().'/libs/alex/fix-style.css" rel="stylesheet" type="text/css" media="all"/>';
	}
}


add_action("wp_footer", "alex_custom_scripts",100);

function alex_custom_scripts()
{

	if( !bp_has_profile() ) return;

	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }

    $member_name = bp_core_get_username($user_id_isnotlogin);
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view = preg_match("#^/members/[a-z0-9_]+/profile/$#i", $url_s);

    // full path = http://dugoodr.com/members/admin7/profile/
    // short path, insted activity set profile http://dugoodr.dev/members/admin7/
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/members/".$member_name."/$#i", $url_s);

	if($profile_view or $profile_view_notdefault){
		echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>';
		echo '<script type="text/javascript" src="'.get_stylesheet_directory_uri().'/libs/jqtimeliner/js/jquery-timeliner.js"></script>';
	?>
	<script type="text/javascript">
		jQuery( document ).ready(function($) {

			function alex_onadd(_data){
					var alex_tl_grp_id = false;
				    for (var key in grs) {
				    	if(grs[key] == _data.alex_gr_name_select) alex_tl_grp_id = key;
				    }
					var data = {
						'action': 'alex_add_timeline',
						'date': _data.date,
						'title': _data.title,
						'content': _data.content,
						'class': _data.class,
						'alex_tl_grp_id': alex_tl_grp_id
						// 'query': true_posts,
					};

					$.ajax({
						url:ajaxurl, // обработчик
						data:data, // данные
						type:'POST', // тип запроса
						success:function(data){
							if( data ) { 
						      location.reload();
							} else { console.log("data send with errors!");}
						}

					 });
			}

			function alex_ondelete(_data){
		        if(confirm("Are you sure to delete ?")){
    				$( "#timeliner" ).on( "click", ".readmore .btn-danger", function() {
					   var html = $(this).parents("li");
					   var id = html.find(".alex_item_id").text();
					   html.hide();

	   					var data = {
							'action': 'alex_del_timeline',
							'id':id
						};

						$.ajax({
							url:ajaxurl, // обработчик
							data:data, // данные
							type:'POST', // тип запроса
							success:function(data){
								if( data ) { 
								} else { console.log("data send with errors!");}
							}

						 });
						// end ajax
					});
		        }
			}

			function alex_onedit(_data){
				var alex_tl_grp_id = false;
			    for (var key in grs) {
			    	if(grs[key] == _data.alex_gr_name_select) alex_tl_grp_id = key;
			    }

				var data = {
					'action': 'alex_edit_timeline',
					'id': _data.id_alex,
					'date': _data.date,
					'title': _data.title,
					'content': _data.content,
					'class': _data.class,
					'alex_tl_grp_id': alex_tl_grp_id

				};

				$.ajax({
					url:ajaxurl, // обработчик
					data:data, // данные
					type:'POST', // тип запроса
					success:function(data){
						console.log("ajax response get success!");
						if( data ) { 
							// console.log(data);
				      		location.reload();  		
						} else { console.log("data send with errors!");}
					}

				 });
			}

		    var tl = jQuery('#timeliner').timeliner({onAdd:alex_onadd, onDelete:alex_ondelete, onEdit:alex_onedit});

		    <?php
		    	// get user_id for logged user
		 		$user = wp_get_current_user();
				$member_id = $user->ID;
				// get user_id for notlogged user
				global $bp;
				$profile_id = $bp->displayed_user->id;

				if($member_id < 1 or ($member_id != $profile_id) ){
					echo '$("#timeliner .btn-primary, #timeliner .btn-danger").remove();';
					echo '$("#timeliner .alex_btn_add_new").hide();';
				}
		    ?>

		    <?php if( !is_user_logged_in()):?>
				 $(document).scroll(function(){ 
				 	// console.log("fired scroll");
				 	// $("#timeliner .date_separator").hide();
	 			 });
		    <?php endif;?>

		});

	</script>
	<?php
	}
}

add_action('wp_ajax_alex_del_timeline', 'alex_del_timeline');

function alex_del_timeline(){
	$id = trim( (int)$_POST['id']);
	if(!empty($id)){
		global $wpdb;
		$wpdb->delete( $wpdb->posts, array( 'ID' => $id ), array( '%d' ) ); 
		echo true;
	}
	exit;
}

add_action('wp_ajax_alex_add_timeline', 'alex_add_timeline');

function alex_add_timeline() {

	$date = sanitize_text_field($_POST['date']);
	$title = sanitize_text_field($_POST['title']);
	$content = sanitize_text_field($_POST['content']);
	$class = sanitize_text_field($_POST['class']);
	$alex_tl_grp_id = (int)($_POST['alex_tl_grp_id']);

	global $wpdb;
	$last_post_id = $wpdb->get_var( "SELECT MAX(`ID`) FROM {$wpdb->posts}");
	$user = wp_get_current_user();
	$member_id = $user->ID;

	$wpdb->insert(
		$wpdb->posts,
		array( 'ID' => $last_post_id+1, 'post_title' => $title, 'post_name' => $class , 'post_content'=> $content, 'post_excerpt'=>$date, 'post_type' => 'alex_timeline', 'post_parent'=> $member_id, 'menu_order'=>$alex_tl_grp_id),
		array( '%d','%s','%s','%s','%s','%s','%d','%d' )
	);
	$res = true;
	echo $res;
	exit;
}

add_action('wp_ajax_alex_edit_timeline', 'alex_edit_timeline');

function alex_edit_timeline() {

	$id = (int)($_POST['id']);
	$date = sanitize_text_field($_POST['date']);
	$title = sanitize_text_field($_POST['title']);
	$content = sanitize_text_field($_POST['content']);
	$class = sanitize_text_field($_POST['class']);
	$alex_tl_grp_id = (int)($_POST['alex_tl_grp_id']);


	if($id > 0){
		global $wpdb;
		$wpdb->update( $wpdb->posts,
			array( 'post_title' => $title, 'post_name' => $class , 'post_content'=> $content, 'post_excerpt'=>$date,'menu_order'=>$alex_tl_grp_id ),
			array( 'ID' => $id ),
			array( '%s', '%s', '%s', '%s','%d' ),
			array( '%d' )
		);
	}
	$res = true;
	echo $res;
	exit;
}

// delete jquery-migrate for correct work Responsive Dynamic Timeline Plugin For jQuery - Timeliner
add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

function remove_jquery_migrate( &$scripts){
    // if(!is_admin()){
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ) );
    // }
}

/* Simple theme specific login form - OVERRIDE STANDARD FORM */
// add_shortcode( 'sq_login_form', 'sq_login_form_func' );
add_shortcode( 'alex_sq_login_form', 'alex_sq_login_form_func' );

/**
 * Return login form for shortcode
 * @param $atts
 * @param null $content
 * @return string
 */
function alex_sq_login_form_func( $atts, $content = null ) {

	if( !is_user_logged_in()){

		$output = $style = $disable_modal = '';

		extract( shortcode_atts( array(
				'style' => 'white',
				'before_input' => '',
				'disable_modal' => ''
		), $atts) );

		$output .= '<div class="login-page-wrap">';
		ob_start();
		kleo_get_template_part( 'page-parts/login-form', null, compact( 'style', 'before_input' ) );
		$output .= ob_get_clean();
		$output .= '</div>';

		if ( $disable_modal == '' ) {
			add_filter( "get_template_part_page-parts/login-form", '__return_false');
		}

		$output .= "<a class='show_home_form' href='#'>Sign in</a>";

		add_action("wp_footer", "alex_custom_scripts_login_form",110);

		function alex_custom_scripts_login_form(){
			?>
			<script type="text/javascript">
				jQuery( document ).ready(function($) {
					$(".home-page .home_form_close").click(function(){
						$(".home-page .login-page-wrap").hide();
						$(".home-page .show_home_form").css({"display":"block"});
					});
					$(".home-page .show_home_form").click(function(){
						$(".home-page .login-page-wrap").show();
						$(".home-page .show_home_form").hide();
					});
				});
			</script>
			<?php
		}

		return $output;
	}
}

// add_action("bp_before_activity_loop","alex_custom_before_activity_loop");
function alex_custom_before_activity_loop(){
	alex_debug(1,0,0,0);
}


/* ****** adding a custom activity - compliment(review) - override method ajax_review() for BP Member Reviews ******* */

// add_action('wp_ajax_bp_user_review',   array($this, 'ajax_review'),300);
// add_action('wp_ajax_bp_user_review',   array("BP_Member_Reviews", 'ajax_review'),300);

add_action('wp_ajax_bp_user_review','ajax_review_override',1);
function ajax_review_override(){

if ( class_exists('BP_Member_Reviews') ){
	
    $user_id = intval($_POST['user_id']);
    if( !wp_verify_nonce( $_POST['_wpnonce'], 'bp-user-review-new-'.$user_id ) ) die();

    // alex code
	global $wpdb;
	$bp_member_r = new BP_Member_Reviews();

    $stars      = $bp_member_r->settings['stars'];
    $criterions = $bp_member_r->settings['criterions'];

    $post = array(
        'post_type'   => $bp_member_r->post_type,
        'post_status' => 'pending'
    );

    if($bp_member_r->settings['autoApprove'] == 'yes'){
        $post['post_status'] = 'publish';
    }

    $response = array(
        'result' => true,
        'errors' => array()
    );

    if( ! apply_filters( 'bp_members_reviews_review_allowed', true, get_current_user_id(), $user_id ) ){
        $response['result'] = false;
        $response['errors'][] = __('You can not put review for this user', 'bp-user-reviews');
    }

    if(is_user_logged_in() && (get_current_user_id() == $user_id)){
        $response['result'] = false;
        $response['errors'][] = __('You can not put yourself reviews', 'bp-user-reviews');
    }

    $review_meta = array(
        'user_id' => $user_id,
        'stars'   => $stars,
        'type'    => $bp_member_r->settings['criterion'],
        'guest'   => false
    );

    if( ! is_user_logged_in() ){
        $review_meta['guest'] = true;

        if(!isset($_POST['name']) || empty($_POST['name'])){
            $response['result'] = false;
            $response['errors'][] = __('Name field is required', 'bp-user-reviews');
        } else {
            $review_meta['name'] = esc_attr($_POST['name']);
        }

        if(!isset($_POST['email']) || empty($_POST['email'])){
            $response['result'] = false;
            $response['errors'][] = __('Email field is required', 'bp-user-reviews');
        } elseif (!is_email($_POST['email'])){
            $response['result'] = false;
            $response['errors'][] = __('Email is wrong', 'bp-user-reviews');
        } else {
            $review_meta['email'] = esc_attr($_POST['email']);
        }
    }

    if($bp_member_r->settings['multiple'] == 'no'){
        if(!is_user_logged_in()){
            if($bp_member_r->checkIfReviewExists($review_meta['email'], $user_id) > 0){
                $response['result'] = false;
                $response['errors'][] = __('Already reviewed by you', 'bp-user-reviews');
            }
        } else {
            if($bp_member_r->checkIfReviewExists(get_current_user_id(), $user_id) > 0){
                $response['result'] = false;
                $response['errors'][] = __('Already reviewed by you', 'bp-user-reviews');
            }
        }
    }

    if(!is_array($_POST['criteria'])){
        $val = esc_attr($_POST['criteria']);

        if($val < 1 || $val > $stars){
            $response['result'] = false;
            $response['errors']['empty'] = __('You must select all stars', 'bp-user-reviews');
        }

        $review_meta['average'] = ($val / $stars) * 100;
    } else {
        foreach($_POST['criteria'] as $index=>$val){
            if($val < 1 || $val > $stars){
                $response['result'] = false;
                $response['errors']['empty'] = __('You must select all stars', 'bp-user-reviews');
                continue;
            }

            $name = $criterions[$index];
            $review_meta['criterions'][$name] = (esc_attr($val) / $stars) * 100;
        }

        $review_meta['average'] = round( array_sum($review_meta['criterions']) / count($review_meta['criterions']) );
    }


    if($bp_member_r->settings['review'] == 'yes') {
        if (empty($_POST['review'])) {
            $response['result'] = false;
            $response['errors'][] = __('Review can`t be empty', 'bp-user-reviews');
        } elseif (mb_strlen($_POST['review']) < $bp_member_r->settings['min_length']) {
            $response['result'] = false;
            $response['errors'][] = sprintf(__('Review must be at least %s characters', 'bp-user-reviews'), $bp_member_r->settings['min_length']);
        } else {
            $review_meta['review'] = esc_attr($_POST['review']);
        }
    }

    if (class_exists('Akismet')){
        $review['user_ip']      = Akismet::get_ip_address();
        $review['blog']         = get_option( 'home' );
        $review['blog_lang']    = get_locale();
        $review['blog_charset'] = get_option('blog_charset');
        if(!is_user_logged_in()){
            $review['comment_author']       = $review_meta['name'];
            $review['comment_author_email'] = $review_meta['email'];
        } else {
            $user = get_userdata($user_id);
            $review['comment_author']       = $user->display_name;
            $review['comment_author_email'] = $user->user_email;
        }
        $review['comment_content'] = esc_attr($_POST['review']);

        $valid = Akismet::http_post( Akismet::build_query( $review ), 'comment-check' )[1];

        if($valid == false){
            $post['post_status'] = 'spam';
        }
    }

    if($response['result'] === true){
        $review_id = wp_insert_post($post);

        foreach($review_meta as $key=>$value){
            if(is_string($value)) $value = trim($value);
            update_post_meta($review_id, $key, $value);
        }
    }

	/* ****** adding a custom activity - compliment(review) ******* */

	$table_activity = $wpdb->prefix."bp_activity";
	$to_user_id = intval($_POST['user_id']);
	$user = wp_get_current_user();
	$from_user_id = $user->ID;

	$primary_link = bp_core_get_userlink($to_user_id);
	$user_link = bp_core_get_userlink($from_user_id);
	$to_user_link_nohtml = bp_core_get_userlink($to_user_id, false, true);
	$date_recorded = date( 'Y-m-d H:i:s');
	$action = $primary_link.' has received a <a href="'.$to_user_link_nohtml.'reviews/">compliment</a> from '.$user_link;

	$q = $wpdb->prepare( "INSERT INTO {$table_activity} (user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide, is_spam ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d )", $to_user_id, 'compliments', 'compliment_sent', $action, '', $to_user_link_nohtml, $date_recorded, 0, 0, 0,0);

	$wpdb->query( $q );	
	/* ****** adding a custom activity - compliment(review) ******* */
    wp_send_json($response);
    die();
	}
}

// only for debug
// add_action("wp_footer","wp_get_name_page_template");

function wp_get_name_page_template(){

    global $template,$bp;
	// get user_id for logged user
	$user = wp_get_current_user();
	$user_id_islogin = $user->ID;
	// get user_id for notlogged user
	global $bp;
	$user_id_isnotlogin = $bp->displayed_user->id;

	if(!$user_id_islogin){ $user_id_islogin = $user_id_isnotlogin; }
	$url_s = $_SERVER['REQUEST_URI'];
	$profile_view_notdefault = preg_match("#^/members/".$member_name."/$#i", $url_s);

	echo "has page profile= "; var_dump(bp_has_profile());

    echo "1- ".$template;
	echo "<br>2- ".$page_template = get_page_template_slug( get_queried_object_id() )." | ";
	echo "<br>3- ".$_SERVER['PHP_SELF'];
	echo "<br>4- ".__FILE__;
	echo "<br>5- ".$_SERVER["SCRIPT_NAME"];
	echo "<br>6- ".$_SERVER['DOCUMENT_ROOT'];
	alex_debug(1,1,0,$_SERVER);
}

function register_widgets_for_groups_pages(){
	register_sidebar( array(
		'name' => "Groups sidebar",
		'id' => 'right-sidebar-for-group',
		'description' => 'Right sidebar for widgets',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
		'before_widget' =>  '<div id="%1$s" class="widget %2$s">',
		'after_widget' =>  '</div>',
	) );
}
add_action( 'widgets_init', 'register_widgets_for_groups_pages' );


/* for work with bp group revies on header group */
remove_action( 'bp_group_header_meta', 'bpgr_render_review' );

add_action("bp_group_header_meta","alex_add_rating_header_group");
function alex_add_rating_header_group(){

	if(class_exists('BP_Group_Reviews')){
		global $bp;

		// Don't show for groups that have reviews turned off
		if ( !BP_Group_Reviews::current_group_is_available() )
			return;

		// Rendering the full span so you can avoid editing your group-header.php template
		// If you don't like it you can call bpgr_review_html() yourself and unhook this function ;)

		$gid = bp_get_group_id();
		$group = groups_get_group($gid);
		$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
		$rating_score  = isset( $bp->groups->current_group->rating_avg_score ) ? $bp->groups->current_group->rating_avg_score : '';
		$rating_number = isset( $bp->groups->current_group->rating_number ) ? $bp->groups->current_group->rating_number : '';
	
		global $wpdb;
		$website = $wpdb->get_results( $wpdb->prepare(
			"SELECT post_content
			FROM {$wpdb->posts}
			WHERE post_parent = %d
			    AND post_type = %s
			    AND post_title = %s
			ORDER BY ID ASC",
			intval( $gid ),
			"alex_gfilds",
			'Website'
		) );
		$ext_url = $website[0]->post_content;
		?>
		<span class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
	    <span itemprop="ratingValue"  content="<?php echo $rating_score;?>"></span>
	    <span itemprop="bestRating"   content="5"></span>
	    <span itemprop="ratingCount"  content="<?php echo $rating_number;?>"></span>
	    <span itemprop="itemReviewed" content="Group"></span>
	    <span itemprop="name" content="<?php echo $group->slug;?>"></span>
	    <span itemprop="url" content="<?php echo $group_permalink;?>"></span>
	    <?php if( !empty($ext_url) ):?>
	    <span itemprop="sameAs" content="<?php echo $ext_url;?>"></span>
		<?php endif;?>
		<?php echo bpgr_review_html() ?>
		</span>
	<?php
	}
}

// for schema.org on google (there was 1 error - missing url breadcrumb)
// unhoock old function
function alex_remove_junk() { remove_action( 'bp_before_group_body','kleo_bp_group_title', 1 ); }
add_action( 'after_setup_theme', 'alex_remove_junk', 999 );

add_action( 'bp_before_group_body','alex_kleo_bp_group_title',10);
function alex_kleo_bp_group_title() {
?>
    <div class="bp-title-section">
        <h1 class="bp-page-title"><?php echo kleo_bp_get_group_page_title();?></h1>
        <?php 
	        $gid = bp_get_group_id();
			$group = groups_get_group($gid);
			$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
	        $breadcrumb = kleo_breadcrumb( array( 'container' => 'ol', 'separator' => '', 'show_browse' => false, 'echo' => false ) );
	        echo $bc_replace = str_replace('a href=""', 'a href="'.$group_permalink.'"', $breadcrumb);
         ?>
    </div>
<?php
}

/* for work with bp group revies on header group */


/* ************ DW actions ************ */

add_filter('bp_get_send_public_message_button', '__return_false');

function remove_wp_adminbar_profile_link() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('my-account-activity-favorites');
}
add_action( 'wp_before_admin_bar_render', 'remove_wp_adminbar_profile_link' );
add_filter( 'bp_activity_can_favorite', '__return_false' );
add_filter( 'bp_get_total_favorite_count_for_user', '__return_false' );


add_action('wp_footer','alex_ccc1');
function alex_ccc1(){
	$groups = BP_Groups_Group::get(array(
								'type'=>'alphabetical',
								'per_page'=>999
								));
	// print_r($groups);
	// отбирает только id и name группы
	foreach ($groups['groups'] as $gr) {
		echo $gr->id. " - ".$gr->name."<br>";
	}
	echo "---7171";
}