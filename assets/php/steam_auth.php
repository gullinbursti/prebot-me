<?php
ob_start();
session_start();


//function logoutbutton() {
//	echo "<form action='' method='get'><button name='logout' type='submit'>Logout</button></form>"; //logout button
//}
//
//function loginbutton($buttonstyle = "square") {
//	$button['rectangle'] = "01";
//	$button['square'] = "02";
//	$button = "<a href='?login'><img src='/assets/images/signin_steam.png'></a>";
//
//	echo $button;
//}

if (isset($_GET['login'])) {
	require 'steamauth/openid.php';

	try {
		require 'steamauth/SteamConfig.php';
		$openid = new LightOpenID($steamauth['domainname']);

		if (!$openid->mode) {
			$openid->identity = 'http://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());

		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';

		} else {
			if ($openid->validate()) {
				$id = $openid->identity;
				$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);

				$_SESSION['steamid'] = $matches[1];
				include ('steamauth/userInfo.php');
				if (!headers_sent()) {
					header("Location: http://prebot.me/claim/steam/". $steamprofile['steamid']);
					exit;

				} else { ?>
					<script type="text/javascript">window.location.href="<?= ("/claim/steam/". $steamprofile['steamid']); ?>";</script>
					<noscript><meta http-equiv="refresh" content="0;url=<?= ("/claim/steam/". $steamprofile['steamid']); ?>" /></noscript>
					<?php exit;
				}

			} else {
				echo "User is not logged in.\n";
			}
		}

	} catch(ErrorException $e) {
		echo $e->getMessage();
	}
}

if (isset($_GET['logout'])) {
	require 'steamauth/SteamConfig.php';
	session_unset();
	session_destroy();
	header('Location: '.$steamauth['logoutpage']);
	exit;
}

if (isset($_GET['update'])) {
	unset($_SESSION['steam_uptodate']);
	require 'steamauth/userInfo.php';
	header('Location: '.$_SERVER['PHP_SELF']);
	exit;
}


unset($_SESSION['steam_uptodate']); ?>
<!DOCTYPE html>
<html lang="en">
	<head></head>
	<body>
		<?php
		if (!isset($_SESSION['steamid'])) {

			//echo "welcome guest! please login<br><br>";
			//loginbutton(); //login button

		}  else {
			include ('steamauth/userInfo.php');
			header("Location: http://prebot.me/claim/steam/". $steamprofile['steamid']);

			//Protected content
			//echo "Welcome back " . $steamprofile['personaname'] . "</br>";
			//echo "here is your avatar: </br>" . '<img src="'.$steamprofile['avatarfull'].'" title="" alt="" /><br>'; // Display their avatar!

			//logoutbutton();
		} ?>
	</body>
</html>
<!--Version 3.2-->
