<?php

setlocale(LC_MONETARY, 'en_US');

define('DB_HOST', "138.197.216.56");
define('DB_NAME', "prebot_marketplace");
define('DB_USER', "pre_usr");
define('DB_PASS', "f4zeHUga.age");


// make the connection
$db_conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect to database");

// select the proper db
mysql_select_db(DB_NAME) or die("Could not select database");
mysql_set_charset('utf8');

// tally up product purchases
$query = 'SELECT `id`, `product_id` FROM `purchases`;';
$result = mysql_query($query);
$purchases_arr = array();
while($purchase_obj = mysql_fetch_object($result)) {
	if (array_key_exists($purchase_obj->product_id, $purchases_arr)) {
		$purchases_arr[$purchase_obj->product_id]++;

	} else {
		$purchases_arr[$purchase_obj->product_id] = 1;
	}
}

arsort($purchases_arr);


// user stats
$query = 'SELECT `users`.`id`, `users`.`fb_psid`, `users`.`points`, `fb_users`.`first_name`, `fb_users`.`last_name` FROM `users` INNER JOIN `fb_users` ON `users`.`id` = `fb_users`.`user_id` WHERE `users`.`id` IN (SELECT `user_id` FROM `subscriptions`) AND `users`.`points` > 0 ORDER BY `points` DESC;';
$result = mysql_query($query);

$users_arr = array();
while($user_obj = mysql_fetch_object($result)) {
	array_push($users_arr, $user_obj);
}

?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Leaderboard</title>
		<link rel="icon" href="/assets/images/favicon.png">
		<style type="text/css" rel="stylesheet">
			html, body {
				margin 0;
				padding: 0;
				font-family: Verdana, Helvetica, sans-serif;
				font-size: 12px;
			}

			a, a:active, a:visited {
				text-decoration: underline;
			}

			a:hover {
				text-decoration: none;
				cursor: pointer;
			}

			h2 {
				border-bottom: 1px solid #000000;
				margin-bottom: 12px;
			}

			hr {
				width: 90%;;
				border: 1px solid #999999;
				margin-top: 10px;
				margin-bottom: 10px;
			}

			table {
				width: 100%;
			}

			td {
				padding: 0 3px 0 3px;
				height: 24px;
				vertical-align: middle;
			}

			.flex-container {
				padding: 0;
				margin: 0;
				display: flex;
				justify-content: space-between;
				flex-direction: row;
				flex-wrap: nowrap;
			}

			.flex-item {
				padding: 2px;
				height: 24px;
				flex: 1 0 0;
			}

			.title {
				background-color: #ffff00;
				border: 1px solid #333333;
				font-weight: bold;
				text-transform: uppercase;
				text-align: center;
				flex:
			}

			.header, .sub-header {
				background-color: #e0e0e0;
				border: 1px solid #666666;
				font-weight: bold;
			}

			.sub-header {
				text-align: center;
			}

			.row {
				border: 1px solid #999999;
				text-align: left;
			}

			.disabled {
				text-decoration: line-through;
			}

			.even {
				background-color: #f0f0f0;
			}

			.odd {
				background-color: #f9f9f9;
			}

			.quantity {
				text-align: right;
			}



			.na {
				font-style: italic;
				color: #666666;
			}

			.is-hidden {
				display: none;
				visibility: hidden;
			}
		</style>
	</head>

	<body>
		<div class="page-header">
			<h2>Lemonade Leaderboards</h2>
		</div>
		<div class="wrapper">
			<div class="flex-container"></div>
			<table cellpadding="0" cellspacing="1">
				<tr><td class="title" colspan="6">Purchases / Shop</td></tr>
				<tr><td class="header">#</td><td class="header">Shop Name</td><td class="header">Owned By</td><td class="header">Product Name</td><td class="header quantity">Sold</td><td class="header quantity">Profit</td></tr>
				<?php $cnt = 0;
				foreach ($purchases_arr as $key=>$val) {
					$query = 'SELECT `storefront_id`, `display_name`, `prebot_url`, `price`, `enabled` FROM `products` WHERE `id` = '. $key . ' LIMIT 1;';
					$result = mysql_query($query);
					if (mysql_num_rows($result) == 1) {
						$product_obj = mysql_fetch_object($result);

						$query = 'SELECT `owner_id`, `display_name`, `enabled` FROM `storefronts` WHERE `id` = '. $product_obj->storefront_id . ' LIMIT 1;';
						$result = mysql_query($query);
						$storefront_obj = mysql_fetch_object($result);

						$query = 'SELECT `first_name`, `last_name` FROM `fb_users` WHERE `user_id` = '. $storefront_obj->owner_id . ' LIMIT 1;';
						$result = mysql_query($query);
						$fbUser_obj = mysql_fetch_object($result);

					} else {
						continue;
					}

					$row_css = "row ". ((++$cnt % 2 == 0) ? "even" : "odd");
					$fb_owner = (strlen($fbUser_obj->first_name) > 0 && strlen($fbUser_obj->last_name) > 0) ? $fbUser_obj->first_name ." ". $fbUser_obj->last_name : "<span class='na'>N/A</span>";

					$html = "<td class='". $row_css ."'>". number_format($cnt) ."</td>";
					$html .= "<td class='". $row_css ."". (($storefront_obj->enabled == 0) ? " disabled" : "") ."'>". $storefront_obj->display_name ."</td>";
					$html .= "<td class='". $row_css ."'>". $fb_owner ."</td>";
					$html .= "<td class='". $row_css ."". (($product_obj->enabled == 0) ? " disabled" : "") ."'>". (($product_obj->enabled == 1) ? "<a href='". preg_replace('/^http\:\/\/prebot\.me\/(.*)$/', 'http://m.me/lmon8?ref=/$1', $product_obj->prebot_url) ."' target='_blank'>". $product_obj->display_name ."</a>" : $product_obj->display_name) ."</td>";
					$html .= "<td class='". $row_css ." quantity'>". $val ."</td>";
					$html .= "<td class='". $row_css ." quantity'>". money_format('%n', ($product_obj->price * $val)) ."</td>";
					echo ("<tr>". $html ."</tr>\n");
				} ?>
			</table>

			<hr noshade />

			<table cellpadding="0" cellspacing="1">
				<tr><td class="title" colspan="6">Users By Points</td></tr>
				<tr><td class="header">#</td><td class="header">FB ID / Name</td><td class="header quantity">Subscriptions</td><td class="header quantity">Purchases</td><td class="header quantity">Total Spent</td><td class="header quantity">Points</td></tr>
				<?php $cnt = 0;
				foreach($users_arr as $user_obj) {
					$query = 'SELECT COUNT(*) AS `tot` FROM `subscriptions` WHERE `user_id` = '. $user_obj->id .' AND `enabled` = 1;';
					$result = mysql_query($query);
					$subscription_total = (mysql_num_rows($result) > 0) ? mysql_fetch_object($result)->tot : 0;

					$query = 'SELECT `products`.`price` FROM `products` INNER JOIN `purchases` ON `products`.`id` = `purchases`.`product_id` WHERE `user_id` = '. $user_obj->id .';';
					$result = mysql_query($query);
					$purchase_tot = 0;
					$purchase_price = 0;
					while ($purchase_obj = mysql_fetch_object($result)) {
						$purchase_tot += $purchase_obj->price;
						$purchase_price++;
					}

					$row_css = "row ". ((++$cnt % 2 == 0) ? "even" : "odd");
					$html = "<td class='". $row_css ."'>". $cnt ."</td>";
					$html .= "<td class='". $row_css ."'>". $user_obj->fb_psid ." / ". ($user_obj->first_name ." ". $user_obj->last_name) ."</td>";
					$html .= "<td class='". $row_css ." quantity'>". number_format($subscription_total) ."</td>";
					$html .= "<td class='". $row_css ." quantity'>". number_format($purchase_price) ."</td>";
					$html .= "<td class='". $row_css ." quantity'>". money_format('%n', $purchase_tot) ."</td>";
					$html .= "<td class='". $row_css ." quantity'>". number_format($user_obj->points) ."</td>";
					echo ("<tr>". $html ."</tr>\n");
				}
				?>
			</table>
		</div>
	</body>
</html>


<?php
// close db
if ($db_conn) {
	mysql_close($db_conn);
	$db_conn = null;
}

?>