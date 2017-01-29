

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



function fetch_claim_item() {
	console_log("fetch_claim_item()");

	$.ajax({
		url         : "/api/gamebots_api.php",
		method      : 'POST',
		dataType    : 'json',
		data        : {
			action      : 'FETCH_CLAIM_ITEM',
			claim_id    : getCookie('claim_id'),
			social_name : getCookie('social_name')
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("fetch_claim_item() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("fetch_claim_item() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("fetch_claim_item() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("fetch_claim_item() --> .done<"+JSON.stringify(data, null, 3)+">");

		$('.item-name').text(data.name);
		$('.game-name').text(data.game_name);
		$('.item-description').html(data.description);
		$('.item-image').attr('src', data.image_url);
		$('.pin-code').text(data.pin_code);
		$('.trade-url').html('<a href="' + data.trade_url + '" target="_blank">' + data.trade_url + '</a>');

		setCookie('trade_url', data.trade_url);

		$('.intro-text').removeClass('is-hidden');
	});
}

function add_steam_user() {
	console_log("add_steam_user()");

	$.ajax({
		url         : "/api/gamebots_api.php",
		method      : 'POST',
		dataType    : 'json',
		data        : {
			action      : 'ADD_STEAM_USER',
			steam_id    : getCookie('steam_id'),
			social_name : getCookie('social_name'),
			claim_id    : getCookie('claim_id')
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("add_steam_user() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("add_steam_user() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("add_steam_user() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("add_steam_user() --> .done<"+JSON.stringify(data, null, 3)+">");
		//location.href = getCookie('trade_url')
	});
}


$(document).ready(function() {
	console_log("$(document).ready!!");

	if (getCookie('claim_id') != "") {
		setTimeout(function() {
			fetch_claim_item();

			if (getCookie('steam_id') == "") {
				location.href = "/assets/php/steam_auth.php?login";
			}
		}, 2000);
	}

	if (getCookie('steam_id') != "") {
		add_steam_user();
	}
});
