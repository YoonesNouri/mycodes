// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbwrCCVRVMQmNqTctIlVQDN-B9RxMTphvCj-tznCVCmLsdigmVC6aWJi3Mz291yb1kr2mw/exec";

// Function to set the webhook
function setWebhook() {
  var response = UrlFetchApp.fetch(url + "/setWebhook?url=" + webAppUrl);
  Logger.log(response.getContentText());
}

// Function to send a message via Telegram
function sendMessage(chatId, text) {
  var response = UrlFetchApp.fetch(url + "/sendMessage?chat_id=" + chatId + "&text=" + text);
  Logger.log(response.getContentText());
}

// Function to store user information in the UsersSheet
function storeUserInfo(from, userName, userSurname, userPhoneNumber) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  sheet.appendRow([new Date(), from, userName, userSurname, userPhoneNumber]);
  Utilities.sleep(2000);
}

// Function to store guest information in the GuestsSheet
function storeGuestInfo(from, guestNationalCode, guestPhoneNumber) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([new Date(), from, guestNationalCode, guestPhoneNumber]);
  Utilities.sleep(2000);
}

// Function to authenticate user by checking if the user ID exists in the UsersSheet and the contract is not expired
function authenticateUser(from) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == from) {
      return true; // User found.
    }
  }
  return false; // User not found.
}

// Function to check if user's contract is not expired
function isContractNotExpired(form) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var currentDate = new Date();

  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == form) {
      var expirationDate = new Date(data[i][4]);

      if (currentDate < expirationDate) {
        return true; // contract is not expired.
      } else {
        return false; // contract is expired.
      }
    }
  }

  return false; // user not found, consider as contract expired
}

function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    var from = contents.message.from.id;

    // Check if the command is "/letsgo"
    if (contents.message.text === "/letsgo") {
      // Check if the user is already registered
      if (authenticateUser(from)) {
        sendMessage(from, "You have already been registered.");

        // Check if the contract is not expired
        if (isContractNotExpired(from)) {
          sendMessage(from, "Please enter your guest national code and phone number in this pattern: 1234567890:09127654321");

        } else {
          sendMessage(from, "Your contract has expired. Please renew your contract.");
        }
      } else {
        // User is not registered, wait for user information and store it
        sendMessage(from, "Invalid user information format. Please enter your name, surname, and phone number in this pattern: name:surname:09127654321");
      }
    } else {
      // Check if the message contains user information in the correct pattern
      var userInfoPattern = /^([a-zA-Z]+):([a-zA-Z]+):(\d{11})$/;
      if (userInfoPattern.test(contents.message.text)) {
        var userInfo = contents.message.text.match(userInfoPattern);
        storeUserInfo(from, userInfo[1], userInfo[2], userInfo[3]);
        sendMessage(from, "Please wait until our operator connects you. More information: https://sadransanat.com/about-us/");
      } else {
        sendMessage(from, "Invalid user information format. Please enter your name, surname, and phone number in this pattern: name:surname:09127654321");
      }
    }
  } catch (error) {
    Logger.log("Error in doPost: " + error);
  }
}
