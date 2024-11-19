// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbyRBM6M5fl94wlzPvDyz_NgzoZ3SRircyihuzalS9gzHIQC5GVrdwCnUuRGDrT--TpG/exec";

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
  // Check if the user ID already exists
  var existingIds = sheet.getRange(1, 1, sheet.getLastRow(), 1).getValues().flat();

  if (!existingIds.includes(userId)) {
    sheet.appendRow([userId]);

    Utilities.sleep(2000);
  }
}

// Function to authenticate user by checking if the user ID is stored in the UsersSheet
function authenticateUser(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var existingIds = sheet.getRange(1, 1, sheet.getLastRow(), 1).getValues().flat();
  return existingIds.includes(userId);
}

// Function to store guestNationalCode temporarily
function storeGuestNationalCodeTemp(guestNationalCodeInput) {
  guestNationalCode = guestNationalCodeInput;
}

// Function to store arrivalTime temporarily
function storeGuestArrivalTimeTemp(arrivalTimeInput) {
  arrivalTime = arrivalTimeInput;
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
    sendMessage(from, "User info stored successfully. please click /Authentication to authenticate yourself. ");
  },
  "/Authentication": function (from) {
    var message = authenticateUser(from)
      ? "You have been authenticated. Please enter the national code of the guest then press /getGuestNationalCode"
      : "Authentication failed. Your ID is not found.";
    sendMessage(from, message);
  },
  "/getGuestNationalCode": function (from, userResponse) {
    // Trim the value
    var guestNationalCodeInput = userResponse.trim();

    // Call the function to store GuestNationalCode
    storeGuestNationalCodeTemp(guestNationalCodeInput);

    sendMessage(from, guestNationalCodeInput + " was registered successfully. Please enter the arrivalTime of your guest then press /getArrivalTime.");
  },
  "/getArrivalTime": function (from, userResponse) {
    // Trim the value
    var arrivalTimeInput = userResponse.trim();

    // Call the function to store GuestArrivalTime
    storeGuestArrivalTimeTemp(arrivalTimeInput);

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
