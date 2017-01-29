

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



function populate_shop(storefront_id) {
	console_log("populate_shop()");

	$.ajax({
		url      : "/api/api.php",
		method   : 'POST',
		dataType : 'json',
		data     : {
			ACCESS_TOKEN  : ACCESS_TOKEN,
			action        : FETCH_STOREFRONT,
			storefront_id : storefront_id
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("populate_shop() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("populate_shop() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("populate_shop() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("populate_shop() --> .done<"+JSON.stringify(data, null, 3)+">");

		$('.storefront-name').html(data.display_name);
		$('.storefront-description').html(data.description);
	});
}



$(document).ready(function() {
	console_log("$(document).ready!!");

	populate_shop(query_string['id']);
});