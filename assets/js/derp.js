/**
 * STOREHOUSE - node-steamcommunity
 *
 * Uses node-steamcommunity to login to Steam, accept and confirm all incoming trade offers,
 *    node-steam-totp to generate 2FA codes
 */

var SteamCommunity = require('steamcommunity');
var SteamTotp = require('steam-totp');
var TradeOfferManager = require('../lib/index.js'); // use require('steam-tradeoffer-manager') in production
var fs = require('fs');

var steam = new SteamCommunity();
var manager = new TradeOfferManager({
	"domain"       : "example.com", // Our domain is example.com
	"language"     : "en", // We want English item descriptions
	"pollInterval" : 5000 // We want to poll every 5 seconds since we don't have Steam notifying us of offers
});

// Steam logon options
var logOnOptions = {
	"accountName"    : "teammodd",
	"password"       : "gkY!z}[H0u7",
	"twoFactorCode"  : SteamTotp.getAuthCode("uJ4IvbWUg9RdMG5m/OqtYfTKE6w=")
};

if (fs.existsSync('steamguard.txt')) {
	logOnOptions.steamguard = fs.readFileSync('steamguard.txt').toString('utf8');
}

if (fs.existsSync('polldata.json')) {
	manager.pollData = JSON.parse(fs.readFileSync('polldata.json'));
}

steam.login(logOnOptions, function(err, sessionID, cookies, steamguard) {
	if (err) {
		console.log("Steam login fail: " + err.message);
		process.exit(1);
	}

	fs.writeFile('steamguard.txt', steamguard);

	console.log("Logged into Steam");

	manager.setCookies(cookies, function(err) {
		if (err) {
			console.log(err);
			process.exit(1); // Fatal error since we couldn't get our API key
			return;
		}

		console.log("Got API key: " + manager.apiKey);
	});

	//steam.startConfirmationChecker(30000, "cju/5tnWhDN6oJLqLQtxhinL0lo="); // Checks and accepts confirmations every 30 seconds
	sendTradeOffer("https://steamcommunity.com/tradeoffer/new/?partner=46143802&token=KYworVTM", 730, 2)
});

/*
 manager.on('newOffer', function(offer) {
 console.log("New offer #" + offer.id + " from " + offer.partner.getSteam3RenderedID());
 offer.accept(function(err) {
 if (err) {
 console.log("Unable to accept offer: " + err.message);
 } else {
 steam.checkConfirmations(); // Check for confirmations right after accepting the offer
 console.log("Offer accepted");
 }
 });
 });

 manager.on('receivedOfferChanged', function(offer, oldState) {
 console.log(`Offer #${offer.id} changed: ${TradeOfferManager.ETradeOfferState[oldState]} -> ${TradeOfferManager.ETradeOfferState[offer.state]}`);

 if (offer.state == TradeOfferManager.ETradeOfferState.Accepted) {
 offer.getReceivedItems(function(err, items) {
 if (err) {
 console.log("Couldn't get received items: " + err);
 } else {
 var names = items.map(function(item) {
 return item.name;
 });

 console.log("Received: " + names.join(', '));
 }
 });
 }
 });
 */

manager.on('pollData', function(pollData) {
	fs.writeFile('polldata.json', JSON.stringify(pollData), function() {});
});



// send a trade offer
function sendTradeOffer(tradeURL, appID, contextID) {
	console.log("sendTradeOffer(tradeURL="+tradeURL+", appID="+appID+", contextID="+contextID+")");


	// load inventory
	console.log('StatusMsg : Loading inventory...');
	manager.getInventoryContents(appID, contextID, true, function(error, inventory, currencies) {
		// failed to load inventory
		if(error) {
			console.log('\nError: Failed to load inventory\n');
			console.log(error);
			return;
		}

		// empty inventory
		if(inventory.length === 0) {
			console.log('StatusMsg : No tradable items found in inventory');
			// acknowledge admin
			client.chatMessage(config.admin, 'Sorry! I don\'t have any item to send.');
			return;
		}

		// inventory loaded
		console.log('StatusMsg : Total ' + inventory.length + ' tradable items found in inventory');

		// create a trade offer
		var offer = manager.createOffer(tradeURL);
		offer.addMyItem(inventory[0]);

		// send the trade offer
		offer.send(function(error, status) {
			// failed to send the trade offer
			if(error) {
				console.log('\nError: Failed to send the trade offer\n');
				console.log(error);
				return;

			} else if(status === 'pending') { // the trade offer is sent but needs confirmation
				console.log('StatusMsg : The trade offer is sent but needs confirmation');
				//acdept the trade offer
				steam.acceptConfirmationForObject("cju/5tnWhDN6oJLqLQtxhinL0lo=", offer.id, function(error) {
					// failed to confirm the trade offer
					if(error) {
						console.log('\nError: Failed to confirm the trade offer\n');
						console.log(error);
						return;

					} else { // the trade offer is confirmed
						console.log('StatusMsg : The trade offer is confirmed');
						// acknowledge admin
						client.chatMessage(config.admin, 'Yo! I have sent you ' + inventory.length + ' items.');
						return;
					}
				});
			} else { // the trade offer is sent successfully
				console.log('StatusMsg : The trade offer is sent');
				// acknowledge admin
				client.chatMessage(config.admin, 'Yo! I have sent you ' + inventory.length + ' items.');
				return;
			}
		});
	});
}



/*
 * Example output:
 *
 * Logged into Steam
 * Got API key: <key>
 * New offer #474139989 from [U:1:46143802]
 * Offer accepted
 * Offer #474139989 changed: Active -> Accepted
 * Received: Reinforced Robot Bomb Stabilizer
 */