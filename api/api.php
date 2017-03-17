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
		$query = 'SELECT `id`, `storefront_id`, `name`, `display_name`, `description`, `image_url`, `video_url`, `price`, `prebot_url`, `release_date`, `added` FROM `products` WHERE `storefront_id` IN (SELECT `id` FROM `storefronts` WHERE `enabled` = 1) AND `enabled` = 1 ORDER BY `added` DESC LIMIT ' . $_POST['offset'] . ', ' . $_POST['limit'] . ';';
		$result = mysql_query($query);

		// has results
		$products_arr = array();
		if (mysql_num_rows($result) > 0) {
			while ($product_obj = mysql_fetch_object($result)) {

				$query = 'SELECT `display_name`, `views` FROM `storefronts` WHERE `id` = ' . $product_obj->storefront_id . ' LIMIT 1;';
				$storefront_result = mysql_query($query);

				$storefront_obj = array();
				if (mysql_num_rows($storefront_result) == 1) {
					$storefront_obj = mysql_fetch_object($storefront_result);

					$query = 'SELECT COUNT(*) AS `tot` FROM `subscriptions` WHERE `product_id` = ' . $product_obj->id . ';';
					$subscribers_result = mysql_query($query);

					$query = 'SELECT COUNT(*) AS `tot` FROM `purchases` WHERE `product_id` = ' . $product_obj->id . ';';
					$purchases_result = mysql_query($query);
				}

				array_push($products_arr, array(
					'type'            => CARD_TYPE_PRODUCT,
					'id'              => $product_obj->id,
					'name'            => $product_obj->name,
					'storefront_name' => $storefront_obj->display_name,
					'product_name'    => $product_obj->display_name,
					'description'     => $product_obj->description,
					'image_url'       => preg_replace('/\.(\w{3})$/', "-400.$1", $product_obj->image_url),
					'video_url'       => $product_obj->video_url,
					'prebot_url'      => $product_obj->prebot_url,
					'price'           => $product_obj->price,
					'views'           => $storefront_obj->views,
					'subscribers'     => (mysql_num_rows($subscribers_result) > 0) ? mysql_fetch_object($subscribers_result)->tot : 0,
					'purchases'       => (mysql_num_rows($purchases_result) > 0) ? mysql_fetch_object($purchases_result)->tot : 0,
					'release_date'    => $product_obj->release_date,
					'added'           => $product_obj->added
				));
			}
		}

		mysql_free_result($result);
		echo(json_encode($products_arr));


	} elseif ($_POST['action'] == FETCH_FEATURE_STOREFRONTS) {
		$query = 'SELECT `id`, `storefront_id`, `type`, `name`, `display_name`, `description`, `image_url`, `video_url`, `price`, `prebot_url`, `release_date`, `added` FROM `products` WHERE `storefront_id` IN (SELECT `id` FROM `storefronts` WHERE `enabled` = 1 AND (`type` = 1 OR `type` = 2 OR `type` = 5 OR `type` = 6)) AND `enabled` = 1 AND (`type` = 1 OR `type` = 2 OR `type` = 5 OR `type` = 6) ORDER BY `added` DESC LIMIT 100;';
		$result = mysql_query($query);

		$products_arr = array();
		while ($product_obj = mysql_fetch_object($result)) {
			$query = 'SELECT `display_name` FROM `storefronts` WHERE `id` = ' . $product_obj->storefront_id . ' LIMIT 1;';
			$storefront_result = mysql_query($query);
			$storefront_name = (mysql_num_rows($storefront_result) == 1) ? mysql_fetch_object($storefront_result)->display_name : "";

			array_push($products_arr, array(
				'id'              => $product_obj->id,
				'type'            => $product_obj->type,
				'name'            => $product_obj->name,
				'storefront_name' => $storefront_name,
				'product_name'    => $product_obj->display_name,
				'description'     => $product_obj->description,
				'image_url'       => preg_replace('/\.(\w{3})$/', "-400.$1", $product_obj->image_url),
				'video_url'       => $product_obj->video_url,
				'prebot_url'      => $product_obj->prebot_url,
				'price'           => $product_obj->price,
				'release_date'    => $product_obj->release_date,
				'added'           => $product_obj->added
			));
		}

		mysql_free_result($result);
		echo(json_encode($products_arr));

	} elseif ($_POST['action'] == UPDATE_PRODUCT_TYPE) {
		$query = 'SELECT `storefront_id` FROM `products` WHERE `id` = '. $_POST['product_id'] .' LIMIT 1;';
		$result = mysql_query($query);

		if (mysql_num_rows($result) == 1) {
			$query = 'UPDATE `storefronts` SET `type` = '. $_POST['type'] .' WHERE `id` = '. mysql_fetch_object($result)->storefront_id .' LIMIT 1;';
			$upd_result = mysql_query($query);

			$query = 'UPDATE `products` SET `type` = '. $_POST['type'] .' WHERE `id` = '. $_POST['product_id'] .' LIMIT 1;';
			$upd_result = mysql_query($query);
		}

		echo (json_encode(array(
			'result' => true
		)));

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

	} elseif ($_POST['action'] == FETCH_STOREFRONT_SHARE) {
		$storefront_obj = array();
		$query = 'SELECT `id`, `owner_id`, `name`, `display_name`, `description`, `logo_url`, `prebot_url`, `views`, `added` FROM `storefronts` WHERE `id` = '. $_POST['storefront_id'] .' LIMIT 1;';
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

	} elseif ($_POST['action'] == FETCH_PRODUCT) {
		$query = 'SELECT `id`, `name`, `display_name`, `description`, `image_url`, `video_url`, `price`, `prebot_url`, `release_date`, `added` FROM `products` WHERE `id` = '. $_POST['product_id'] .' LIMIT 1;';
		$result = mysql_query($query);

		$obj = array();
		if (mysql_num_rows($result) > 0) {
			$obj = mysql_fetch_object($result);
		}

		echo(json_encode($obj));

	} elseif ($_POST['action'] == VALIDATE_PRODUCT) {
		$query = 'SELECT `id` FROM `storefronts` WHERE `prebot_url` = "'. preg_replace('/^.*\=(.+)$/', "http://prebot.me/$1", $_POST['prebot_url']) .'" LIMIT 1;';
		$result = mysql_query($query);

		echo(json_encode(array(
			'prebot_url' => preg_replace('/^.*\=(.+)$/', "http://prebot.me/$1", $_POST['prebot_url']),
			'result' => (mysql_num_rows($result) > 0)
		)));
	}
}

?>