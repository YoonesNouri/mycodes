// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbwDCtZqbvmqHFvMJU7NW3hYF_0eNkHnAgsUAgyEHQBcUFxMJocRUrxwMOJ862iuWsnMjw/exec";

function setWebhook() {
  var response = UrlFetchApp.fetch(url + "/setWebhook?url=" + webAppUrl);
  Logger.log(response.getContentText());
}

function sendMessage(chatId, text) {
  var response = UrlFetchApp.fetch(url + "/sendMessage?chat_id=" + chatId + "&text=" + text);
  Logger.log(response.getContentText());
}

function storeUserInfo(userId, userName, userSurname, userPhoneNumber) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  sheet.appendRow([new Date(), userId, userName, userSurname, userPhoneNumber, ""]);
}

function storeGuestInfo(userId, guestNationalCode, guestPhoneNumber) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([new Date(), userId, guestNationalCode, guestPhoneNumber]);
  Utilities.sleep(2000);
}

function authenticateUser(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == userId) {
      return true; // User found.
    }
  }
  return false; // User not found
}

function isContractNotExpired(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var currentDate = new Date();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == userId) {
      var expirationDate = new Date(data[i][5]);
      if (currentDate < expirationDate) {
        return true; // contract is not expired.
      } else {
        return false; // contract is expired.
      }
    }
  }
  sendMessage(userId, "User not found");
  return false; // user not found, consider as contract expired.
}

function isGuestNationalCodeExist(guestNationalCode) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][2] == guestNationalCode) {
      return true; // guest national code already exists.
    }
  }
  return false; // guest national code not found.
}

function isValidUserInfo(userInfo) {
  var userInfoPattern = /^([a-zA-Z]+):([a-zA-Z]+):(\d{11})$/;
  return userInfoPattern.test(userInfo);
}

function isValidGuestInfo(guestInfo) {
  var guestInfoPattern = /^(\d{10}):(\d{11})$/;
  return guestInfoPattern.test(guestInfo);
}

// Constants and Configuration
// ... (unchanged)

function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    var userId = contents.message.from.id;

    // Check if the command is "/start"
    if (contents.message.text === "/start") {
      // Check if the user is already registered
      if (authenticateUser(userId)) {
        sendMessage(userId, "You have already been registered.");
        // Check if the contract is not expired
        if (isContractNotExpired(userId)) {
          sendMessage(userId, "Please enter your guest 10-digit national code and 11-digit phone number according to this pattern: 1234567890:09127654321");
        } else {
          sendMessage(userId, "Your contract has expired. Please renew your contract.");
        }
      } else {
        // User not registered, wait for user input
        sendMessage(userId, "You are not registered yet. If you want to register, please enter your firstName, lastName, and 11-digit phone number according to this pattern: firstName:lastName:09127654321 and wait until our operator connects you. More information: https://sadrun.ir/");
      }
    } else {
      // Handle guest information when the command is not "/start"
      if (isValidGuestInfo(contents.message.text)) {
        var guestInfo = contents.message.text.match(/^(\d{10}):(\d{11})$/);
        // Check if the guest national code already exists
        if (isGuestNationalCodeExist(guestInfo[1])) {
          sendMessage(userId, "Guest national code already exists.");
        } else {
          // Store guest information
          storeGuestInfo(userId, guestInfo[1], guestInfo[2]);
          sendMessage(userId, "Your guest information was registered successfully");
        }
      } else if (isValidUserInfo(contents.message.text)) {
        var userInfo = contents.message.text.match(/^([a-zA-Z]+):([a-zA-Z]+):(\d{11})$/);
        // Check if the phone number has exactly 11 digits
        if (userInfo && userInfo[3].length === 11) {
          // Store user information
          storeUserInfo(userId, userInfo[1], userInfo[2], userInfo[3]);
          sendMessage(userId, "Your information was registered successfully. Please wait until our operator connects you. More information: https://sadrun.ir/");
        } else {
          sendMessage(userId, "Invalid user information format. Please make sure the phone number has exactly 11 digits.");
        }
      } else {
        // Invalid information format
        sendMessage(userId, "Invalid guest information format. Please follow This pattern => 1234567890:09127654321");
      }
    }
  } catch (error) {
    sendMessage(userId, "Error: " + error);
    Logger.log("Error in doPost: " + error);
  }
}

