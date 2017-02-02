<?php

$claim_id = (isset($_GET['id'])) ? $_GET['id'] : "";
$social_name = (isset($_GET['social'])) ? $_GET['social'] : "";
$steam_id = (isset($_GET['steam_id'])) ? $_GET['steam_id'] : "";

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Loading Steam...</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Pre™ - Shop Bots">
		<meta name="keywords" content="Pre™ - Shop Bots">
		<link rel="icon" href="/assets/images/favicon.png">
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
		<link href='https://fonts.googleapis.com/css?family=Poppins:400,600,700|Work+Sans:600,500,400,300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="/assets/css/animate.css"> <!-- Resource style -->
		<link rel="stylesheet" href="/assets/css/ionicons.min.css"> <!-- Resource style -->
		<link href="/assets/css/style-claim.css" rel="stylesheet" type="text/css" media="all" />
		<link href="/assets/css/font-awesome.css" rel="stylesheet" type="text/css" media="all" />
		<link rel="stylesheet" href="/assets/css/prebot-commons.css" type="text/css" media="all" />
		<script type="text/javascript" src="/assets/js/prebot-commons.js"></script>

		<script type="text/javascript">
			setCookie('claim_id', (getCookie('claim_id') == "") ? "<?= ($claim_id); ?>" : getCookie('claim_id'));
			setCookie('social_name', (getCookie('social_name') == "") ? "<?= ($social_name); ?>" : getCookie('social_name'));
			setCookie('steam_id', (getCookie('steam_id') == "") ? "<?= ($steam_id); ?>" : getCookie('steam_id'));
		</script>

		<script>
			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'Messenger'));
		</script>

	</head>

	<body>
		<div class="wrapper">
			<!-- Homepage Main Section-->
			<div class="main app-main">
				<section class="split-home">
<!--					<div class="arrow"><i class="fa fa-chevron-down" aria-hidden="true"></i></div>-->
					<div class="buy-button">
						<a href="/assets/php/steam_auth.php?login" class="wow  " data-wow-delay=".2s"><img src="/assets/images/signin_steam.png"></a>
					</div>

<!--					<section class="left-section app wow " data-wow-delay="0.1s"></section>-->

					<section class="right-section">
						<div class="main-logo">
							<a href="#"><img class="item-image wow " src="" width="188" height="188" alt="Logo" style="border:0px solid #ccc; background:#fff; padding:0px" /></a>
						</div>

						<div class="intro intro-app">
							<div class="intro-text">
<!--								<p>1. Invite 2 friends to <a href="http://me.me/gamebotsc" target="_blank" style="padding-right:0px;">m.me/gamebotsc</a></p>-->
								<!--	<p>2. Give Gamebots +rep on Steam: <a href="http://steamcommunity.com/id/gamebots" target="_blank">steamcommunity.com/id/gamebots</a></p>-->
								<!--<p>2. Give 5 stars to <a href="http://me.me/gamebotsc" target="_blank" style="padding-right:0px;">m.me/gamebotsc</a></p>-->
<!--								<p>2. Install & open free app: <a href="http://taps.io/BgNYg" target="_blank" style="padding-right:0px;">Install Here</a></p>-->
								<p>Submit Steam Trade: <span class="trade-url"></span></p>
								<p style="font-size:12px;">Include the following security code inside your trade comments for approval: <span class="pin-code" style="font-size:12px;"></span> <span class="item-name" style="font-size:12px;"></span></p>
							</div>
							<div class="intro-text is-hidden">
								<h1 class="wow  " data-wow-delay=".1s">You won <span class="item-name"></span> for <span class="game-name"></span></h1>
								<p class="wow  ">
									DESCRIPTION: <span class="item-description"></span><br><br>
									<a href="/assets/php/steam_auth.php?login" class="wow  " data-wow-delay=".2s"><img src="/assets/images/signin_steam.png"></a>
								</p>
							</div>
						</div>

						<div class="footer">
							<span class="footer-credits">© Pre™</span>
							<ul style="padding-left:15px;">
								<li><a href="/shops">Shops</a></li>
								<li><a href="/press">Press</a></li>
								<li><a href="/about">About</a></li>
								<li><a href="/terms">Terms</a></li>
							</ul>
						</div>
					</section>
				</section>
			</div><!-- Main-->
		</div><!-- Wrapper -->


		      <!-- Jquery and Js Plugins -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="/assets/js/claim.js"></script>

		<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/assets/js/plugins.js"></script>
		<script type="text/javascript" src="/assets/js/menu.js"></script>
		<script type="text/javascript" src="/assets/js/custom.js"></script>
		<script type="text/javascript" src="/assets/js/parallax.min.js"></script>
		<script type="text/javascript" src="/assets/js/jquery.parallax.min.js"></script>
		<script type="text/javascript" src="/assets/js/custom.js"></script>
	</body>
</html>
