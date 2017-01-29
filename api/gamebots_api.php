<?php header("access-control-allow-origin: *");

define('DB_HOST', "internal-db.s4086.gridserver.com");
define('DB_NAME', "db4086_modd");
define('DB_USER', "db4086_modd_usr");
define('DB_PASS', "f4zeHUga.age");

define('FETCH_CLAIM_ITEM', "FETCH_CLAIM_ITEM");
define('ADD_STEAM_USER', "ADD_STEAM_USER");

$server_time = new DateTime('NOW', new DateTimeZone('UTC'));

// make the connection
$db_conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database");

// select the proper db
mysql_select_db(DB_NAME) or die("Could not select database");
mysql_set_charset('utf8');


$pin_code = "0000";
if ($_POST['action'] == FETCH_CLAIM_ITEM) {

	$query = 'SELECT `id`, `name`, `game_name`, `description`, `image_url`, `trade_url`, `price` FROM `flip_inventory` WHERE `id` IN (SELECT `item_id` FROM `item_winners` WHERE `id` = ' . $_POST['claim_id'] . ') LIMIT 1;';
	$result = mysql_query($query);

	if (mysql_num_rows($result) == 1) {
		$item_obj = mysql_fetch_object($result);

		$query = 'SELECT `pin` FROM `item_winners` WHERE `id` = "'. $_POST['claim_id'] .'" LIMIT 1;';
		$result = mysql_query($query);

		$pin_code = "0000";
		if (mysql_num_rows($result) == 1) {
			$pin_code = mysql_fetch_object($result)->pin;
		}

		echo(json_encode(array(
			'id'          => $item_obj->id,
			'name'        => $item_obj->name,
			'game_name'   => $item_obj->game_name,
			'description' => htmlentities($item_obj->description),
			'image_url'   => $item_obj->image_url,
			'trade_url'   => $item_obj->trade_url,
			'price'       => $item_obj->price,
			'pin_code'    => $pin_code
		)));

	} else {
		echo(json_encode(array('id' => 0)));
	}

//	} else {
//		echo(json_encode(array('id' => 0)));
//	}

} elseif ($_POST['action'] == ADD_STEAM_USER) {
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=6636EF814B616DDEC4EF52D333646615&steamids=". $_POST['steam_id']);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$contents = json_decode(curl_exec($ch), true);
	$player_obj = $contents['response']['players'][0];
	curl_close($ch);

	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, "http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=6636EF814B616DDEC4EF52D333646615&steamid=". $_POST['steam_id'] ."&count=1&format=json");
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$contents = json_decode(curl_exec($ch), true);
	$game_obj = ($contents['response']['total_count']) > 0 ? $contents['response']['games'][0] : array(
		'appid'           => "",
		'name'            => "",
		'playtime_2weeks' => 0
	);
	curl_close($ch);


	$query = 'SELECT `id` FROM `steam_users` WHERE `steam_id` = "'. $_POST['steam_id'] .'" LIMIT 1;';
	$result = mysql_query($query);

	if (mysql_num_rows($result) == 0) {
		$query = 'INSERT IGNORE INTO `steam_users` (`id`, `social_name`, `steam_id`, `username`, `avatar`, `game_id`, `game_name`, `game_time`, `updated`, `added`) VALUES (NULL, "'. $_POST['social_name'] .'", "'. $_POST['steam_id'] .'", "'. $player_obj['personaname'] .'", "'. $player_obj['avatarfull'] .'", "'. $game_obj['appid'] .'", "'. $game_obj['name'] .'", '. $game_obj['playtime_2weeks'] .', NOW(), NOW());';
		$result = mysql_query($query);

		$query = 'UPDATE `item_winners` SET `steam_id` = "'. $_POST['steam_id'] .'" WHERE `id` = '. $_POST['claim_id'] .' LIMIT 1;';
		$result = mysql_query($query);

		echo(json_encode(array(
			'id' => mysql_insert_id()
		)));

	} else {
		$query = 'UPDATE `item_winners` SET `steam_id` = "'. $_POST['steam_id'] .'" WHERE `id` = '. $_POST['claim_id'] .' LIMIT 1;';
		$r = mysql_query($query);

		echo(json_encode(array(
			'id' => mysql_fetch_object($result)->id
		)));
	}
}

?>