

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



function populate_home_shops() {
	console_log("populate_home_shops()");

	$.ajax({
		url      : "/api/api.php",
		method   : 'POST',
		dataType : 'json',
		data     : {
			ACCESS_TOKEN : ACCESS_TOKEN,
			action       : FETCH_MARKETPLACE,
			offset       : 0,
			limit        : 100
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
			//var matches = item.description.match(/^(.*)\b(\d+)$/);
			//var release_info = matches[1] + Number(matches[2]).ordinal(true);

			check_asset_url(item.image_url, function(isValid) {
				var html = '';
				html += '<div class="col-lg-3 col-md-4 col-xs-6 thumb">';
				html += '  <a class="thumbnail" href="' + item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/prebotme?ref=/$2') + '">';
				html += '    <img class="img-responsive" src="' + ((isValid) ? item.image_url : "/assets/images/placeholder_storefront.gif") + '" width="400" alt="' + item.product_name + '">';
				html += '    ' + item.storefront_name;
				html += '  </a>';
				html += '</div>';

				$('.marketplace-wrapper').append(html);
			});
		});
	});
}



$(document).ready(function() {
	console_log("$(document).ready!!");

	populate_home_shops();
});