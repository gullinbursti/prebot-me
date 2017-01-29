<?php


define('DB_HOST', "138.197.216.56");
define('DB_NAME', "prebot_marketplace");
define('DB_USER', "pre_usr");
define('DB_PASS', "f4zeHUga.age");

// make the connection
$db_conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database");

// select the proper db
mysql_select_db(DB_NAME) or die("Could not select database");
mysql_set_charset('utf8');


// parse out the url path
$path_arr = explode("/", preg_replace('/^\//', "", $_GET['q']));

// referral into bot
$url_bit = 0x00;
$is_enabled = false;

// check if its a storefront or product
foreach ($path_arr as $val) {

	// check products
	$query = 'SELECT `id`, `storefront_id`, `enabled` FROM `products` WHERE `name` = "' . $val . '" LIMIT 1;';
	$result = mysql_query($query);

	if (mysql_num_rows($result) == 1) {
		$product_obj = mysql_fetch_object($result);

		$url_bit |= 0x01;
		$is_enabled = ($product_obj->enabled == 1);

		$query = 'SELECT `name` FROM `storefronts` WHERE `id` = "' . $product_obj->storefront_id . '" AND `enabled` = 1 LIMIT 1;';
		$result = mysql_query($query);

		if (mysql_num_rows($result) == 1) {
			$url_bit |= 0x10;

			if (count($path_arr) == 1) {
				array_unshift($path_arr, mysql_fetch_object($result)->name);
			}
		}

		break;
	}


	$query = 'SELECT `id` FROM `storefronts` WHERE `name` = "' . $val . '" AND `enabled` = 1 LIMIT 1;';
	$result = mysql_query($query);

	if (mysql_num_rows($result) == 1) {
		$storefront_obj = mysql_fetch_object($result);
		$url_bit |= 0x10;

		$query = 'SELECT `name` FROM `products` WHERE `storefront_id` = ' . $storefront_obj->id . ' AND `enabled` = 1 LIMIT 1;';
		$result = mysql_query($query);
		$is_enabled = (mysql_num_rows($result) == 1);

		if (mysql_num_rows($result) == 1) {
			$url_bit |= 0x01;

			if (count($path_arr) == 1) {
				array_push($path_arr, mysql_fetch_object($result)->name);
			}
		}
	}
}


// redirect into bot or go to marketplace
//echo ("url_bit:". $url_bit ."\n");
//echo ("is_enabled:". $is_enabled ."\n");
//$url = ((($url_bit & 0x01) == 0x01) && $is_enabled) ? "http://prebot.me/intent/". implode("/", $path_arr) : "http://prebot.me/shop". ((($url_bit & 0x10) == 0x10) ? "/". (($is_enabled) ? implode("/", $path_arr) : $path_arr[0]) : "s");
$url = ((($url_bit & 0x01) == 0x01) && $is_enabled) ? "http://prebot.me/intent/". implode("/", $path_arr) : "http://prebot.me/shops";
//echo ($url);
header("Location: ". $url);
exit();

?>