
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
//		console_log("populate_home_shops() --> .done(<"+JSON.stringify(data, null, 3)+">)");
		console_log("populate_home_shops() --> .done(<"+data.length+">)");

		$('.marketplace-wrapper').empty();
		$('.marketplace-wrapper').removeClass('is-hidden');

		$.each(data, function(i, item) {
			var isValid = true;
			product_types[item.id] = parseInt(item.type);
//			check_asset_url(item.image_url.replace(/^.*\/thumbs\/(.*)$/g, 'http://prebot.me/thumbs/$1'), function(isValid) {
				var html = '';
				html += '<div>';
				html += '<div class="thumbnail" data-product-id="'+ item.id +'"><img src="' + ((isValid) ? item.image_url.replace(/^.*\/thumbs\/(.*)$/g, 'http://prebot.me/thumbs/$1') : "/assets/images/placeholder_storefront.gif") + '" width="400" alt="' + item.product_name + '" /></div>';
				html += '<div><i class="fa fa-check '+((item.type == 1 || item.type == 2) ? 'is-hidden' : '')+'" style="text-align:right;" aria-hidden="true"></i>' + item.storefront_name + '</div>';
				html += '<div class="deeplink"><a href="'+item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/lmon8?ref=/$2')+'" target="_blank">'+item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/lmon8?ref=/$2')+'</a></div>';
				html += "</div>";
				html += "<hr />";


//				html += '<div class="col-lg-3 col-md-4 col-xs-6 thumb">';
//				html += '  <div class="thumbnail" data-product-id="'+ item.id +'" style="margin-bottom:4px">';
//				html += '    <img class="img-responsive" src="' + ((isValid) ? item.image_url.replace(/^.*\/thumbs\/(.*)$/g, 'http://prebot.me/thumbs/$1') : "/assets/images/placeholder_storefront.gif") + '" width="400" alt="' + item.product_name + '" />' + item.storefront_name;
//				html += '    <i class="fa fa-check '+((item.type == 1 || item.type == 2) ? 'is-hidden' : '')+'" style="text-align:right;" aria-hidden="true"></i>';
//				html += '  </div>';
//				html += '  <div class="deeplink"><a href="'+item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/lmon8?ref=/$2')+'" target="_blank">'+item.prebot_url.replace(/^(.*)\/(.+)$/g, 'http://m.me/lmon8?ref=/$2')+'</a></div>';
//				html += '</div>';

				$('.marketplace-wrapper').append(html);
//				$('.marketplace-wrapper').html($('.marketplace-wrapper').html() + html);
//			});
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
				type         : (product_types[$(this).attr('data-product-id')] == 1 || product_types[$(this).attr('data-product-id')] == 2) ? product_types[$(this).attr('data-product-id')] + 4 : product_types[$(this).attr('data-product-id')] - 4
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