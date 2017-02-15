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
