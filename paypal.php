<?php

$product_id = (isset($_GET['product_id'])) ? $_GET['product_id'] : "";
$customer_id = (isset($_GET['customer_id'])) ? $_GET['customer_id'] : "";
$is_close = (isset($_GET['$close']));

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>PayPal</title>

		<!-- Bootstrap Core CSS -->
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>

		<link rel="stylesheet" href="/assets/css/prebot-commons.css" type="text/css" media="all" />
		<script type="text/javascript" src="/assets/js/prebot-commons.js"></script>

		<script type="text/javascript">

			setCookie('product_id', "<?= ($product_id); ?>");
			setCookie('customer_id', "<?= ($customer_id); ?>");
			setCookie('is_close', "<?= (intval($is_close == true)); ?>");
		</script>

		<style type="text/css" rel="stylesheet" media="all">
		</style>
	</head>

	<body>
		<script>
			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'Messenger'));
		</script>


		<!-- Page Content -->
		<div class="container">
			<div class="prebot-url"></div>

			<form id="frm-paypal" class="is-hidden" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="image_url" value="https://scard.tv/static/logo-paypal.jpg">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="return" value="http://prebot.me/checkout/cancel">
				<input type="hidden" name="cancel_return" value="http://prebot.me/checkout/cancel">
				<input type="hidden" name="business" value="jason@modd.live">
				<input type="hidden" name="currency_code" value="USD">
				<input id="product-name" type="hidden" name="item_name" value="">
				<input id="product-name" type="hidden" name="item_number" value="">
				<input id="product-price" type="hidden" name="amount" value="">
				<input id="customer-note" type="hidden" name="cn" value="Enter your trade URL below…">
				<input id="customer-id" type="hidden" name="custom" value="">
				<input id="product-price" type="hidden" name="notify_url" value="">
				<input id="product-price" type="hidden" name="amount" value="">
			</form>

		</div>
		<!-- /.container -->

		<!-- jQuery -->
		<script src="/assets/js/jquery-2.1.1.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="/assets/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="/assets/js/paypal.js"></script>
	</body>
</html>



<?php


// acct api:
$express_token = "access_token$production$5x9mkh6xz2wqwvh6$cc8f73cea0ac0524e1c3fa05ff6188a1";



// business / verified / bank + cc
$email = "biflindi.fjolnir@gmail.com";
$passwd = "flashmaster";
$phone = "4084127711";
$country_code = "US";

$acct_type = "Business";
$acct_status = "Verified";

$bank_acct = "541729804034068";
$routing = "325272063";

$cc_accout = "5110921317968124";
$cc_type = "MASTERCARD";
$cc_exp = "02/2022";

$balance = "666.00";

//api
$email_id = "biflindi.fjolnir_api1.gmail.com";
$passwd = "S2NWRBCFYNCC2AM3";
$signature = "An5ns1Kso7MWUdW4ErQKJJJ4qi4-AOVvelEAAQsJNguk0VuPSnhP5s5w";



// personal -- no verify / no bank
$email_id = "arthurpewty888@gmail.com";
$passwd = "montypython";
$phone = "0359319698";
$country_code = "GB";

$acct_type = "personal";
$acct_status = "unverified";

$balance = "256.00";

?>