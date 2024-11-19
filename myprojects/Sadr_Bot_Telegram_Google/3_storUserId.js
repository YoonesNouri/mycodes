// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN ;

// to get user ID
function getUserId() {
  var response = UrlFetchApp.fetch(url + "/getUpdates");
  var responseData = JSON.parse(response.getContentText());
  var userId = responseData.result[0].message.from.id;
  Logger.log(userId);
  return userId;
}

// Function to store user information in the UsersSheet
function storeUserInfo() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var userId = getUserId();
  sheet.appendRow([userId]);
}
