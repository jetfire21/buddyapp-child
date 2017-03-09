<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*********** this code for work with twitter ***********/

// add_action('wp_ajax_al21_tw_automate_enable', 'al21_tw_automate_enable');
// function al21_tw_automate_enable(){
// 	print_r($_REQUEST);
// 	exit;
// }

add_action('wp_ajax_get_tweets', 'alex_my_action_callback');
add_action('wp_ajax_nopriv_get_tweets', 'alex_my_action_callback');
function alex_my_action_callback(){

// print_r($_POST);
$gr_id = (int)$_POST['gr_id'];
$gr_permalink = sanitize_text_field($_POST['gr_permalink']);
$gr_name = sanitize_text_field($_POST['gr_name']);
$gr_avatar = sanitize_text_field($_POST['gr_avatar']);
$tw_user = sanitize_text_field($_POST['user']);
    // [gr_id] => 13
    // [gr_permalink] => http://dugoodr.dev/causes/linux/
    // [gr_name] => Linux
	// require 'libs/twitter/test.php';
// require 'libs/twitter/TwitterAPIExchange.php';
// require_once 'libs/twitter/tw-api.php';
require_once 'tw-api.php';
$twitter_debug = false;
// $twitter_username = 'ottawafoodbank';
// $twitter_username = 'kaspersky_ru';
// $tweets = a21_tw_get_tweets($settings,$url,$getfield,$requestMethod,$twitter_debug);
$tweets = a21_tw_get_tweets($tw_user,$settings,$url,$requestMethod,$twitter_debug, 15);

global $wpdb;
$table_activity = $wpdb->prefix."bp_activity";

if(!$twitter_debug):
	foreach ($tweets as $k => $v):

		$date_format = ago($v->created_at,1,1);
		$tweet = $v->text;
		$date = $v->created_at; //Wed Mar 08 14:11:10 +0000 2017
		$date_to_db = date("Y-m-d H:i:s", strtotime($date)); // 2017-03-08 14:11:10
		// data-livestamp="2017-03-08T17:30:01+0000"
		$date_for_html = date("Y-m-dTH:i:s+0000", strtotime($date)); // 2017-03-08 14:11:10

		if(!empty($v->entities->urls[0]->url)) $short_link = $v->entities->urls[0]->url;
		// extract($output);
		// echo "\r\n".$tweet.": ".$date_to_db.": ".$short_link.": ".$date_for_html.": ".$date_format."\r\n \r\n";

		//$check_tweet_db = $wpdb->get_row("SELECT id FROM `{$table_activity}` WHERE date_recorded='{$date_to_db}' AND content='{$tweet}' ");
		$check_tweet_db = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM `{$table_activity}` WHERE date_recorded='%s' AND content='%s' ",
			$date_to_db,$tweet) );;
		// var_dump($check_tweet_db);

	   // echo "<pre>";
	   // print_r($wpdb->queries);
	   // echo "</pre>";


		// if value = NULL, in other words if tweet not exist db then add it in db and output on site
		if( is_null($check_tweet_db) ) {

			// echo "\r\n == this tweet not exist in db \r\n";

			$user = wp_get_current_user();
			$from_user_id = $user->ID;
			$group = groups_get_group($gr_id);
			$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
			$avatar_options = array ( 'item_id' => $gr_id, 'object' => 'group','avatar_dir' => 'group-avatars', 'html' => false );
			$gr_avatar = bp_core_fetch_avatar($avatar_options);
			$action = '<a href="http://dugoodr.dev/members/toddroberts/" title="Todd2_LongName">Todd2_LongName</a> posted tweet <a href="http://dugoodr.dev/causes/ottawa-food-bank/">'.$group->name.'</a>';

			$q = $wpdb->prepare( "INSERT INTO {$table_activity} (user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide, is_spam ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d )", $from_user_id, 'groups', 'new_event', $action, $tweet, $to_user_link_nohtml, $date_to_db, $gr_id, 0, 0,0);
			$wpdb->query( $q );	


			$html .= '
				<li class="groups activity_update activity-item date-recorded-1488491970" id="activity-900">
					<div class="activity-avatar">
						<a href="http://dugoodr.dev/members/admin7/">
							<img src="http://dugoodr.dev/wp-content/uploads/avatars/1/5803f61eb836b-bpthumb.jpg" class="avatar user-1-avatar avatar-50 photo" width="50" height="50" alt="Profile picture of Admin" />
						</a>
					</div>

					<div class="activity-content">
						<div class="activity-header">
							<p><a href="http://dugoodr.dev/members/admin7/" title="Admin">Admin</a> posted tweet <a href="'.$gr_permalink.'" class=""><img src="'.$gr_avatar.'" class="avatar group-8-avatar avatar-20 photo" width="20" height="20" alt="Group cause logo of Ottawa Food Bank" /></a><a href="http://dugoodr.dev/causes/ottawa-food-bank/">'.$gr_name.'</a> <a href="http://dugoodr.dev/activity/p/168/" class="view activity-time-since" title="View Discussion">
								<span class="time-since">'.$date_format.'</span>
							</a></p>
						</div>	
							<div class="activity-inner">
								<p>'.$tweet.'</p>
							</div>	
						<div class="activity-meta">		
							<a href="?ac=168/#ac-form-168" class="button acomment-reply bp-primary-action" id="acomment-comment-168">
										Comment <span>0</span>					</a>				
								<a href="http://dugoodr.dev/activity/delete/168/?_wpnonce=d14e60b831" class="button item-button bp-secondary-action delete-activity confirm" rel="nofollow">Delete</a>
						</div>
					</div>
						<div class="activity-comments">	
								<form action="http://dugoodr.dev/activity/reply/" method="post" id="ac-form-168" class="ac-form">
									<div class="ac-reply-avatar"><img src="http://dugoodr.dev/wp-content/uploads/avatars/1/5803f61eb836b-bpthumb.jpg" class="avatar user-1-avatar avatar-50 photo" width="50" height="50" alt="Profile picture of Admin" /></div>
									<div class="ac-reply-content">
										<div class="ac-textarea">
											<textarea id="ac-input-168" class="ac-input bp-suggestions" name="ac_input_168"></textarea>
										</div>
										<input type="submit" name="ac_form_submit" value="Post" /> &nbsp; <a href="#" class="ac-reply-cancel">Cancel</a>
										<input type="hidden" name="comment_form_id" value="168" />
									</div>
													<input type="hidden" id="rt_upload_hf_comment"
								       value="1"
								       name="comment"/>
												<input type="hidden" id="rt_upload_hf_privacy"
								       value="0"
								       name="privacy"/>
												<input type="hidden" class="rt_upload_hf_upload_parent_id"
								       value="168"
								       name="upload_parent_id"/>
												<input type="hidden" class="rt_upload_hf_upload_parent_id_type"
								       value="activity"
								       name="upload_parent_id_type"/>
												<input type="hidden" id="rt_upload_hf_upload_parent_id_context"
								       value="groups"
								       name="upload_parent_id_context"/>
									<div class="rtmedia-container rtmedia-uploader-div">
									<div class="rtmedia-uploader no-js">
								<div id="rtmedia-uploader-form-activity-168">				
									<div class="rtm-tab-content-wrapper">
										<div id="rtm-file_upload-ui-activity-168" class="rtm-tab-content">
											<div class="rtmedia-plupload-container rtmedia-comment-media-main rtmedia-container clearfix"><div id="rtmedia-comment-action-update-activity-168" class="clearfix"><div class="rtm-upload-button-wrapper"><div id="rtmedia-comment-media-upload-container-activity-168"></div><button type="button" class="rtmedia-comment-media-upload" data-media_context="groups" id="rtmedia-comment-media-upload-activity-168" title="Attach Media"><span class="dashicons dashicons-admin-media"></span></button></div><input type="hidden" name="privacy" value="0" /></div></div><div class="rtmedia-plupload-notice"><ul class="plupload_filelist_content ui-sortable rtm-plupload-list clearfix" id="rtmedia_uploader_filelist-activity-168"></ul></div><input type="hidden" name="mode" value="file_upload" />							</div>
										</div>				
										<input type="hidden" id="rtmedia_upload_nonce" name="rtmedia_upload_nonce" value="dc7191db20" /><input type="hidden" name="_wp_http_referer" value="/causes/ottawa-food-bank/" />
																<input type="submit" id="rtMedia-start-upload-activity-168" name="rtmedia-upload" value="Upload" />
									</div>
								</div>
								</div>
									<input type="hidden" id="_wpnonce_new_activity_comment" name="_wpnonce_new_activity_comment" value="7e4cd8101a" /><input type="hidden" name="_wp_http_referer" value="/causes/ottawa-food-bank/" />
								</form>
						</div>
				</li>';
		}

	endforeach;
	endif;

	$count_tw = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM {$table_activity} WHERE component='%s' AND item_id='%d' AND type='%s' ",
	'groups',$gr_id,'new_event') );
	// var_dump($count_tw);
	// echo $count_tw;
	// $i=1;
	while($count_tw > 15){
		$old_tw = $wpdb->get_var($wpdb->prepare("SELECT MIN(date_recorded)  FROM {$table_activity} WHERE component='%s' AND item_id='%d' AND type='%s' ",
		//$old_tw = $wpdb->get_var($wpdb->prepare("SELECT id MIN(date_recorded)  FROM {$table_activity} WHERE component='%s' AND item_id='%d' AND type='%s' ",
		'groups',$gr_id,'new_event') );
		// var_dump($old_tw);
		$wpdb->delete( $table_activity, array( 'item_id'=>$gr_id,'date_recorded' => $old_tw,'component'=> 'groups', 'type'=>'new_event'), array( '%d','%s','%s','%s' ) );
		// echo $i++;
	}

	// echo "\r\n <b>last query:</b> ".$wpdb->last_query."<br>";
	// echo "<b>last result:</b> "; print_r($wpdb->last_result);
	// echo "<br><b>last error:</b> ";	echo "\r\n"; print_r($wpdb->last_error);


	// echo $output['date'];
	// var_dump($output);
	echo $html;
	// echo "test";
	// echo json_encode($html);
	exit;
}

add_action("wp_footer","alex_tweet");
function alex_tweet(){

		$gr_id = bp_get_group_id();
		// echo "gr ".$gid;
		$group = groups_get_group($gr_id);
		$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
		$avatar_options = array ( 'item_id' => $gr_id, 'object' => 'group','avatar_dir' => 'group-avatars', 'html' => false );
		$gr_avatar = bp_core_fetch_avatar($avatar_options);

		// print_r($bp);
        $tw_url = groups_get_groupmeta( $gr_id, 'al21_twitteer_url' );
		$tw_user = substr(strrchr($tw_url,"/"), 1); // parse url and return last part,e.g. ottawafoodbank
		// echo "<hr>".$tw_url."<br>".$tw_user;

	// echo $getfield;
	// print_r($settings);


	if(bp_is_group_home()) {
		// echo "<h3>this is page-test888</h3>";

		// add_action("wp_ajax_get_tweets", "alex_my_action_callback");
		// add_action("wp_ajax_nopriv_get_tweets", 'alex_my_action_callback');
		// function alex_my_action_callback(){
		// 	echo $res['res'] = "php ajax";
		// 	json_encode($res);
		// 	exit;
		// }
		 $setting_2 = groups_get_groupmeta( $gr_id, 'al21_automate_enable' );
		if( $setting_2 =="yes"):
		?>
		<script>
		jQuery( document ).ready(function() {
		    function get_tweets(){
			    console.log(KLEO.ajaxurl);
				var data = {
					'action': 'get_tweets',
					'gr_id': <?php echo $gr_id;?>,
					'gr_permalink': '<?php echo $group_permalink;?>',
					'gr_name': '<?php echo $group->name;?>',
					'gr_avatar': '<?php echo $gr_avatar;?>',
					'user': '<?php echo $tw_user;?>',
				};

				jQuery.ajax({
					url:KLEO.ajaxurl, // обработчик
					data:data, // данные
					type:'POST', // тип запроса
					success:function(data){
						console.log("js ok!");
						console.log(data);
						if( data ) { 
							// current_page++; // увеличиваем номер страницы на единицу
							// if (current_page == max_pages) $("#true_loadmore").remove(); // если последняя страница, удаляем кнопку
							jQuery(".activity.single-group>ul").prepend(data);
						} else {
							// $('#true_loadmore').remove(); // если мы дошли до последней страницы постов, скроем кнопку
						}
					},
					beforeSend: function(){
						// $("#loading-text").html('<a class="loading-link" href="#">Loading ...</a>');
						console.log("Loading get_tweets");
					}
				 });
			}
			get_tweets();
		});
		</script>
	<?php
		endif;
	}
}

/*********** this code for work with twitter ***********/


/* *********** Group Extension API (creation new section for group settings) ************ */

/**
 * The bp_is_active( 'groups' ) check is recommended, to prevent problems 
 * during upgrade or when the Groups component is disabled
 */
if ( bp_is_active( 'groups' ) ) :
  
class Group_Extension_Example_2 extends BP_Group_Extension {
    /**
     * Here you can see more customization of the config options
     */
    function __construct() {
        $args = array(
            'slug' => 'group-automation',
            'name' => 'Group Automation',
            'nav_item_position' => 105,
            'show_tab' => 'noone',
            'screens' => array(
                'edit' => array(
                    'name' => 'Group Automation',
                    // Changes the text of the Submit button
                    // on the Edit page
                    'submit_text' => 'Save',
                ),
                'create' => array(
                    'position' => 100,
                ),
            ),
        );
        parent::init( $args );
    }
 
    function display( $group_id = NULL ) {
        $group_id = bp_get_group_id();
        echo 'This plugin is 2x cooler!';
    }
 
    function settings_screen( $group_id = NULL ) {
        $setting = groups_get_groupmeta( $group_id, 'al21_twitteer_url' );
        $setting_2 = groups_get_groupmeta( $group_id, 'al21_automate_enable' );
        ?>
        Twitter url: 
        <input type="url" name="al21_twitteer_url" value="<?php echo esc_attr( $setting ) ?>" />
		<label for="al21_automate_enable">
		<input type="checkbox" id="al21_automate_enable" name="al21_automate_enable" <?php if($setting_2 == 'yes') echo 'checked="checked"';?> value="<?php echo $setting_2;?>"> Enable automate
		</label>
		<script type="text/javascript">
		jQuery(document).ready(function(){

			var sel = jQuery('#al21_automate_enable');
			sel.on('click', function(){     
				// if(sel.attr("checked") == 'checked') {  
				if(sel.val() == "yes") {  
					sel.val("no");
				} else {
				    sel.val("yes");
				}
			console.log(sel.val());
			});

		});
		</script>
        <?php
    }
 
    function settings_screen_save( $group_id = NULL ) {
        $setting = isset( $_POST['al21_twitteer_url'] ) ? $_POST['al21_twitteer_url'] : '';
        groups_update_groupmeta( $group_id, 'al21_twitteer_url', $setting );
        $setting_2 = isset( $_POST['al21_automate_enable'] ) ? $_POST['al21_automate_enable'] : '';
        groups_update_groupmeta( $group_id, 'al21_automate_enable', $setting_2 );

			global $wpdb,$bp;
			// print_r($bp);
			$table_activity = $wpdb->prefix."bp_activity";
 			$gr_id = $bp->groups->current_group->id;
			if($setting_2 == 'yes') $hide = 0;
			else $hide = 1;
			$wpdb->update( $table_activity,
				array( 'hide_sitewide'=> $hide),
				array( 'item_id' => $gr_id, 'component'=>'groups','type'=>'new_event' ),
				array( '%d' ),
				array( '%d', '%s', '%s' )
			);

 		// 	echo "<br>end twitter";
			//  echo '<hr><br>';
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
			// exit;

    }
 
    /**
     * create_screen() is an optional method that, when present, will
     * be used instead of settings_screen() in the context of group
     * creation.
     *
     * Similar overrides exist via the following methods:
     *   * create_screen_save()
     *   * edit_screen()
     *   * edit_screen_save()
     *   * admin_screen()
     *   * admin_screen_save()
     */
    function create_screen( $group_id = NULL ) {
        $setting = groups_get_groupmeta( $group_id, 'al21_twitteer_url' );
 
        ?>
        Twitter url: 
        <input type="url" name="al21_twitteer_url" value="<?php echo esc_attr( $setting ) ?>" />
		<label for="al21_automate_enable">
		<input type="checkbox" id="al21_automate_enable" name="al21_automate_enable" value="yes"> Enable automate
		</label>
        <?php
    }
 
}
bp_register_group_extension( 'Group_Extension_Example_2' );
 
endif;

/* *********** Group Extension API ************ */

function al_add_tweets_in_db(){

	// alex_debug(0,1,'req1',$_REQUEST);
	// alex_debug(0,1,'post1',$_POST);
	if( !empty($_REQUEST['al21_twitteer_url']) ) echo $twitter_url = sanitize_text_field($_REQUEST['al21_twitteer_url']);
	if (!empty($twitter_url) ){
		require_once 'tw-api.php';
		$twitter_debug = false;
		// $twitter_username = 'ottawafoodbank';
		$twitter_username = substr(strrchr($twitter_url,"/"), 1); // parse url and return last part,e.g. ottawafoodbank
		// $tweets = a21_tw_get_tweets($twitter_username, $settings,$url,$getfield,$requestMethod,$twitter_debug);
		$tweets = a21_tw_get_tweets($twitter_username,$settings,$url,$requestMethod,$twitter_debug,15);

		global $wpdb;
		$table_activity = $wpdb->prefix."bp_activity";
		$user = wp_get_current_user();
		$from_user_id = $user->ID;

		// echo $gr_id = bp_get_group_id();
		// echo "gr ".$gid;
		$gr_id = (int)$_REQUEST['group_id'];
		$group = groups_get_group($gr_id);
		$group_permalink =  'http://'.$_SERVER['HTTP_HOST'] . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/';
		$avatar_options = array ( 'item_id' => $gr_id, 'object' => 'group','avatar_dir' => 'group-avatars', 'html' => false );
		$gr_avatar = bp_core_fetch_avatar($avatar_options);
		$action = '<a href="http://dugoodr.dev/members/toddroberts/" title="Todd2_LongName">Todd2_LongName</a> posted tweet <a href="http://dugoodr.dev/causes/ottawa-food-bank/">'.$group->name.'</a>';

		if(!$twitter_debug):
			foreach ($tweets as $k => $v):
				$output['date_format'] = ago($v->created_at,1,1);
				$output['tweet'] = $v->text; // in tweets can be as sign ' "
				$output['date'] = $v->created_at;
				// Wed Mar 08 16:05:46 +0000 2017
				 $date_recorded = date("Y-m-d H:i:s", strtotime($output['date']));
				if(!empty($v->entities->urls[0]->url)) $output['short_link'] = $v->entities->urls[0]->url;
				$q = $wpdb->prepare( "INSERT INTO {$table_activity} (user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide, is_spam ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %d, %d, %d, %d )", $from_user_id, 'groups', 'new_event', $action, $output['tweet'], $to_user_link_nohtml, $date_recorded, $gr_id, 0, 0,0);
				$wpdb->query( $q );	
			endforeach;
		endif;
		// print_r($output);
	}
	// echo "<br>end twitter";
	//  echo '<hr><br>';
	// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
	// echo "<b>last result:</b> "; print_r($wpdb->last_result);
	// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
 //   echo "<pre>";
 //   print_r($wpdb->queries);
 //   echo "</pre>";
	// exit;
}

// Fires after the group has been successfully created (variation 1) after click button Finish
add_action( 'groups_group_create_complete','al_add_tweets_in_db');

// add_action("wp_footer","a21_test1");
// function a21_test1(){
// 	echo '<hr>';
// 	echo $str = 'https://twitter.com/ottawafoodbank';
// 	// echo $str = 'https://twitter.com/ottawafood.bank';
// 	echo "<br>";
// 	echo $str = substr(strrchr($str,"/"), 1); // parse url and return last part,e.g. ottawafoodbank
// 	echo "<br>";

// }