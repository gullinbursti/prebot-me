
function fill_storefront(storefront_name) {
	console_log("fill_storefront("+storefront_name+"): ");

	$.ajax({
		url      : "/api/api.php",
		method   : 'POST',
		dataType : 'json',
		data     : {
			ACCESS_TOKEN    : ACCESS_TOKEN,
			action          : "FETCH_STOREFRONT_SHARE",
			storefront_name : storefront_name
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("populate_shop() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("populate_shop() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("populate_shop() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("populate_shop() --> .done <"+JSON.stringify(data, null, 3)+">");
		$('.prebot-url').html('<a href="' + data.prebot_url + '" target="_blank">' + data.prebot_url + '</a>');
		//$('.fb-url').attr('href', "http://www.facebook.com/sharer.php?s=100&p[title]=" + encodeURIComponent(data.display_name) + '&p[summary]=' + encodeURIComponent(data.description) + '&p[url]=' + encodeURIComponent(data.prebot_url) + '&p[images][0]=' + encodeURIComponent(data.logo_url));
		$('.messenger-url').attr('href', data.prebot_url);
		$('.twitter-url').attr('href', "https://twitter.com/intent/tweet?text=" + encodeURIComponent("I am selling " + data.display_name + " on Lemonade. Tap to buy!") + "&url=" + encodeURIComponent(data.prebot_url));
	});
}


$(document).ready(function() {
	console_log("$(document).ready!!");
	fill_storefront(getCookie('storefront_name'));
});