<?php

$server_time = new DateTime('NOW', new DateTimeZone('UTC'));

// using a token
if (isset($_POST['ACCESS_TOKEN'])) {

	// includes
	require_once('./_api_consts.php');
	require_once('./_db_creds.php');

	// make the connection
	$db_conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database");

	// select the proper db
	mysql_select_db(DB_NAME) or die("Could not select database");
	mysql_set_charset('utf8');


	// perform on api
	if ($_POST['action'] == FETCH_MARKETPLACE) {
		$query = 'SELECT `id`, `owner_id`, `name`, `display_name`, `description`, `logo_url`, `prebot_url`, `views`, `added` FROM `storefronts` WHERE `enabled` = 1;';
		$result = mysql_query($query);

		// has results
		$cards_arr = array();
		if (mysql_num_rows($result) > 0) {
			while ($storefront_obj = mysql_fetch_object($result)) {

				// lookup product(s) per storefront
				$storefronts_arr = array();
				$query = 'SELECT `id`, `name`, `display_name`, `description`, `image_url`, `video_url`, `price`, `prebot_url`, `release_date`, `added` FROM `products` WHERE `storefront_id` = ' . $storefront_obj->id . ' AND `enabled` = 1 LIMIT 1;';
				$product_result = mysql_query($query);

				if (mysql_num_rows($product_result) > 0) {
					$product_obj = mysql_fetch_object($product_result);

					$query = 'SELECT COUNT(*) AS `tot` FROM `subscriptions` WHERE `product_id` = '. $product_obj->id .';';
					$subscribers_result = mysql_query($query);

					$query = 'SELECT COUNT(*) AS `tot` FROM `purchases` WHERE `product_id` = '. $product_obj->id .';';
					$preorders_result = mysql_query($query);

					array_push($cards_arr, array(
						'type'            => CARD_TYPE_PRODUCT,
						'id'              => $product_obj->id,
					  'name'            => $product_obj->name,
					  'storefront_name' => $storefront_obj->display_name,
					  'product_name'    => $product_obj->display_name,
					  'description'     => $product_obj->description,
					  'image_url'       => preg_replace('/\.(\w{3})$/', "-256.$1", $product_obj->image_url),
					  'video_url'       => $product_obj->video_url,
					  'prebot_url'      => $product_obj->prebot_url,
					  'price'           => $product_obj->price,
					  'views'           => $storefront_obj->views,
					  'subscribers'     => (mysql_num_rows($subscribers_result) > 0) ? mysql_fetch_object($subscribers_result)->tot : 0,
					  'preorders'       => (mysql_num_rows($preorders_result) > 0) ? mysql_fetch_object($preorders_result)->tot : 0,
					  'release_date'    => $product_obj->release_date,
					  'added'           => $product_obj->added
					));

				} else {

					$query = 'SELECT COUNT(*) AS `tot` FROM `subscriptions` WHERE `storefront_id` = '. $storefront_obj->id .';';
					$subscribers_result = mysql_query($query);

					$query = 'SELECT COUNT(*) AS `tot` FROM `purchases` WHERE `product_id` IN (SELECT `id` FROM `products` WHERE `storefront_id` = '. $storefront_obj->id .');';
					$preorders_result = mysql_query($query);

					array_push($cards_arr, array(
						'query'           => $query,
						'type'            => CARD_TYPE_STOREFRONT,
						'id'              => $storefront_obj->id,
					  'owner_id'        => $storefront_obj->owner_id,
					  'storefront_name' => $storefront_obj->display_name,
					  'product_name'    => $product_obj->display_name,
					  'description'     => $storefront_obj->description,
					  'image_url'       => preg_replace('/\.(\w{3})$/', "-256.$1", $storefront_obj->logo_url),
					  'prebot_url'      => $storefront_obj->prebot_url,
					  'price'           => $product_obj->price,
					  'views'           => $storefront_obj->views,
					  'subscribers'     => (mysql_num_rows($subscribers_result) > 0) ? mysql_fetch_object($subscribers_result)->tot : 0,
					  'preorders'       => (mysql_num_rows($preorders_result) > 0) ? mysql_fetch_object($preorders_result)->tot : 0,
					  'added'           => $storefront_obj->added
					));
				}
			}

			mysql_free_result($result);
		}

		echo(json_encode($cards_arr));


	} elseif ($_POST['action'] == FETCH_STOREFRONT) {

		$storefront_obj = array();
		$query = 'SELECT `id`, `owner_id`, `name`, `display_name`, `description`, `logo_url`, `prebot_url`, `views`, `added` FROM `storefronts` WHERE `id` = '. $_POST['id'] .' LIMIT 1;';
		$result = mysql_query($query);

		if (mysql_num_rows($result) == 1) {
			$obj = mysql_fetch_object($result);

			$storefront_obj = array(
				'id'           => $obj->id,
			  'owner_id'     => $obj->owner_id,
			  'name'         => $obj->name,
			  'display_name' => $obj->display_name,
			  'description'  => $obj->description,
			  'products'     => array()
			);

			$query = 'SELECT `id`, `name`, `display_name`, `description`, `image_url`, `video_url`, `price`, `prebot_url`, `release_date`, `added` FROM `products` WHERE `storefront_id` = '. $obj->id .' AND `enabled` = 1 LIMIT 1;';
			$result = mysql_query($query);

			if (mysql_num_rows($result) > 0) {
				$obj = mysql_fetch_object($result);

				array_push($storefront_obj['products'], array(
					'id' => $obj->id,
				  'name' => $obj->name,
				  'display_name' => $obj->display_name,
				  'description' => $obj->description,
				  'image_url' => $obj->image_url,
				  'video_url' => $obj->video_url,
				  'price' => $obj->price,
				  'prebot_url' => $obj->prebot_url,
				  'release_date' => $obj->release_date,
					'added' => $obj->added
				));
			}
		}

		echo(json_encode($storefront_obj));

	} else if ($_POST['action'] == FETCH_STOREFRONT_SHARE) {
		$storefront_obj = array();
		$query = 'SELECT `id`, `owner_id`, `name`, `display_name`, `description`, `logo_url`, `prebot_url`, `views`, `added` FROM `storefronts` WHERE `name` = "'. $_POST['storefront_name'] .'" LIMIT 1;';
		$result = mysql_query($query);

		if (mysql_num_rows($result) == 1) {
			$obj = mysql_fetch_object($result);

			$query = 'SELECT `display_name`, `prebot_url` FROM `products` WHERE `storefront_id` = '. $obj->id .' AND `enabled` = 1 LIMIT 1;';
			$result = mysql_query($query);

			$p_obj = null;
			if (mysql_num_rows($result) == 1) {
				$p_obj = mysql_fetch_object($result);
			}

			$storefront_obj = array(
				'id'           => $obj->id,
				'owner_id'     => $obj->owner_id,
				'name'         => $obj->name,
				'display_name' => (mysql_num_rows($result) == 1) ? $p_obj->display_name : $obj->display_name,
				'description'  => $obj->description,
				'logo_url'     => $obj->logo_url,
				'prebot_url'   => (mysql_num_rows($result) == 1) ? $p_obj->prebot_url : $obj->prebot_url
			);
		}

		echo(json_encode($storefront_obj));
	}
}

?>