
var ACCESS_TOKEN = "6849858F2449BCBF62D0309B8D2A7F03726A861F79D8C559400E551312812B3450EF0";
var FETCH_MARKETPLACE = "FETCH_MARKETPLACE";
var FETCH_STOREFRONT = "FETCH_STOREFRONT";
var FETCH_PRODUCT = "FETCH_PRODUCT";

var FETCH_CLAIM_ITEM = "FETCH_CLAIM_ITEM";
var ADD_STEAM_USER = "ADD_STEAM_USER";

var FB_PAGE_NAME = "prebotme";

/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */
/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */

function console_log(msg, lvl) {
	lvl = (lvl || 'log');

	if (lvl == 'info') {
		console.info(msg);

	} else if (lvl == 'warn') {
		console.warn(msg);

	} else if (lvl == 'error') {
		console.error(msg);

	} else {
		console.log(msg);
	}
}


/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */


function build_referral(storefront_name, product_name) {
	storefront_name = (storefront_name || '');
	product_name = (product_name || '');

	var slugs = [
		storefront_name,
	  product_name
	];

	return (('/' + slugs.join('/')).replace(/^\/\/(.*)\/$/g, '$1'));
}

function redirect(url, delay) {
	delay = (delay || 125);

	console_log(":: REDIRECTING --> '"+url+"' in "+(delay * 0.001).toFixed(3)+"sec...");

	setInterval(function () {
		location.href = url;
	}, delay);
}

/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */

function deleteCookie(key) {
	document.cookie = key + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT;";
}

function getCookie(key) {
	var val = "";

	var cookies = document.cookie.split(";");
	for (var i=0; i<cookies.length; i++) {
		var c = cookies[i];
		while (c.charAt(0) == " ")
			c = c.substring(1);

		if (c.indexOf(key) != -1) {
			val = c.split("=")[1];
			break;
		}
	}

	return (val);
}

function setCookie(key, val) {
	if (!val || val.length == 0) {
		deleteCookie(key);

	} else {
		var d = new Date();
		d.setDate(d.getDate() + 365);
		document.cookie = (key + "=" + val + ";domain=." + location.hostname + ";path=/" + ";expires=" + d.toUTCString());
	}
}


/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */
/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */

/**
 * Returns indexes where an element matches
 */
if (!Array.prototype.containsAt) {
	Array.prototype.containsAt = function (obj) {
		var i = this.length;
		var inds = [];

		while (i--) {
			if (this[i] === obj)
				inds.unshift(i);
		}

		return (inds);
	};
}

/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */

/**
 * Generates a random integer w/in a range
 */
if (!Math.randomInt) {
	Math.randomInt = function (lower, upper) {
		return (this.floor(this.random() * (this.max(lower, upper) - this.min(lower, upper)) + this.min(lower, upper)));
	};
}

/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */


/**
 * Limits a number to MIN > x < MAX
 */
if (!Number.prototype.clamp) {
	Number.prototype.clamp = function (lower, upper) {
		return (Math.min(Math.max(this, lower), upper));
	};
}

/**
 * Inserts commas every 3rd digit
 */
if (!Number.prototype.commaFormatted) {
	Number.prototype.commaFormatted = function () {
		return (this.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
	};
}

/**
 * Adds trailing 0 if needed
 */
if (!Number.prototype.currencyFormatted) {
	Number.prototype.currencyFormatted = function () {
		console_log((this - parseInt(this)) * 100 % 10);
		return ((((this - parseInt(this)) * 100 % 10) == 0) ? (this.toString() + '0') : this.toString());
	};
}

/**
 * Determines st/nd/rd/th of a number
 */
if (!Number.prototype.ordinal) {
	Number.prototype.ordinal = function (isHTML) {
		//isHTML = ((typeof isHTML == "undefined" || isHTML == true) || false);
		console_log(isHTML);

		var digit = this.toString().match(/(?:\d*)(\d)/)[1];
		var suffix = 'th';

		if (digit == 1) {
			suffix = 'st';

		} else if (digit == 2) {
			suffix = 'nd';

		} else if (digit == 3) {
			suffix = 'nd';
		}

		return (this.toString() + ((isHTML) ? '<sup>' + suffix + '</sup>' : suffix));
	};
}


/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */

/**
 * Create string from HTML entities
 */
if (!String.prototype.fromHtmlEntities) {
	String.prototype.fromHtmlEntities = function (string) {
		return (string + "").replace(/&#\d+;/gm, function (s) {
			return String.fromCharCode(s.match(/\d+/gm)[0]);
		});
	};
}

/**
 * Changes to plural form
 */
if (!String.prototype.pluralize) {
	String.prototype.pluralize = function () {
		// already plural
		if (this.match(/^\w+(?:s|ies)$/m)) {
//		if (this.match(/^\w*(?!s|ies)$/m)) {
			return (this.toString());

		// ends in -y, drop & append -ies
		} else if (this.match(/y$/m)) {
			return (this.replace(/(\w+)y$/gm, function (s, $1) {
//			return (this.replace(/^(\w)+(?=y)$/m, function (s, $1) {
					return ($1 + 'ies');
			}));

		// append -s
		} else {
			return (this.toString() + 's');
//			return (this.replace(new RegExp('([A-Za-z]+)(?:y)$', 'i'), function ($0, $1, $2, $3) {
//				console_log('String.prototype.pluralize(this:['+this+'] $0:['+$0+'] $1:['+$1+'] $2:['+$2+'] $3:['+$3+'])');
//				return (this + 'ies');
//			}));
		}
	};
}

/**
 * Makes an item plural if val != 1
 */
if (!String.quantitate) {
	String.quantitate = function (item, val) {
		return ((val == 1) ? item : item.pluralize())
	};
}

/**
 * Convert a string to HTML entities
 */
if (!String.prototype.toHtmlEntities) {
	String.prototype.toHtmlEntities = function () {
		return this.replace(/./gm, function (s) {
			return "&#" + s.charCodeAt(0) + ";";
		});
	};
}

/**
 * Clips a string to given length, & appends an ellipsis
 */
if (!String.prototype.truncate) {
	String.prototype.truncate = function (amt, ellipsis) {
		ellipsis = (ellipsis || '…');
		return (this.replace(
			new RegExp('^(.{'+(amt-1)+'})(?:.*)$', 'm'), function ($0, $1, $2) {
				console_log('String.prototype.truncate:(amt:['+amt+'] ellipsis:['+ellipsis+']) $0:['+$0+'] $1:['+$1+'] $2:['+$2+']');
				return ($1 + ellipsis);
			}
		));

		//return ((this.length > amt) ? (this.substring(0, amt - 1) + ((!ellipsis) ? "…" : "")) : (this));
	};
}

/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */
/* *=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=*=-=* */

var query_string = {};
window.location.href.replace(
	new RegExp('([^?=&]+)(=([^&]*))?', 'g'), function ($0, $1, $2, $3) {
		//console_log('QUERYSTRING: $0:['+$0+'] $1:['+$1+'] $2:['+$2+'] $3:['+$3+']');

		var $p1 = ($1 || '');
		var $p3 = ($3 || '');

		query_string[$p1] = $p3.replace(/^.*#(.*)$/g, '$1');
	}
);
