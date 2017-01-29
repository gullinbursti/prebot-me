

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
			action : FETCH_MARKETPLACE
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("populate_home_shops() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("populate_home_shops() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("populate_home_shops() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("populate_home_shops() --> .done<"+JSON.stringify(data, null, 3)+">");

		$('.marketplace-wrapper').empty();
		$('.marketplace-wrapper').removeClass('is-hidden');

		$.each(data, function(i, item) {
			var matches = item.description.match(/^(.*)\b(\d+)$/);
			var release_info = matches[1] + Number(matches[2]).ordinal(true);

			var html = '';
			html += '<div class="storefront-row">';
			html += '  <div class="storefront-name"><h4 style="margin: 20px 0 10px 0;">' + item.storefront_name + '</h4></div>';
			html += '  <div class=""><a href="' + item.prebot_url + '" target="_blank"><img class="storefront-graphic" style="width: 256px" src="' + item.image_url + '" width="256" height="256" alt="' + item.display_name + '" /></a></div>';
			html += '  <div class="storefront-info"><p style="margin:0 0 10px 0; padding:0"><a href="' + item.prebot_url + '" target="_blank" class="product-name">' + item.product_name + '</a> - $' + Number(item.price).currencyFormatted() + '<br>' + release_info +'</p></div>';
			html += '  <div class="storefront-details intro-text">';
			html += '    <div class="storefront-views"><h5>&bull; <span class="storefront-stat">' + item.views + "</span> " + String.quantitate('viewer', item.views) + '</h5></div>';
			html += '    <div class="storefront-subscribers"><h5>&bull; <span class="storefront-stat">' + item.subscribers + "</span> " + String.quantitate('subscriber', item.subscribers) + '</h5></div>';
			html += '    <div class="storefront-preorders"><h5>&bull;  <span class="storefront-stat">' + item.preorders + "</span> " + String.quantitate('preorder', item.preorders) + '</h5></div>';
			html += '    <div class="storefront-url"><h6 style="font-size:12px; margin: 15px 0 5px 0;"><a href="' + item.prebot_url + '" target="_blank">' + item.prebot_url + '</a></h6></div>';
			html += '  </div>';

			html += '</div>';

			$('.marketplace-wrapper').append(html);
		});
	});
}



$(document).ready(function() {
	console_log("$(document).ready!!");

	populate_home_shops();
});