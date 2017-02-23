<?php

/* ****** добавление полля city в профлиь группы,если его не существует в базе ************** */
add_action('wp_footer','alex_test_1');
function alex_test_1(){
	if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/")
	// if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/admin/edit-details/")
	{
		echo "<h3>=========Testing========</h3>";
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical','per_page'=>999));
		// print_r($groups);
		echo "Всего групп: ".$groups['total']."<hr>";
		// отбирает только id и name группы
		global $wpdb;
		foreach ($groups['groups'] as $gr) {

			echo $gr->id. " - ".$gr->name;

		    $table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
			$city = $wpdb->get_results( $wpdb->prepare(
				"SELECT meta_value
				FROM {$table_grmeta}
				WHERE group_id = %d
				    AND meta_key = %s",
				intval( $gr->id ),
				"city_state"
			) );

			// print_r($city);
			echo $city = $city[0]->meta_value;
			if( !empty($city)) { echo " ---- создан! "; echo $city;}
			else{
				$wpdb->insert(
					$wpdb->prefix."bp_groups_groupmeta",
					array( 'group_id' => $gr->id, 'meta_key' => 'city_state', 'meta_value'=> " "),
					array( '%d','%s','%s' )
				);
			}
			echo "<br>";
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
		}

		$table_grmeta = $wpdb->prefix."bp_groups_groupmeta";
		$all_city = $wpdb->get_results( $wpdb->prepare(
			"SELECT meta_value,group_id
			FROM {$table_grmeta}
			WHERE meta_key = %s
			","city_state"
		) );

		echo "<hr>Группы у которых есть города<br>";
		// print_r($all_city);
		$i = 1;
		foreach ($all_city as $item) {
			echo $i."___".$item->group_id." - ".$item->meta_value."<br>"; $i++;
		}
	}
}
/* ****** добавление полля city в профлиь группы,если его не существует в базе ************** */




/* ****** изменение post_type c alex_gfields на alex_grsoclink ************** */
add_action('wp_footer','alex_test_1');
function alex_test_1(){
	// if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/")
	if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/admin/edit-details/")
	{
		echo "<h3>=========Testing========</h3>";
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical','per_page'=>999));
		// print_r($groups);
		echo "Всего групп: ".$groups['total']."<hr>";
		// отбирает только id и name группы
		global $wpdb;
		foreach ($groups['groups'] as $gr) {
			echo $gr->id. " - ".$gr->name."<br>";
		}

		echo "<hr>Группы c post_type=alex_gfilds: <hr>";
		$k = 1;
		foreach ($groups['groups'] as $gr) {
			$gr_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s",
				intval( $gr->id ),
				"alex_gfilds"
			) );
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);

			 // print_r($gr_soclinks);
			$i = 1;
			foreach ($gr_soclinks as $item) {
				echo "гр ".$k;
				echo " поле ".$i."___".$item->post_parent." == ".$item->post_title." == ".$item->post_content."<br>"; $i++;

				$wpdb->update( $wpdb->posts,
					array( 'post_type' => "alex_grsoclink", ),
					array( 'post_parent' => $gr->id, 'post_type' => 'alex_gfilds' ),
					array( '%s' ),
					array( '%d','%s' )
				);
			}
			$k++;
		}

		echo "<hr>Группы c post_type=alex_grsoclink: <hr>";
		$k = 1;
		foreach ($groups['groups'] as $gr) {

			$gr_new_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s",
				intval( $gr->id ),
				"alex_grsoclink"
			) );
			$i = 1;
			foreach ($gr_new_soclinks as $item) {
				echo "гр ".$k;
				echo " поле ".$i."___".$item->post_parent." == ".$item->post_title." == ".$item->post_content."<br>"; $i++;
			}
			$k++;
		}

	   // echo "<pre>";
	   // print_r($wpdb->queries);
	   // echo "</pre>";

    }
}
/* ****** изменение post_type c alex_gfields на alex_grsoclink ************** */

/* ****** добавление поля website если он не был создан для группы ************** */
add_action('wp_footer','alex_test_1');
function alex_test_1(){
	if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/")
	// if( $_SERVER['REQUEST_URI'] == "/causes/something-amazing/admin/edit-details/")
	{
		echo "<h3>=========Testing========</h3>";
		$groups = BP_Groups_Group::get(array('type'=>'alphabetical','per_page'=>999));
		// print_r($groups);
		echo "Всего групп: ".$groups['total']."<hr>";
		// отбирает только id и name группы
		global $wpdb;
		foreach ($groups['groups'] as $gr) {
			$new_gr[$gr->id] = $gr->name;
		}
		ksort($new_gr);
		$num =1;
		foreach ($new_gr as $k=>$v) {
			echo $num.".__".$k. " - ".$v."<br>"; $num++;
		}

		echo "<hr>Группы c post_type=alex_grsoclink: <hr>";
		$k = 1;
		foreach ($new_gr as $key=>$v) {

			$gr_new_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s
				    AND post_title = %s",
				intval( $key),
				"alex_grsoclink",
				"Website"
			) );
			$i = 1;
			foreach ($gr_new_soclinks as $item) {
				echo "гр ".$k;
				echo " поле ".$i."___".$item->post_parent." == ".$item->post_title." == ".$item->post_content."<br>"; $i++;
			}
			$k++;
		}

		echo "<hr>Группы c post_type=alex_grsoclink,проверка на сущ: <hr>";
		$k = 1;
		foreach ($new_gr as $key=>$v) {

			$gr_new_soclinks = $wpdb->get_results( $wpdb->prepare(
				"SELECT * FROM {$wpdb->posts}
				WHERE post_parent = %d
				    AND post_type = %s
				    AND post_title = %s",
				intval( $key ),
				"alex_grsoclink",
				"Website"
			) );
			$i = 1;

			// print_r($gr_new_soclinks);
			$gr_new_soclinks = $gr_new_soclinks[0]->post_content;
			if( !empty($gr_new_soclinks)) { echo $k." ---- создан! "; echo $gr_new_soclinks."<br>";}
			else{
				$wpdb->insert(
					$wpdb->posts,
					array( 'post_parent' => $key, 'post_type' => 'alex_grsoclink', 'post_title'=> "Website", 'post_content'=>' '),
					array( '%d','%s','%s','%s' )
				);
			}
			// echo "<b>last query:</b> ".$wpdb->last_query."<br>";
			// echo "<b>last result:</b> "; print_r($wpdb->last_result);
			// echo "<br><b>last error:</b> "; print_r($wpdb->last_error);
			$k++;
		}

	   // echo "<pre>";
	   // print_r($wpdb->queries);
	   // echo "</pre>";

    }
}
/* ****** добавление поля website если он не был создан для группы ************** */


INSERT INTO `wp8k_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES (NULL, '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'http://instagram.com0', 'Instagram', '', 'publish', 'open', 'open', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '110', '', '0', 'alex_gfilds', '', '0')


add_action('wp_footer',"group_pages_scroll_to_anchor");
function group_pages_scroll_to_anchor(){
	// echo '===alex-gr===';
	echo bp_get_groups_slug();
	var_dump( bp_current_component() );
	var_dump( bp_is_groups_component() );
	// if(bp_is_group_home()) {
	// if page related group
	if( bp_is_groups_component()) {
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {
	    	var scroll = (jQuery('#item-nav').offset().top)-110;
	    	jQuery(document.body).scrollTop(scroll);
	    	// console.log(scroll);
	    });
		</script>
		<?
	}
}

add_action('wp_footer',"highlight_group_interest_links_on_profile_member");
function highlight_group_interest_links_on_profile_member(){
	echo '===alex-gr===';
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function(e) {
	    	e.preventDefault();
	    	var link = jQuery(".profile .bp-widget a");
	    	console.log(link.text());
	    });
		</script>
}


<!-- ------- -->

add_action('wp_footer',"highlight_group_interest_links_on_profile_member");
function highlight_group_interest_links_on_profile_member(){
	// echo '===alex-gr===';
		?>
		<script type="text/javascript">
	    jQuery(document).ready(function() {

	    	// console.log(document.cookie);
	    	var link = jQuery(".profile .bp-widget a");
	    	// var link = jQuery("a");

			// возвращает cookie с именем name, если есть, если нет, то undefined
			function getCookie(name) {
			  var matches = document.cookie.match(new RegExp(
			    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			  ));
			  return matches ? decodeURIComponent(matches[1]) : undefined;
			}

			link.each(function( i,item) {
				var cur_link = jQuery(this).text();
				if( getCookie( cur_link ) == 1) { jQuery(this).css({"color":"#ca0532"}); }
				// console.log(i+"- cur link= "+cur_link);
				// console.log("cookie = "+getCookie( "and" ) );
				// console.log("cookie = "+getCookie( jQuery(this).text() ) );
				// console.log("item = "+item );
			});

	    	jQuery(link).click(function(e){
	    		// e.preventDefault();
	    		var cur_link = jQuery(this);
	    		// console.log(cur_link.text());
				// var date = new Date(new Date().getTime() + 60 * 10000);
				// document.cookie = cur_link.text()+"=1; path=/; expires=" + date.toUTCString();

				// time no set,to delete cookie if close browser
				document.cookie = cur_link.text()+"=1; path=/;";
				// console.log( getCookie(cur_link.text()) );
				// link.each(function( index ) {
				// 	if( getCookie(cur_link.text() ) == 1) { cur_link.css({"color":"blue"}); }
				// });
				// if( getCookie(cur_link.text() ) == 1) { cur_link.css({"color":"#ca0532"}); }
	    	});

	    	window.onbeforeunload = function(){jQuery.cookie('enter', null);}

	    });
		</script>
		<?php
}


############

/* ********** Load modules ******** */

$kleo_modules = array(
    'new_facebook-login.php'
);

$kleo_modules = apply_filters( 'kleo_modules', $kleo_modules );
// var_dump($kleo_modules);
// var_dump(KLEO_LIB_DIR);
// var_dump(THEME_DIR);
// echo trailingslashit(get_stylesheet_directory_uri());
// /* Sets the path to the theme library folder. */
// define( 'KLEO_LIB_DIR', trailingslashit( THEME_DIR ) . 'lib' );
// get absolute path /home/jetfire/www/dugoodr.dev/wp-content/themes/buddyapp
 $theme_url = get_template_directory()."-child/";
 // /home/jetfire/www/dugoodr.dev/wp-content/themes/buddyapp/kleo-framework/lib/function-core.php
 include_once get_template_directory()."/kleo-framework/lib/function-core.php";
// exit;

foreach ( $kleo_modules as $module ) {
    $file_path = $theme_url. 'lib/modules/' . $module;
    include_once $file_path;
}

/* ********** Load modules ******** */


        function fb_intialize(FB_response, token){
            FB.api( '/me', 'GET', {
                    fields : 'id,email,verified,name,cover,age_range,link,locale,picture,gender,first_name',
                    // fields : 'id,email,verified,name',
                    access_token : token
                },
                function(FB_userdata){
                    console.log("====alex data=====");
                    console.log("====user data=====");
                    console.log(FB_userdata);
                    console.log(FB_response);

                    jQuery.ajax({
                        type: 'POST',
                        url: fbAjaxUrl,
                        data: {"action": "fb_intialize", "FB_userdata": FB_userdata, "FB_response": FB_response},
                        success: function(user){
                            
                            console.log("========user ");
                            console.log(user);

                            if( user.error ) {
                                alert( user.error );
                            }
                            // else if( user.loggedin ) {
                            //     jQuery('.kleo-login-result').html(user.message);
                            //     if( user.type === 'login' ) {
                            //         if(window.location.href.indexOf("wp-login.php") > -1) {
                            //             window.location = user.url;
                            //         } else if (user.redirectType == 'reload') {
                            //             window.location.reload();
                            //         } else {
                            //             window.location = user.url;
                            //         }
                            //     }
                            //     else if( user.type === 'register' ) {
                            //         window.location = user.url;
                            //     }
                            // }
                        }
                    });
                }
            );
        }


     #############


            // alex
    $FB_userdata = $_REQUEST['FB_userdata'];
    echo "<h1>777_test</h1>";
    print_r($FB_userdata);
    echo "============";
    $new_fb_data = array();
    echo $new_fb_data["cover"] = $FB_userdata['cover']['source'];
    echo $new_fb_data["name"] = $FB_userdata['name'];
    echo "\r\n============";
    $ser_fb_data = serialize($new_fb_data);
    var_dump($ser_fb_data);
    echo "\r\n============";
    $b = unserialize($ser_fb_data);
    print_r($b);
    echo "\r\n============";

    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix."usermeta",
        array( 'user_id' => 7777, 'meta_key'=>'_afbdata', 'meta_value'=>$ser_fb_data),
        array( '%d','%s','%s' )
    );

    exit;
    // alex