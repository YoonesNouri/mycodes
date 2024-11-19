// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var apiUrl = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbx5GKvSgpzLN-AVvJYGALd8ZEgyT-fOp9k0bCO1AFmuP14bCPYenOEJVJLxQL3qGia6eA/exec";

// Define functions for commands
var commandFunctions = {
  "/let's go": function(from) {
    return "Welcome to my bot. Your user ID is: " + from;
  },
  "/storeUserInfo": function(from) {
    storeUserInfo(from);
    return "User info stored successfully.";
  },
};

// set webhook
function setWebhook() {
  var url = apiUrl + "/setwebhook?url=" + webAppUrl;
  var res = UrlFetchApp.fetch(url).getContentText();
  Logger.log(res);
}


// Function to store user info to Google Sheet
function storeUserInfo(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  sheet.appendRow([userId]);
}

// handle webhook
function doPost(e) {
  try {
    // Check if 'e' object is defined
    if (e && e.postData) {
      var webhookData = JSON.parse(e.postData.contents);
      var from = webhookData.message.from.id;
      var text = webhookData.message.text;

      // Check if the command has an associated function
      if (commandFunctions[text] && typeof commandFunctions[text] === 'function') {
        // Execute the function and get the response
        var response = commandFunctions[text](from);

        // Send the response to Telegram
        var url = apiUrl + "/sendmessage?parse_mode=HTML&chat_id=" + from + "&text=" + encodeURIComponent(response);
        var opts = { "muteHttpExceptions": true };
        UrlFetchApp.fetch(url, opts).getContentText();
      } else {
        Logger.log("Command not found or doesn't have an associated function");
      }
    } else {
      Logger.log("No postData found in the payload");
    }

    // Merge the new code snippet
    var postData = e.postData.contents;
    var payload = JSON.parse(postData);

    if (payload.message && payload.message.from && payload.message.from.id) {
      var userId = payload.message.from.id;
      Logger.log("User ID:", userId);

      // Now you can use the userId for further processing
    } else {
      Logger.log("Invalid payload format");
    }
  } catch (error) {
    Logger.log("Error in doPost: " + error);
  }
}
