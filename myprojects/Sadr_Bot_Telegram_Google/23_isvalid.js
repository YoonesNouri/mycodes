// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbyholt-JyJ82JwvyaDbNSFlRGQ6uZZbw_zCGcSvbv5EJQXNODqYtm2_20o-XZnR9DlKnA/exec";

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

//! IS ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
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

function isUserIdExistInGuestsSheet(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] !== userId) {
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

function isValidGuestPhoneNumber(Info) {
  var InfoPattern = /^تلفن مهمان:(\d{11})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidNationalCode(Info) {
  var InfoPattern = /^کد ملی:(\d{10})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidDate(Info) {
  var InfoPattern = /^تاریخ:(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
  var match = Info.match(InfoPattern);
  if (match !== null) {
    var solarYear = Number(match[3]);
    var solarMonth = Number(match[2]);
    var solarDay = Number(match[1]);
    // Solar to Gregorian conversion logic
    var gregorianYear = solarYear + Math.floor((solarMonth - 1 + solarDay / 31) / 12);
    var gregorianMonth = (solarMonth - 1 + solarDay / 31) % 12 + 1;
    var gregorianDay = Math.ceil((solarDay % 31 === 0 ? 31 : solarDay % 31));
    // Create a Date object with the converted Gregorian date
    var inputDate = new Date(gregorianYear, gregorianMonth - 1, gregorianDay);
    // Get the current date
    var currentDate = new Date();
    // Compare the input date with the current date
    return inputDate >= currentDate;
  }
  return false;
}

// function isValidDate(Info) {
//   var InfoPattern = /^تاریخ:(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
//   return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
// }

function isValidHour(Info) {
  var InfoPattern = /^ساعت:(0?|1?[0-9]|2[0-3])$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

//! OTHER FUNCTIONS ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function combineAndConvertToISO(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();

  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      var guestDate = data[i][4];  // Assuming guest date is in the fifth column (index 4)
      var guestHour = data[i][5];   // Assuming guest time is in the sixth column (index 5)

      try {
        // Convert guestDate to a string
        guestDate = guestDate.toString();

        // Split the guestDate into year, month, and day components
        var dateComponents = guestDate.split('/');
        var year = dateComponents[2];
        var month = dateComponents[1] - 1; // Adjust month by subtracting 1
        var day = dateComponents[0];

        // Parse guestHour as an integer
        var hour = parseInt(guestHour);

        // Check if date components are valid
        if (isNaN(day) || isNaN(month) || isNaN(year) || isNaN(hour)) {
          throw new Error('Invalid date components');
        }

        // Create a JavaScript Date object with the components
        var combinedDate = new Date(year, month, day, hour, 0, 0, 0);

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

//!INTERACTS WITH BOT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    userId = contents.message.from.id;
    var messageText = contents.message.text;


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

    if (messageText === "/mehman") {
      if (!isUserIdExistInGuestsSheet(userId)) {
        if (isUserContractNotExpired(userId)) {
          storeUserIdInGuestsSheet(userId);
          sendMessage(userId, " لطفا کد ملی مهمان خود را وارد با این الگو بدون فاصله وارد کنید کد ملی:1234567890");
        } else { sendMessage(userId, "شما یا ثبت نام نکرده اید یا قراردادتان منقضی شده است لطفا با زدن /start نسبت ثبتنام اقدام کنید و یا قراردادتان را تمدید کنید سپس فرمان /mehman را ارسال کنید."); }
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


  } catch (error) {
    sendMessage(userId, "Error in doPost: " + error);
    Logger.log("Error in doPost: " + error);
  }
}
