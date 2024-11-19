// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbznys3jorhFztXUJ3Sl9CevRhMRK9zAILrfeYHtcZ-unMLMvQsUAcxq_kEMh8pVnBVn5w/exec";

// Global variables to store guest information temporarily
var guestNationalCode;
var arrivalTime;

// Function to send a message via Telegram
function sendMessage(chatId, text) {
  var response = UrlFetchApp.fetch(url + "/sendMessage?chat_id=" + chatId + "&text=" + text);
  Logger.log(response.getContentText());
}

// Function to set the webhook
function setWebhook() {
  var response = UrlFetchApp.fetch(url + "/setWebhook?url=" + webAppUrl);
  Logger.log(response.getContentText());
}

// Function to delete the webhook
function deleteWebhook() {
  try {
    var response = UrlFetchApp.fetch(url + "/deleteWebhook");
    Logger.log(response.getContentText());
  } catch (error) {
    Logger.log("Error in deleteWebhook: " + error);
  }
}

// Function to store user information in the UsersSheet
function storeUserId(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  // Check if the sheet is empty
  if (sheet.getLastRow() === 0) {
    sheet.appendRow([userId]);
    Utilities.sleep(2000);
    sendMessage(userId, "User info stored successfully. Please click /Authentication to authenticate yourself.");
    return;
  }
  // Check if the user ID already exists
  var existingIds = sheet.getRange(1, 1, sheet.getLastRow(), 1).getValues().flat();
  if (!existingIds.includes(userId)) {
    sheet.appendRow([userId]);
    Utilities.sleep(2000);
    sendMessage(userId, "User info stored successfully. Please click /Authentication to authenticate yourself.");
  } else {
    sendMessage(userId, "User ID already exists. You are already authenticated.");
  }
}

// Function to authenticate user by checking if the user ID exists in the main sheet
function authenticateUser(userId) {
  var mainSpreadsheet = SpreadsheetApp.openById(SHEET_ID);
  var mainSheet = mainSpreadsheet.getSheetByName(USERS_SHEET_NAME);
  var data = mainSheet.getDataRange().getValues();
  
  for (var i = 0; i < data.length; i++) {
    for (var j = 0; j < data[i].length; j++) {
      if (data[i][j] !== null && data[i][j].toString() === userId.toString()) {
        return true; // User found
      }
    }
  }
  
  return false; // User not found
}

// Function to store guest information in the GuestsSheet
function storeGuestInfo() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([guestNationalCode, arrivalTime]);
}

// Command functions
var commandFunctions = {
  "/letsgo": function (from) {
    sendMessage(from, "Welcome to the Door Access System! Your user ID is: " + from + " please click /storeUserId to save your information.");
  },
  "/storeUserId": function (from) {
    storeUserId(from);
  },
  "/Authentication": function (from) {
    var userId = from.toString(); // Convert to string in case it's not
    var userFound = authenticateUser(userId);
    if (userFound) {
      sendMessage(from, "User found: User ID: " + userId + " Please enter the national code of your guest then press /getGuestNationalCode");
    } else {
      sendMessage(from, "User not found: User ID: " + userId);
    }
  },
  "/getGuestNationalCode": function (from, userResponse) {
    // Trim the value
    var guestNationalCodeInput = userResponse.trim();
    // Set guestNationalCode directly
    guestNationalCode = guestNationalCodeInput;
    sendMessage(from, guestNationalCodeInput + " was registered successfully. Please enter the arrivalTime of your guest then press /getArrivalTime.");
  },
  "/getArrivalTime": function (from, userResponse) {
    // Trim the value
    var arrivalTimeInput = userResponse.trim();
    // Set arrivalTime directly
    arrivalTime = arrivalTimeInput;
    sendMessage(from, "The arrival time " + arrivalTimeInput + ", was recorded for your guest's arrival. To complete the process, click /storeGuestInfo.");
  },
  "/storeGuestInfo": function (from) {
    // Call the function to store guest information in the GuestsSheet
    storeGuestInfo();
    sendMessage(from, "Your guest with national code " + guestNationalCode + "can enter the security gate after" + arrivalTime);
  },
};

// Handler for HTTP POST requests
function doPost(e) {
  try {
    var contents = JSON.parse(e.postData ? e.postData.contents : "{}");
    Logger.log(JSON.stringify(contents, null, 4));

    var chatId = contents.message.from.id;
    var messageText = contents.message.text;

    // Process the command
    for (var command in commandFunctions) {
      if (messageText === command) {
        commandFunctions[command](chatId);
      }
    }
  } catch (error) {
    Logger.log("Error in doPost: " + error);
  }
}
