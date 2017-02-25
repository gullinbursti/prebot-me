

/*
(function (i, s, o, g, r, a, m) {
	i['GoogleAnalyticsObject'] = r;
	i[r] = i[r] || function () {
		(i[r].q = i[r].q || []).push(arguments)
	}, i[r].l = 1 * new Date();
	a = s.createElement(o), m = s.getElementsByTagName(o)[0];
	a.async = 1;
	a.src = g;
	m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-83711813-3', 'auto');
ga('send', 'pageview');
*/



function populate_feature_shops() {
	console_log("populate_feature_shops()");

	$.ajax({
		url      : "/api/api.php",
		method   : 'POST',
		dataType : 'json',
		data     : {
			ACCESS_TOKEN : ACCESS_TOKEN,
			action       : FETCH_FEATURE_STOREFRONTS
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("populate_home_shops() --> .then(<"+jqXHR+">, <"+textStatus+">, <"+errorThrown+">)");

	}).always(function () {
		console_log("populate_home_shops() --> .always()");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("populate_home_shops() --> .fail(<"+jqXHR+">, <"+textStatus+">, <"+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("populate_home_shops() --> .done(<"+JSON.stringify(data, null, 3)+">)");
//		console_log("populate_home_shops() --> .done(<"+data.length+">)");

		$('.marketplace-wrapper').empty();
		$('.marketplace-wrapper').removeClass('is-hidden');

		$.each(data, function(i, item) {
			product_types[item.id] = item.type;
			check_asset_url(item.image_url, function(isValid) {
				var html = '';
				html += '<div class="col-lg-3 col-md-4 col-xs-6 thumb">';
				html += '  <div class="thumbnail" data-product-id="'+ item.id +'" style="margin-bottom:4px">';
				html += '    <img class="img-responsive" src="' + ((isValid) ? item.image_url : "/assets/images/placeholder_storefront.gif") + '" width="400" alt="' + item.product_name + '" />' + item.storefront_name;
				html += '    <i class="fa fa-check '+((item.type == 1) ? 'is-hidden' : '')+'" style="text-align:right;" aria-hidden="true"></i>';
				html += '  </div>';
				html += '  <div class="deeplink"><a href="'+item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/lmon8?ref=/$2')+'" target="_blank">'+item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/lmon8?ref=/$2')+'</a></div>';
				html += '</div>';

				$('.marketplace-wrapper').append(html);
			});
		});
	});
}


var product_types = [];
$(document).ready(function() {
	console_log("$(document).ready!!");
	populate_feature_shops();

	$('body').on('click', 'div.thumbnail', function() {
		console.log("CLICKED:["+$(this).attr('data-product-id')+"]");
		$.ajax({
			url      : "/api/api.php",
			method   : 'POST',
			dataType : 'json',
			data     : {
				ACCESS_TOKEN : ACCESS_TOKEN,
				action       : UPDATE_PRODUCT_TYPE,
				product_id   : $(this).attr('data-product-id'),
				type         : (product_types[$(this).attr('data-product-id')] == 1) ? 2 : 1
			}
		}).then(null, function (jqXHR, textStatus, errorThrown) {
			console_log("update_shop_type() --> .then(<"+jqXHR+">, <"+textStatus+">, <"+errorThrown+">)");

		}).always(function () {
			console_log("update_shop_type() --> .always()");

		}).fail(function (jqXHR, textStatus, errorThrown) {
			console_log("update_shop_type() --> .fail(<"+jqXHR+">, <"+textStatus+">, <"+errorThrown+">", 'error');

		}).done(function (data) {
			console_log("update_shop_type() --> .done(<" + JSON.stringify(data, null, 3) + ">)");
			populate_feature_shops();
		});
	});
});