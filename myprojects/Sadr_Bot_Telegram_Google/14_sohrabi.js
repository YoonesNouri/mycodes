// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbz9kT2cYmW4JsQwpz7jHy9aIyQFrukBlp5jinulNjDBt_0CoZKIDVM0rgpvSDZ1Nyxw9w/exec";

//! STATES SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS
const States = {
  Unknown: -1,
  Unregistered: 0,
  Registering_FirstNameReq: 1,
  Registering_LastNameReq: 2,
  Registering_MobileReq: 3,
  Registering_WaitForAccept: 4,
  Registered: 5,
  Registered_Expired: 6,
  Registered_InviteGuest_NCReq: 7,
  Registered_InviteGuest_MobileReq: 8,
  Registered_InviteGuest_DateReq: 9,
  Registered_InviteGuest_HourReq: 10,
};

function GetUserState(userId) {
  if (isUserIdAbsentInUsersSheet(userId)) {
    return States.Unregistered;
  } else if (isUserIdExistInUsersSheet(userId)) {
    return States.Registering_FirstNameReq;
  } else if (isUserFirstNameExist(userId)) {
    return States.Registering_LastNameReq;
  } else if (isUserLastNameExist(userId)) {
    return States.Registering_MobileReq;
  } else if (isUserMobileExist(userId)) {
    return States.Registering_WaitForAccept;
  } else if (isUserContractNotExpired(userId)) {
    if (isUserIdExistInGuestsSheet(userId)) {
      return States.Registered_InviteGuest_NCReq;
    } else if (isGuestNationalCodeExist(userId)) {
      return States.Registered_InviteGuest_MobileReq;
    } else if (isGuestMobileExist(userId)) {
      return States.Registered_InviteGuest_DateReq;
    } else if (isGuestDateExist(userId)) {
      return States.Registered_InviteGuest_HourReq;
    }
    return States.Registered;
  } else if (isUserContractExpired(userId)) {
    return States.Registered_Expired;
  }
  return States.Unknown;
}

//! WEB APP FUNCTIONS WEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPPWEBAPP
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
  // A B columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 2; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column C
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
  // A B C columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 3; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column D
    var range = sheet.getRange(rowIndex, 4);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "نام کاربر وارد نشده است.");
  }
}

function storeUserMobile(messageText) {
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
  // A B C D columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 4; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column E
    var range = sheet.getRange(rowIndex, 5);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "نام خانوادگی کاربر وارد نشده است.");
  }
}

//? GUEST #####################################################################################################################################################################
function storeUserIdInGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  // Check if any incomplete row contains the user ID
  var incompleteRowFound = false;
  for (var i = 0; i < data.length; i++) {
    var row = data[i];
    var isComplete = true;
    for (var j = 0; j < 7; j++) {
      if (row[j] === "") {
        isComplete = false;
        break;
      }
    }
    if (row[1] === userId && !isComplete) {
      incompleteRowFound = true;
      break;
    }
  }
  // If no incomplete row found, append a new row
  if (!incompleteRowFound) {
    sheet.appendRow([new Date(), userId]);
  }
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
  // A B columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 2; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column C
    var range = sheet.getRange(rowIndex, 3);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "آیدی کاربر در شیت مهمان ها پیدا نشد.");
  }
}

function storeGuestMobile(messageText) {
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
  // A B C columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 3; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column D
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
  // A B C D columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 4; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column E
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
    if (data[i][1] === userId && data[i][3] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // A B C D E columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 5; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column F
    var range = sheet.getRange(rowIndex, 6);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "تاریخ حضور مهمان وارد نشده است.");
  }
}

function storeGuestISODate(guestISODate) {
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
  // A B C D E F columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 6; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
    }
    // append in column G
    var range = sheet.getRange(rowIndex, 7);
    range.setValue(guestISODate);
  } else {
    sendMessage(userId, "ساعت ورود مهمان وارد نشده است.");
  }
}

//! IS ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
function isUserIdExistInUsersSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId) {
      // Check columns must be empty
      for (var j = 2; j <= 5; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isUserIdAbsentInUsersSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      return false; // If the user ID is found in any row
    }
  }
  return true; // If the loop completes without finding userId
}

function isUserFirstNameExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "") {
      // Check columns must be empty
      for (var j = 3; j <= 5; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isUserLastNameExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "" && data[i][3] !== "") {
      // Check columns must be empty
      for (var j = 4; j <= 5; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isUserMobileExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "" && data[i][3] !== "" && data[i][4] !== "") {
      // Check columns must be empty
      if (data[i][5] !== "") {
        return false; // If any column is not empty, return false
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isUserContractNotExpired(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var currentDate = new Date();
  currentDate.setHours(0, 0, 0, 0); // Set time to midnight for comparison
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      var expirationDate = new Date(data[i][5]);
      expirationDate.setHours(0, 0, 0, 0); // Set time to midnight for comparison
      if (currentDate <= expirationDate) {
        return true; // contract is not expired.
      } else {
        return false; // contract is expired.
      }
    }
  }
}

function isUserContractExpired(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var currentDate = new Date();
  currentDate.setHours(0, 0, 0, 0); // Set time to midnight for comparison
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      var expirationDate = data[i][5];
      expirationDate.setHours(0, 0, 0, 0); // Set time to midnight for comparison
      if (expirationDate !== "") {
        return currentDate >= expirationDate;
      }
    }
  }
  return true;
}

//# GUEST ??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
function isUserIdExistInGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId) {
      // Check columns must be empty
      for (var j = 2; j <= 6; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isUserIdAbsentInGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      return false; // If the user ID is found in any row
    }
  }
  return true; // If the loop completes without finding userId
}

function isGuestNationalCodeExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "") {
      // Check columns must be empty
      for (var j = 3; j <= 6; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isGuestMobileExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "" && data[i][3] !== "") {
      // Check columns must be empty
      for (var j = 4; j <= 6; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isGuestDateExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "" && data[i][3] !== "" && data[i][4] !== "") {
      // Check columns must be empty
      for (var j = 5; j <= 6; j++) {
        if (data[i][j] !== "") {
          return false; // If any column is not empty, return false
        }
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isGuestHourExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    // Check columns must be filled
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "" && data[i][3] !== "" && data[i][4] !== "" && data[i][5] !== "") {
      // Check columns must be empty
      if (data[i][6] !== "") {
        return false; // If any column is not empty, return false
      }
      return true; // If all conditions are met, return true
    }
  }
  return false; // User ID not found or conditions not met
}

function isGuestISODateExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][0] !== "" && data[i][1] === userId && data[i][2] !== "" && data[i][3] !== "" && data[i][4] !== "" && data[i][5] !== "" && data[i][6] !== "") {
      return true; // Guest ISO date found
    }
  }
  return false; // Guest ISO date not found
}

function isValidUserFirstName(Info) {
  var InfoPattern = /^[\u0600-\u06FF\s]+$/u;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidUserLastName(Info) {
  var InfoPattern = /^[\u0600-\u06FF\s]+$/u;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidUserMobile(Info) {
  var InfoPattern = /^(\d{11})+$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidNationalCode(Info) {
  var InfoPattern = /^(\d{10})+$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidGuestMobile(Info) {
  var InfoPattern = /^(\d{11})+$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidDate(Info) {
  var InfoPattern = /^(\d{1,2})\/(\d{1,2})\/(\d{4})+$/;
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
      Logger.log('Error converting solar to Gregorian date:', error);
      sendMessage(userId, 'Error converting solar to Gregorian date:', error);
      return false;
    }
  }
  return false;
}

function isValidHour(Info) {
  var InfoPattern = /^(0?|1?[0-9]|2[0-3])+$/;
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
  // find the row of userId
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // A B C D E F columns filled
  if (rowIndex !== -1) {
    var row = data[rowIndex - 1];
    // Check other columns must be empty
    for (var j = 6; j < row.length; j++) {
      if (row[j] !== "") {
        return;
      }
      var guestDateStr = data[i][4];
      var guestHour = data[i][5];
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
        var guestISODate = combinedDate.toISOString();
        return guestISODate;
      } catch (error) {
        Logger.log('Error in combineAndConvertToISO: ' + error);
        throw new Error('Error in combineAndConvertToISO: ' + error);
      }
    }
    // If userId is not found
    return null;
  }
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

//! INTERACTS WITH BOT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    userId = contents.message.from.id;
    var messageText = contents.message.text;

    // check user is in which state?
    var state = GetUserState(userId);

//* USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER USER 
    if (state === States.Unregistered) {
      if (messageText === "/start") {
        storeUserIdInUsersSheet(userId);
        sendMessage(userId, "لطفا نام خود را به زبان فارسی وارد کنید.");
      } else { sendMessage(userId, "لطفا برای شروع /start را بزنید."); }

    } else if (state === States.Registering_FirstNameReq) {
      if (isValidUserFirstName(messageText)) {
        storeUserFirstName(messageText);
        sendMessage(userId, "نام شما ثبت شد.");
        sendMessage(userId, "لطفا نام خانوادگی خود را به زبان فارسی وارد کنید.");
      } else {
        sendMessage(userId, "مطمئن شوید نام تان را به زبان فارسی وارد کرده اید.");
      }

    } else if (state === States.Registering_LastNameReq) {
      if (isValidUserLastName(messageText)) {
        storeUserLastName(messageText);
        sendMessage(userId, "نام خانوادگی شما ثبت شد.");
        sendMessage(userId, "لطفا شماره تلفن 11 رقمی خود را وارد کنید.");
      } else {
        sendMessage(userId, "مطمئن شوید نام خانوادگی تان را به زبان فارسی وارد کرده اید.");
      }

    } else if (state === States.Registering_MobileReq) {
      if (isValidUserMobile(messageText)) {
        storeUserMobile(messageText);
        sendMessage(userId, "شماره تلفن شما ثبت شد.");
        sendMessage(userId, "لطفا منتظر بمانید تا اپراتور تاریخ قرارداد شما را ثبت کند سپس میتوانید با زدن /mehman اطلاعات مهمان تان را وارد کنید.");
      } else {
        sendMessage(userId, "مطمئن شوید شماره تلفن 11 رقمی وارد کرده اید.");
      }

    } else if (state === States.Registering_WaitForAccept) {
      sendMessage(userId, "اطلاعات تاریخ قراردادتان توسط اپراتور ثبت نشده است. لطفا بعد از ثبت آن توسط اپراتور برای وارد کردن اطلاعات مهمان تان /mehman را بزنید.");

    } else if (state === States.Registered_Expired) {
      sendMessage(userId, "تاریخ قرارداد شما منقضی شده است. لطفا نسبت به تمدید آن اقدام کنید سپس برای وارد کردن اطلاعات مهمان تان /mehman را بزنید.");

//* GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST GUEST 
    } else if (state === States.Registered) {
      if (messageText === "/mehman") {
        storeUserIdInGuestsSheet(userId);
        sendMessage(userId, "لطفا کد ملی 10 رقمی مهمان خود را وارد کنید.");
      } else { sendMessage(userId, "لطفا برای وارد کردن اطلاعات مهمان تان /mehman را بزنید."); }

    } else if (state === States.Registered_InviteGuest_NCReq) {
      if (isValidNationalCode(messageText)) {
        storeGuestNationalCode(messageText);
        sendMessage(userId, "کد ملی مهمان شما ثبت شد.");
        sendMessage(userId, "لطفا شماره تلفن 11 رقمی مهمان تان را وارد کنید.");
      } else { sendMessage(userId, "مطمئن شوید کد ملی 10 رقمی وارد کرده اید."); }

    } else if (state === States.Registered_InviteGuest_MobileReq) {
      if (isValidGuestMobile(messageText)) {
        storeGuestMobile(messageText);
        sendMessage(userId, "شماره تلفن مهمان شما ثبت شد.");
        sendMessage(userId, "لطفا تاریخ حضور مهمان تان را طبق قالب 30/12/1402 (ابتدا روز سپس ماه سپس سال) وارد کنید.");
      } else { sendMessage(userId, "مطمئن شوید شماره تلفن 11 رقمی وارد کرده اید."); }

    } else if (state === States.Registered_InviteGuest_DateReq) {
      if (isValidDate(messageText)) {
        storeGuestDate(messageText);
        sendMessage(userId, "تاریخ حضور مهمان تان ثبت شد.");
        sendMessage(userId, "لطفا ساعت حضور مهمان تان را (0-23) وارد کنید.");
      } else { sendMessage(userId, "مطمئن شوید قالب تاریخ را درست وارد کرده اید"); }

    } else if (state === States.Registered_InviteGuest_HourReq) {
      if (isValidHour(messageText)) {
        storeGuestHour(messageText);
        Utilities.sleep(2000); // دو ثانیه مکث تا خوراک فانکشن های بعدی در شیت بشیند.
        var guestISODate = combineAndConvertToISO(userId);
        storeGuestISODate(guestISODate);
        sendMessage(userId, "ساعت حضور مهمان تان ثبت شد.");
        sendMessage(userId, "ایشان میتوانند با ارائه کارت ملی به دوربین دربازکن نگهبانی وارد مجموعه شوند.");
      } else { sendMessage(userId, "مطمئن شوید عددی از 0 تا 23 وارد کرده اید."); }

    } else if (state === States.Unknown) {
      sendMessage(userId, "خطایی رخ داده است. لطفا بعدا امتحان کنید.");
    }

  } catch (error) {
    sendMessage(userId, "Error in doPost: " + error);
    Logger.log("Error in doPost: " + error);
  }
}
