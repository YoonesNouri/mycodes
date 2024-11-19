// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbyRBM6M5fl94wlzPvDyz_NgzoZ3SRircyihuzalS9gzHIQC5GVrdwCnUuRGDrT--TpG/exec";

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
  // Check if the user ID already exists
  var existingIds = sheet.getRange(1, 1, sheet.getLastRow(), 1).getValues().flat();
  
  if (!existingIds.includes(userId)) {
    sheet.appendRow([userId]);
    
    // Wait for a short time to allow the sheet to be updated before continuing
    Utilities.sleep(2000);  // You can adjust the sleep duration as needed
  }
}

// Function to authenticate user by checking if the user ID is stored in the UsersSheet
function authenticateUser(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var existingIds = sheet.getRange(1, 1, sheet.getLastRow(), 1).getValues().flat();
  return existingIds.includes(userId);
}

// Function to store guest information in the GuestSheet
function storeGuestNationalCode(guestnationalCode) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([guestnationalCode]);
}

function storeGuestArrivalTime(arrivalTime) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([arrivalTime]);
}

// Command functions
var commandFunctions = {
  "/letsgo": function (from) {
    sendMessage(from, "Welcome to the Door Access System! Your user ID is: " + from + " please click /storeUserId to save your information.");
  },
  "/storeUserId": function (from) {
    storeUserId(from);
    sendMessage(from,"User info stored successfully. please click /Authentication to authenticate yourself. ");
  },
  "/Authentication": function (from) {
    var message = authenticateUser(from)
      ? "You have been authenticated. Please enter the national code of the guest then press /storeGuestNationalCode"
      : "Authentication failed. Your ID is not found.";
    sendMessage(from, message);
  },
  "/storeGuestNationalCode": function (from, userResponse) {
  // Trim the value
  var guestnationalCode = userResponse.trim();

  // Store guest national code for later use
  storeGuestNationalCode(guestnationalCode);

  sendMessage(from, guestnationalCode + " was registered successfully. Please enter the arrivalTime of your guest then press /arrivalTime.");
  },
  "/arrivalTime": function (from, userResponse) {
  // Trim the value
  var arrivalTime = userResponse.trim();
  // Store guest arrival time for later use
  storeGuestArrivalTime(arrivalTime);
  sendMessage(from, "The arrival time " + arrivalTime + ", was recorded for your guest's arrival.");
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

