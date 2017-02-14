
function populate_form(product_id) {
	console_log("populate_form("+product_id+"): ");

	$.ajax({
		url      : "/api/api.php",
		method   : 'POST',
		dataType : 'json',
		data     : {
			ACCESS_TOKEN : ACCESS_TOKEN,
			action       : "FETCH_PRODUCT",
			product_id   : product_id
		}
	}).then(null, function (jqXHR, textStatus, errorThrown) {
		console_log("populate_form() --> .then <"+textStatus+", "+errorThrown+">");

	}).always(function () {
		console_log("populate_form() --> .always");

	}).fail(function (jqXHR, textStatus, errorThrown) {
		console_log("populate_form() --> .fail <"+textStatus+", "+errorThrown+">", 'error');

	}).done(function (data) {
		console_log("populate_form() --> .done <"+JSON.stringify(data, null, 3)+">");
		$('#product-name').val(data.display_name);
		$('#product-price').val(data.price);
		$('#frm-paypal').submit();
	});
}

function close_page() {
	MessengerExtensions.requestCloseBrowser(function success() {
	}, function error(err) {
	});
}

$(document).ready(function() {
	console_log("$(document).ready!!");

	if (getCookie('is_close') == "1") {
		if (getCookie('customer_id')) {
			console_log("HE'S BACK!!! ("+getCookie('customer_id')+")");
		}
		close_page();

	} else {
		populate_form(getCookie('product_id'));
	}
});