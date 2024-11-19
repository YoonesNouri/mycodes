// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN ;

// an update before getUserId is neccessary
function getUpdates() {
  var response = UrlFetchApp.fetch(url + "/getUpdates");
  Logger.log(response.getContentText());
}

// to get user ID
function getUserId() {
  var response = UrlFetchApp.fetch(url + "/getUpdates");
  var responseData = JSON.parse(response.getContentText());
  var userId = responseData.result[0].message.from.id;
  Logger.log(userId);
  return userId;
}

// برای مواقعی که وب هوک از پیش فعال است و برای آپدیت باید دیلیت شود در کد نوشنه شد
function deleteWebhook() {
  try {
    var response = UrlFetchApp.fetch(url + "/deleteWebhook");
    Logger.log(response.getContentText());
  } catch (error) {
    Logger.log("Error in deleteWebhook: " + error);
  }
}

// Function to store user information in the UsersSheet
function storeUserInfo() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var userId = getUserId();
  sheet.appendRow([userId]);
}
