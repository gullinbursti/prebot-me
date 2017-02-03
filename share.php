<?php

$storefront_id = (isset($_GET['id'])) ? $_GET['id'] : "";

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Share Shop</title>

		<!-- Bootstrap Core CSS -->
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="/assets/css/thumbnail-gallery.css" rel="stylesheet">

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
			setCookie('storefront_id', (getCookie('storefront_id') == "") ? "<?= ($storefront_id); ?>" : getCookie('storefront_id'));
		</script>

		<style type="text/css" rel="stylesheet" media="all">
			.prebot-url {
				margin-bottom: 10px;
				border-bottom: 1px solid #666;
			}
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
			<div class="row">

				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail fb-url" href="http://www.facebook.com/prebotme" target="_blank">
						<img class="img-responsive" src="/assets/images/fb.jpg" alt="">
						Facebook
					</a>
				</div>

				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail messenger-url" href="http://m.me/prebotme" target="_blank">
						<img class="img-responsive" src="/assets/images/messenger.jpg" alt="">
						Messenger
					</a>
				</div>

				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail instagram-url" href="instagram://camera">
						<img class="img-responsive" src="/assets/images/ig.jpg" alt="">
						Instagram
					</a>
				</div>

				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail twitter-url" href="https://twitter.com/intent/tweet" target="_blank">
						<img class="img-responsive" src="/assets/images/twitter.jpg" alt="">
						Twitter
					</a>
				</div>
			</div>

			<!-- Footer -->
			<footer>
				<div class="row">
					<div class="col-lg-12">
						<p>&copy; 2017</p>
					</div>
				</div>
			</footer>
		</div>
		<!-- /.container -->

		<!-- jQuery -->
		<script src="/assets/js/jquery-2.1.1.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="/assets/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="/assets/js/share.js"></script>
	</body>
</html>
