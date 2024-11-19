// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbxqFHKQwIPosFXYGlUw1Rh3jNMAxQh-fKebugnBgTIqnTbHbpL8t4g3Wz2C6kyEntGV3w/exec";

//! WEB APP FUNCTIONS ----------------------------------------------------------------------------------------------------------------------------------------------------------
function setWebhook() {
  var response = UrlFetchApp.fetch(url + "/setWebhook?url=" + webAppUrl);
  Logger.log(response.getContentText());
}

function deleteWebhook() {
  try {
    var response = UrlFetchApp.fetch(url + "/deleteWebhook");
    Logger.log(response.getContentText());
  } catch (error) {
    Logger.log("Error in deleteWebhook: " + error);
  }
}

function sendMessage(chatId, text) {
  var response = UrlFetchApp.fetch(url + "/sendMessage?chat_id=" + chatId + "&text=" + text);
  Logger.log(response.getContentText());
}

//! STORE FUNCTIONS +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//? USER ######################################################################################################################################################################
function storeUserIdInUsersSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  sheet.appendRow([new Date(), userId]);
}

function storeUserFirstName(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column C
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 3);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "آیدی کاربر در شیت کاربرها پیدا نشد.");
  }
}

function storeUserLastName(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column D
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 4);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "نام کاربر وارد نشده است.");
  }
}

function storeUserPhoneNumber(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column E
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 5);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "نام خانوادگی کاربر وارد نشده است.");
  }
}

//? GUEST #####################################################################################################################################################################
function storeUserIdInGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([new Date(), userId]);
}

function storeGuestNationalCode(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column C
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 3);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "آیدی کاربر در شیت مهمان ها پیدا نشد.");
  }
}

function storeGuestPhoneNumber(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column D
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 4);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "کد ملی مهمان وارد نشده است.");
  }
}

function storeGuestDate(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column E
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 5);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "شماره تلفن مهمان وارد نشده است.");
  }
}

function storeGuestHour(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][4] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column F
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 6);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "تاریخ حضور مهمان وارد نشده است.");
  }
}

function storeGuestArrivalDate(guestArrivalDate) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column G
  if (rowIndex !== -1) {
    var range = sheet.getRange(rowIndex, 7);
    range.setValue(guestArrivalDate);
  } else {
    sendMessage(userId, "ساعت ورود مهمان وارد نشده است.");
  }
}

//* /mehmanPlus برای مهمان دوم به بعد*******************************************************************************************************************************************
function storeUserIdInLastRowOfGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var lastRow = sheet.getLastRow();
  if (lastRow === 0) {
    lastRow = 1;
  }
  var lastRowRange = sheet.getRange(lastRow + 1, 1, 1, 2);
  lastRowRange.setValues([[new Date(), userId]]);
}

function storeGuestNationalCodePlus(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // Find the last occurrence of userId and check if the seventh column is empty
  var lastRow = -1;
  for (var i = data.length - 1; i >= 0; i--) {
    if (data[i][1] === userId && data[i][6] === "") {
      lastRow = i + 1;
      break;
    }
  }
  // If a valid lastRow is found, update the information
  if (lastRow !== -1) {
    var range = sheet.getRange(lastRow, 3);
    range.setValue(messageText);
  }
}

function storeGuestPhoneNumberPlus(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // Find the last occurrence of userId and check if the seventh column is empty
  var lastRow = -1;
  for (var i = data.length - 1; i >= 0; i--) {
    if (data[i][1] === userId && data[i][6] === "") {
      lastRow = i + 1;
      break;
    }
  }
  // If a valid lastRow is found, update the information
  if (lastRow !== -1) {
    var range = sheet.getRange(lastRow, 4);
    range.setValue(messageText);
  }
}

function storeGuestDatePlus(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // Find the last occurrence of userId and check if the seventh column is empty
  var lastRow = -1;
  for (var i = data.length - 1; i >= 0; i--) {
    if (data[i][1] === userId && data[i][6] === "") {
      lastRow = i + 1;
      break;
    }
  }
  // If a valid lastRow is found, update the information
  if (lastRow !== -1) {
    var range = sheet.getRange(lastRow, 5);
    range.setValue(messageText);
  }
}

function storeGuestHourPlus(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // Find the last occurrence of userId and check if the seventh column is empty
  var lastRow = -1;
  for (var i = data.length - 1; i >= 0; i--) {
    if (data[i][1] === userId && data[i][6] === "") {
      lastRow = i + 1;
      break;
    }
  }
  // If a valid lastRow is found, update the information
  if (lastRow !== -1) {
    var range = sheet.getRange(lastRow, 6);
    range.setValue(messageText);
  }
}

function storeGuestArrivalDatePlus(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // Find the last occurrence of userId and check if the seventh column is empty
  var lastRow = -1;
  for (var i = data.length - 1; i >= 0; i--) {
    if (data[i][1] === userId && data[i][6] === "") {
      lastRow = i + 1;
      break;
    }
  }
  // If a valid lastRow is found, update the information
  if (lastRow !== -1) {
    var range = sheet.getRange(lastRow, 7);
    range.setValue(messageText);
  }
}

//! IS ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
function isUserIdExistInUsersSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == userId) {
      return true; // User ID found in users sheet
    }
  }
  return false; // User ID not found in users sheet
}

function isUserFirstNameExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      return true; // User First Name found
    }
  }
  return false; // User First Name not found
}

function isUserLastNameExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      return true; // User Last Name found
    }
  }
  return false; // User last Name not found
}

function isUserPhoneNumberExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][4] !== "") {
      return true; // User Phone Number found
    }
  }
  return false; // User Phone Number not found
}

function isUserContractNotExpired(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var currentDate = new Date();
  currentDate.setHours(0, 0, 0, 0); // Set time to midnight for comparison
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == userId) {
      var expirationDate = new Date(data[i][5]);
      expirationDate.setHours(0, 0, 0, 0); // Set time to midnight for comparison
      if (currentDate <= expirationDate) {
        return true; // contract is not expired.
      } else {
        return false; // contract is expired.
      }
    }
  }
  sendMessage(userId, "User not found");
  return false; // user not found, consider as contract expired.
}

function isUserIdExistInGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      return true; // User ID found in guests sheet
    }
  }
  return false; // User ID not found in guests sheet
}

function isGuestNationalCodeExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      return true; // Guest national code found
    }
  }
  return false; // Guest national code not found
}

function isGuestPhoneNumberExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      return true; // Guest Phone Number found
    }
  }
  return false; // Guest Phone Number not found
}

function isGuestDateExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][4] !== "") {
      return true; // Guest date found
    }
  }
  return false; // Guest date not found
}

function isGuestHourExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][4] !== "") {
      return true; // Guest hour found
    }
  }
  return false; // Guest hour not found
}

function isGuestArrivalDateExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][6] !== "") {
      return true; // Guest arrival date found
    }
  }
  return false; // Guest arrival date not found
}

function isValidUserFirstName(Info) {
  var InfoPattern = /^نام:([\u0600-\u06FFA-Za-z]+)$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidUserLastName(Info) {
  var InfoPattern = /^نام خانوادگی:([\u0600-\u06FFA-Za-z]+)$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidUserPhoneNumber(Info) {
  var InfoPattern = /^تلفن کاربر:(\d{11})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidNationalCode(Info) {
  var InfoPattern = /^کد ملی:(\d{10})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidGuestPhoneNumber(Info) {
  var InfoPattern = /^تلفن مهمان:(\d{11})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidDate(Info) {
  var InfoPattern = /^تاریخ:(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
  var match = Info.match(InfoPattern);
  if (match !== null) {
    var solarYear = Number(match[3]);
    var solarMonth = Number(match[2]);
    var solarDay = Number(match[1]);
    try {
      // Convert solar date to Gregorian date using the Converter class
      var gregorianDate = Converter.persianToGregorian(solarYear, solarMonth, solarDay);
      var gregorianYear = gregorianDate[0];
      var gregorianMonth = gregorianDate[1];
      var gregorianDay = gregorianDate[2];
      // Create a Date object with the converted Gregorian date
      var inputDate = new Date(gregorianYear, gregorianMonth - 1, gregorianDay);
      // Get the current date
      var currentDate = new Date();
      // Get the expiration date from the 6th column of the users sheet
      var expirationDate = getUserExpirationDate(userId);
      // Compare the input date with the current date and the expiration date
      return inputDate >= currentDate && inputDate <= expirationDate;
    } catch (error) {
      // Handle conversion errors
      console.error('Error converting solar to Gregorian date:', error);
      Logger.logError('Error converting solar to Gregorian date:', error);
      sendMessage(userId, 'Error converting solar to Gregorian date:', error);
      return false;
    }
  }
  return false;
}

function isValidHour(Info) {
  var InfoPattern = /^ساعت:(0?|1?[0-9]|2[0-3])$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

//* /mehmanPlus برای مهمان دوم به بعد*******************************************************************************************************************************************
function isValidNationalCodePlus(Info) {
  var InfoPattern = /^کد ملی جدید:(\d{10})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidGuestPhoneNumberPlus(Info) {
  var InfoPattern = /^تلفن مهمان جدید:(\d{11})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidDatePlus(Info) {
  var InfoPattern = /^تاریخ جدید:(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
  var match = Info.match(InfoPattern);
  if (match !== null) {
    var solarYear = Number(match[3]);
    var solarMonth = Number(match[2]);
    var solarDay = Number(match[1]);
    try {
      // Convert solar date to Gregorian date using the Converter class
      var gregorianDate = Converter.persianToGregorian(solarYear, solarMonth, solarDay);
      var gregorianYear = gregorianDate[0];
      var gregorianMonth = gregorianDate[1];
      var gregorianDay = gregorianDate[2];
      // Create a Date object with the converted Gregorian date
      var inputDate = new Date(gregorianYear, gregorianMonth - 1, gregorianDay);
      // Get the current date
      var currentDate = new Date();
      // Get the expiration date from the 6th column of the users sheet
      var expirationDate = getUserExpirationDate(userId);
      // Compare the input date with the current date and the expiration date
      return inputDate >= currentDate && inputDate <= expirationDate;
    } catch (error) {
      // Handle conversion errors
      console.error('Error converting solar to Gregorian date:', error);
      Logger.logError('Error converting solar to Gregorian date:', error);
      sendMessage(userId, 'Error converting solar to Gregorian date:', error);
      return false;
    }
  }
  return false;
}

function isValidHourPlus(Info) {
  var InfoPattern = /^ساعت جدید:(0?|1?[0-9]|2[0-3])$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

//! OTHER FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getUserExpirationDate(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][5] !== "") {
      return new Date(data[i][5]);
    }
  }
  return null; // Return null after checking all rows
}

function combineAndConvertToISO(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      var guestDateStr = data[i][4];  // Assuming guest date is in the fifth column (index 4)
      var guestHour = data[i][5];   // Assuming guest time is in the sixth column (index 5)
      try {
        // Parse guestDateStr into a JavaScript Date object
        var guestDateObj = new Date(guestDateStr);
        // Extract components from the Date object
        var solarYear = guestDateObj.getFullYear();
        var solarMonth = guestDateObj.getMonth() + 1; // Months are zero-based, so add 1
        var solarDay = guestDateObj.getDate();
        // Parse guestHour as an integer
        var hour = parseInt(guestHour, 10);
        // Check if date components are valid
        if (isNaN(solarDay) || isNaN(solarMonth) || isNaN(solarYear) || isNaN(hour)) {
          throw new Error('Invalid date components');
        }
        // Convert the solar date to Gregorian date
        var gregorianDate = Converter.persianToGregorian(solarYear, solarDay, solarMonth);
        var gregorianYear = gregorianDate[0];
        var gregorianMonth = gregorianDate[1];
        var gregorianDay = gregorianDate[2];
        // Create a JavaScript Date object with the components
        var combinedDate = new Date(gregorianYear, gregorianMonth - 1, gregorianDay, hour, 0, 0, 0);
        // Check if the Date object is valid
        if (isNaN(combinedDate.getTime())) {
          throw new Error('Invalid Date object');
        }
        // Convert the Date object to ISO format
        var guestArrivalDate = combinedDate.toISOString();
        return guestArrivalDate;
      } catch (error) {
        Logger.log('Error in combineAndConvertToISO: ' + error);
        throw new Error('Error in combineAndConvertToISO: ' + error);
      }
    }
  }
  // If userId is not found, you may want to handle this case appropriately
  return null;
}

//* /mehmanPlus برای مهمان دوم به بعد*********************************************************************************************************************************************
function combineAndConvertToISOPlus(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var lastRow = -1;
  for (var i = data.length - 1; i >= 0; i--) {
    if (data[i][1] === userId && data[i][6] === "") {
      lastRow = i + 1;
      break;
    }
  }
  if (lastRow !== -1) {
    var guestDateStr = data[lastRow - 1][4];  // Assuming guest date is in the fifth column (index 4)
    var guestHour = data[lastRow - 1][5];   // Assuming guest time is in the sixth column (index 5)
    try {
      // Parse guestDateStr into a JavaScript Date object
      var guestDateObj = new Date(guestDateStr);
      // Extract components from the Date object
      var solarYear = guestDateObj.getFullYear();
      var solarMonth = guestDateObj.getMonth() + 1; // Months are zero-based, so add 1
      var solarDay = guestDateObj.getDate();
      // Parse guestHour as an integer
      var hour = parseInt(guestHour, 10);
      // Check if date components are valid
      if (isNaN(solarDay) || isNaN(solarMonth) || isNaN(solarYear) || isNaN(hour)) {
        throw new Error('Invalid date components');
      }
      // Convert the solar date to Gregorian date
      var gregorianDate = Converter.persianToGregorian(solarYear, solarDay, solarMonth);
      var gregorianYear = gregorianDate[0];
      var gregorianMonth = gregorianDate[1];
      var gregorianDay = gregorianDate[2];
      // Create a JavaScript Date object with the components
      var combinedDate = new Date(gregorianYear, gregorianMonth - 1, gregorianDay, hour, 0, 0, 0);
      // Check if the Date object is valid
      if (isNaN(combinedDate.getTime())) {
        throw new Error('Invalid Date object');
      }
      // Convert the Date object to ISO format
      var guestArrivalDate = combinedDate.toISOString();
      return guestArrivalDate;
    } catch (error) {
      Logger.log('Error in combineAndConvertToISOPlus: ' + error);
      throw new Error('Error in combineAndConvertToISOPlus: ' + error);
    }
  }
  // If userId is not found, you may want to handle this case appropriately
  return null;
}

//! DATE CONVERTER S2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2GS2G
// Constants
var GREGORIAN_EPOCH = 1721425.5;
var PERSIAN_EPOCH = 1948320.5;

// Helper function for modulo operation
function mod(a, b) {
  return ((a % b) + b) % b;
}

// Converter class
function Converter() { }

// Leap Gregorian
Converter.leapGregorian = function (year) {
  return ((year % 4) === 0) && (!((year % 100 === 0) && (year % 400 !== 0)));
};

// Gregorian to Julian
Converter.gregorianToJulian = function (year, month, day) {
  var pad = 0;
  if (month <= 2) {
    pad = 0;
  } else if (Converter.leapGregorian(year)) {
    pad = -1;
  } else {
    pad = -2;
  }
  return (GREGORIAN_EPOCH - 1) +
    (365 * (year - 1)) +
    Math.floor((year - 1) / 4) +
    (-Math.floor((year - 1) / 100)) +
    Math.floor((year - 1) / 400) +
    Math.floor((((367 * month) - 362) / 12) + (pad + day));
};

// Julian to Gregorian
Converter.julianToGregorian = function (jd) {
  var wjd = Math.floor(jd - 0.5) + 0.5;
  var depoch = wjd - GREGORIAN_EPOCH;
  var quadricent = Math.floor(depoch / 146097);
  var dqc = mod(depoch, 146097);
  var cent = Math.floor(dqc / 36524);
  var dcent = mod(dqc, 36524);
  var quad = Math.floor(dcent / 1461);
  var dquad = mod(dcent, 1461);
  var yindex = Math.floor(dquad / 365);
  var year = (quadricent * 400) + (cent * 100) + (quad * 4) + yindex;
  if (!((cent === 4) || (yindex === 4))) {
    year += 1;
  }
  var yearday = wjd - Converter.gregorianToJulian(year, 1, 1);
  var leapadj;
  if (wjd < Converter.gregorianToJulian(year, 3, 1)) {
    leapadj = 0;
  } else if (Converter.leapGregorian(year)) {
    leapadj = 1;
  } else {
    leapadj = 2;
  }
  var month = Math.floor((((yearday + leapadj) * 12) + 373) / 367);
  var day = (wjd - Converter.gregorianToJulian(year, month, 1)) + 1;

  return [year, month, day];
};

// Leap Persian
Converter.leapPersian = function (year) {
  if (year === 1403) return true; // Well, algorithms are not perfect \o/
  return (
    (((((year - ((year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816
  ) < 682;
};

// Persian to Julian
Converter.persianToJulian = function (year, month, day) {
  var epbase = year - ((year >= 0) ? 474 : 473);
  var epyear = 474 + mod(epbase, 2820);
  return day +
    ((month <= 7)
      ? ((month - 1) * 31)
      : (((month - 1) * 30) + 6)
    ) +
    Math.floor(((epyear * 682) - 110) / 2816) +
    ((epyear - 1) * 365) +
    (Math.floor(epbase / 2820) * 1029983) + (PERSIAN_EPOCH - 1);
};

// Julian to Persian
Converter.julianToPersian = function (jd) {
  var njd = Math.floor(jd) + 0.5;
  var depoch = njd - Converter.persianToJulian(475, 1, 1);
  var cycle = Math.floor(depoch / 1029983);
  var cyear = mod(depoch, 1029983);
  var ycycle;
  if (cyear === 1029982) {
    ycycle = 2820;
  } else {
    var aux1 = Math.floor(cyear / 366);
    var aux2 = mod(cyear, 366);
    ycycle = Math.floor(((2134 * aux1) + (2816 * aux2) + 2815) / 1028522)
      + aux1 + 1;
  }
  var year = ycycle + (2820 * cycle) + 474;
  if (year <= 0) {
    year -= 1;
  }
  var yday = (njd - Converter.persianToJulian(year, 1, 1)) + 1;
  var month = (yday <= 186) ? Math.ceil(yday / 31) : Math.ceil((yday - 6) / 30);
  var day = (njd - Converter.persianToJulian(year, month, 1)) + 1;
  return [year, month, day];
};

// Persian to Gregorian
Converter.persianToGregorian = function (year, month, day) {
  var julian = Converter.persianToJulian(year, month, day);
  return Converter.julianToGregorian(julian);
};

// Gregorian to Persian
Converter.gregorianToPersian = function (year, month, day) {
  var julian = Converter.gregorianToJulian(year, month, day);
  return Converter.julianToPersian(julian);
};

//!INTERACTS WITH BOT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    userId = contents.message.from.id;
    var messageText = contents.message.text;

    //? USER @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    if (messageText === "/start") {
      if (!isUserIdExistInUsersSheet(userId)) {
        storeUserIdInUsersSheet(userId);
        sendMessage(userId, " لطفا نام خود را وارد با این الگو بدون فاصله وارد کنید نام:محمد");
      }
    }

    if (isValidUserFirstName(messageText)) {
      var inf = messageText.replace("نام:", "").trim();
      storeUserFirstName(inf);
      sendMessage(userId, " نام شما ثبت شد. لطفا نام خانوادگی خود را با این الگو بدون فاصله وارد کنید نام خانوادگی:محمدی");
    }

    if (isValidUserLastName(messageText)) {
      var inf = messageText.replace("نام خانوادگی:", "").trim();
      storeUserLastName(inf);
      sendMessage(userId, " نام خانوادگی شما ثبت شد. لطفا شماره تلفن خود را با این الگو بدون فاصله وارد کنید تلفن کاربر:09121234567");
    }

    if (isValidUserPhoneNumber(messageText)) {
      var inf = messageText.replace("تلفن کاربر:", "").trim();
      storeUserPhoneNumber(inf);
      sendMessage(userId, "شماره تلفن شما ثبت شد. لطفا منتظر تماس اپراتور بمانید تا ابتدا قرارداد شما را بررسی کند سپس با ارسال فرمان /mehman ، اطلاعات مهمان خود را وارد کنید. ");
    }

    //? GUEST @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    if (messageText === "/mehman") {
      if (!isUserIdExistInGuestsSheet(userId)) {
        if (isUserContractNotExpired(userId)) {
          storeUserIdInGuestsSheet(userId);
          sendMessage(userId, "لطفا کد ملی مهمان خود را وارد با این الگو بدون فاصله وارد کنید کد ملی:1234567890");
        } else {
          sendMessage(userId, "شما یا ثبت نام نکرده اید یا قراردادتان منقضی شده است لطفا با زدن /start نسبت ثبتنام اقدام کنید و یا قراردادتان را تمدید کنید سپس فرمان /mehman را ارسال کنید.");
        }
      } else {
        // Check if the guest is registered and get the relevant information
        if (isGuestNationalCodeExist(userId)) {
          // Get the relevant information from the GuestsSheet
          var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
          var data = sheet.getDataRange().getValues();
          var messageSent = false;
          for (var i = 0; i < data.length; i++) {
            if (data[i][1] === userId && data[i][6] !== "") {
              var guestNationalCode = data[i][2];
              var guestDate = data[i][4];
              sendMessage(userId, "جواز تردد مهمان با کد ملی " + guestNationalCode + " در تاریخ " + guestDate + "  قبلا یک بار ثبت شده است اگر میخواهید جواز تردد مهمان دیگری را اخذ کنید فرمان /mehmanPlus را ارسال کنید.");
              messageSent = true;
            }
          }
          // Check if no message has been sent (optional)
          if (!messageSent) {
            sendMessage(userId, "No matching record found."); // You can customize this message
          }
        } else {
          sendMessage(userId, "لطفا کد ملی مهمان خود را وارد با این الگو بدون فاصله وارد کنید کد ملی:1234567890");
        }
      }
    }

    if (isValidNationalCode(messageText)) {
      var inf = messageText.replace("کد ملی:", "").trim();
      storeGuestNationalCode(inf);
      sendMessage(userId, "کد ملی مهمان شما ثبت شد. لطفا شماره تلفن مهمان خود را با این الگو بدون فاصله وارد کنید تلفن مهمان:09121234567 ");
    }

    if (isValidGuestPhoneNumber(messageText)) {
      var inf = messageText.replace("تلفن مهمان:", "").trim();
      storeGuestPhoneNumber(inf);
      sendMessage(userId, "تلفن مهمان شما ثبت شد. لطفا تاریخ حضور مهمان خود را به این ترتیب وارد کنید: تاریخ:روز/ماه/سال که بعد از تایپ کردن به این صورت در می آید: تاریخ:30/12/1404");
    }

    if (isValidDate(messageText)) {
      var inf = messageText.replace("تاریخ:", "").trim();
      storeGuestDate(inf);
      sendMessage(userId, "تاریخ حضور مهمان شما ثبت شد. لطفا ساعت ورود مهمان خود را بااین الگو بدون فاصله وارد کنید ساعت:0-23");
    }

    if (isValidHour(messageText)) {
      var inf = messageText.replace("ساعت:", "").trim();
      storeGuestHour(inf);
      // Get guest's ISO date using the function
      var guestArrivalDate = combineAndConvertToISO(userId);
      // Store guest arrival date using the function
      storeGuestArrivalDate(guestArrivalDate);
      sendMessage(userId, "ساعت ورود مهمان شما ثبت شد. ایشان میتوانند با در دست داشتن کارت ملی از درب نگهبانی وارد مجموعه شوند.");
    }

    //* /mehmanPlus برای مهمان دوم به بعد********************************************************************************************************************************************
    if (messageText === "/mehmanPlus") {
      if (isUserContractNotExpired(userId)) {
        storeUserIdInLastRowOfGuestsSheet(userId);
        sendMessage(userId, "لطفا کد ملی مهمان خود را وارد با این الگو بدون فاصله وارد کنید کد ملی جدید:1234567890");
      } else {
        sendMessage(userId, "شما یا ثبت نام نکرده اید یا قراردادتان منقضی شده است لطفا با زدن /start نسبت ثبتنام اقدام کنید و یا قراردادتان را تمدید کنید سپس فرمان /mehman را ارسال کنید.");
      }
    }

    if (isValidNationalCodePlus(messageText)) {
      var inf = messageText.replace("کد ملی جدید:", "").trim();
      storeGuestNationalCodePlus(inf);
      sendMessage(userId, "کد ملی مهمان شما ثبت شد. لطفا شماره تلفن مهمان خود را با این الگو بدون فاصله وارد کنید تلفن مهمان جدید:09121234567 ");
    }

    if (isValidGuestPhoneNumberPlus(messageText)) {
      var inf = messageText.replace("تلفن مهمان جدید:", "").trim();
      storeGuestPhoneNumberPlus(inf);
      sendMessage(userId, "تلفن مهمان شما ثبت شد. لطفا تاریخ حضور مهمان خود را به این ترتیب وارد کنید: تاریخ جدید:روز/ماه/سال که بعد از تایپ کردن به این صورت در می آید: تاریخ جدید:30/12/1404");
    }

    if (isValidDatePlus(messageText)) {
      var inf = messageText.replace("تاریخ جدید:", "").trim();
      storeGuestDatePlus(inf);
      sendMessage(userId, "تاریخ حضور مهمان شما ثبت شد. لطفا ساعت ورود مهمان خود را بااین الگو بدون فاصله وارد کنید ساعت جدید:0-23");
    }

    if (isValidHourPlus(messageText)) {
      var inf = messageText.replace("ساعت جدید:", "").trim();
      storeGuestHourPlus(inf);
      // Get guest's ISO date using the function
      var guestArrivalDate = combineAndConvertToISOPlus(userId);
      // Store guest arrival date using the function
      storeGuestArrivalDatePlus(guestArrivalDate);
      sendMessage(userId, "ساعت ورود مهمان شما ثبت شد. ایشان میتوانند با در دست داشتن کارت ملی از درب نگهبانی وارد مجموعه شوند.");
    }

  } catch (error) {
    sendMessage(userId, "Error in doPost: " + error);
    Logger.log("Error in doPost: " + error);
  }
}
