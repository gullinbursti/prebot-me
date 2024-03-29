
function fill_storefront(storefront_id) {
	console_log("fill_storefront("+storefront_id+"): ");

	$.ajax({
		url      : "/api/api.php",
		method   : 'POST',
		dataType : 'json',
		data     : {
			ACCESS_TOKEN  : ACCESS_TOKEN,
			action        : "FETCH_STOREFRONT_SHARE",
			storefront_id : storefront_id
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("populate_shop() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("populate_shop() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("populate_shop() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("populate_shop() --> .done <"+JSON.stringify(data, null, 3)+">");

		var prebot_url = data.prebot_url.replace(/^.*\/(.+)/, 'm.me/prebotme?ref=/$1');
		$('.prebot-url').html('<a href="http://' + prebot_url + '" target="_blank">' + prebot_url + '</a>');
		//$('.fb-url').attr('href', "http://www.facebook.com/sharer.php?s=100&p[title]=" + encodeURIComponent(data.display_name) + '&p[summary]=' + encodeURIComponent(data.description) + '&p[url]=' + encodeURIComponent(prebot_url) + '&p[images][0]=' + encodeURIComponent(data.logo_url));
		$('.messenger-url').attr('href', "http://" + prebot_url);
		$('.twitter-url').attr('href', "https://twitter.com/intent/tweet?text=" + encodeURIComponent("I am selling " + data.display_name + " on Lemonade. Tap to buy!") + "&url=" + encodeURIComponent("http://" + prebot_url));
	});
}


$(document).ready(function() {
	console_log("$(document).ready!!");
	fill_storefront(getCookie('storefront_id'));
});