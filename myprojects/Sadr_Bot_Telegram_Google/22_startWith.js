// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbyq-YMSQe-oIQNqkasXXqwY0--E5Yv8HSoMttS1cvP-rK7hz313CItMOumjIAkEHDAAhQ/exec";

//! WEB APP FUNCTIONS ------------------------------------------------------------------------------------------
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

//! STORE FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//? USER ##################################################################################################
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
  if (rowIndex !== -1 && isValidString(messageText)) {
    var range = sheet.getRange(rowIndex, 3);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "قالب نام نامعتبر است و یا آیدی کاربر پیدا نشد.");
  }
}

function storeUserLastName(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userFirstName
  var rowIndex = -1;
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      rowIndex = i + 1;
      break;
    }
  }
  // append in column D
  if (rowIndex !== -1 && isValidString(messageText)) {
    var range = sheet.getRange(rowIndex, 4);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "قالب نام خانوادگی نامعتبر است و یا نام کاربر وارد نشده است.");
  }
}

function storeUserPhoneNumber(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var dataRange = sheet.getDataRange();
  var data = dataRange.getValues();
  // find the row of userFirstName
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

//? GUEST #################################################################################################
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
  if (rowIndex !== -1 && isValidNationalCode(messageText)) {
    var range = sheet.getRange(rowIndex, 3);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "قالب کد ملی مهمان نامعتبر است و یا آیدی کاربر پیدا نشد.");
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
  if (rowIndex !== -1 && isValidPhoneNumber(messageText)) {
    var range = sheet.getRange(rowIndex, 4);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "قالب شماره تلفن مهمان نامعتبر است و یا کد ملی مهمان وارد نشده است.");
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
  if (rowIndex !== -1 && isValidDate(messageText)) {
    var range = sheet.getRange(rowIndex, 5);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "قالب تاریخ نامعتبر است و یا شماره تلفن مهمان وارد نشده است.");
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
  if (rowIndex !== -1 && isValidHour(messageText)) {
    var range = sheet.getRange(rowIndex, 6);
    range.setValue(messageText);
  } else {
    sendMessage(userId, "قالب ساعت نامعتبر است و یا تاریخ حضور مهمان وارد نشده است.");
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
    var range = sheet.getRange(rowIndex, 5);
    range.setValue(guestArrivalDate);
  } else {
    sendMessage(userId, "ساعت ورود مهمان وارد نشده است.");
  }
}

//! IS ????????????????????????????????????????????????????????????????????????????????????????
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

function isUserFirstNameExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      return true; // User First Name found
    }
  }
  return false; // User First Name not found
}

function isUserLastNameExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      return true; // User Last Name found
    }
  }
  return false; // User last Name not found
}

function isUserPhoneNumberExist() {
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
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == userId && new Date() < new Date(data[i][5])) {
        return true; // contract is not expired.
      } else {
        return false; // contract is expired.
      }
  }
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

function isGuestNationalCodeExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][2] !== "") {
      return true; // Guest national code found
    }
  }
  return false; // Guest national code not found
}

function isGuestPhoneNumberExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][3] !== "") {
      return true; // Guest Phone Number found
    }
  }
  return false; // Guest Phone Number not found
}

function isGuestDateExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][4] !== "") {
      return true; // Guest date found
    }
  }
  return false; // Guest date not found
}

function isGuestHourExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][4] !== "") {
      return true; // Guest hour found
    }
  }
  return false; // Guest hour not found
}

function isGuestArrivalDateExist() {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId && data[i][6] !== "") {
      return true; // Guest arrival date found
    }
  }
  return false; // Guest arrival date not found
}

function isValidString(Info) {
  var InfoPattern = /^([\u0600-\u06FFA-Za-z]+)$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidPhoneNumber(Info) {
  var InfoPattern = /^(\d{11})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidNationalCode(Info) {
  var InfoPattern = /^(\d{10})$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidDate(Info) {
  var InfoPattern = /^(\d{4})\/(0[1-9]|1[0-2])\/(0[1-9]|[12]\d|3[01])$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

function isValidHour(Info) {
  var InfoPattern = /^(0?|1?[0-9]|2[0-3])$/;
  return InfoPattern.test(Info) && InfoPattern.exec(Info) !== null;
}

//! OTHER FUNCTIONS //////////////////////////////////////////////////////////////////////////////////////
function getGuestDateHour(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] === userId) {
      // Found the row with the matching userId
      return {
        guestDate: data[i][4],  // Assuming guest date is in the fifth column (index 4)
        guestHour: data[i][5]   // Assuming guest time is in the sixth column (index 5)
      };
    }
  }
  // If userId is not found, you may want to handle this case appropriately
  return null;
}

function combineAndConvertToISO(userId, guestDate, guestHour) {
  // get guestDate and guestHour
  getGuestDateHour(userId)
  // Split the guestDate into day, month, and year components
  var dateComponents = guestDate.split('/');
  var day = parseInt(dateComponents[0], 10);
  var month = parseInt(dateComponents[1], 10);
  var year = parseInt(dateComponents[2], 10);
  // Create a JavaScript Date object with the components
  var combinedDate = new Date(year, month - 1, day, guestHour, 0, 0, 0);
  // Convert the Date object to ISO format
  var guestArrivalDate = combinedDate.toISOString();
  return guestArrivalDate;
}

//!INTERACTS WITH BOT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
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


    if (messageText.startsWith("نام:")) {
      var inf = messageText.replace("نام:", "").trim();
      storeUserFirstName(inf);
      sendMessage(userId, " نام شما ثبت شد. لطفا نام خانوادگی خود را با این الگو بدون فاصله وارد کنید نام خانوادگی:محمدی");
    }


    if (messageText.startsWith("نام خانوادگی:")) {
      var inf = messageText.replace("نام خانوادگی:", "").trim();
      storeUserLastName(inf);
      sendMessage(userId, " نام خانوادگی شما ثبت شد. لطفا شماره تلفن خود را با این الگو بدون فاصله وارد کنید تلفن کاربر:09121234567");
    }


    if (messageText.startsWith("تلفن کاربر:") && isValidPhoneNumber(messageText)) {
      var inf = messageText.replace("تلفن کاربر:", "").trim();
      storeUserPhoneNumber(inf);
      sendMessage(userId, "شماره تلفن شما ثبت شد. لطفا منتظر تماس اپراتور بمانید تا ابتدا قرارداد شما را بررسی کند سپس با ارسال فرمان /mehman ، اطلاعات مهمان خود را وارد کنید. ");
    } else { sendMessage(userId, "قالب شماره تلفن کاربر نامعتبر است."); }


    if (messageText === "/mehman") {
      if (isUserContractNotExpired(userId)) {
        sendMessage(userId, " لطفا کد ملی مهمان خود را وارد با این الگو بدون فاصله وارد کنید کد ملی:1234567890");
        storeUserIdInGuestsSheet(userId);
      } else { sendMessage(userId, "شما یا ثبت نام نکرده اید یا قراردادتان منقضی شده است لطفا با زدن /start نسبت ثبتنام اقدام کنید و یا قراردادتان را تمدید کنید سپس فرمان /mehman را ارسال کنید."); }
    }

    if (messageText.startsWith("کد ملی:")) {
      var inf = messageText.replace("کد ملی:", "").trim();
      storeGuestNationalCode(inf);
      sendMessage(userId, "کد ملی مهمان شما ثبت شد. لطفا شماره تلفن مهمان خود را با این الگو بدون فاصله وارد کنید تلفن مهمان:09121234567 ");
    }


    if (messageText.startsWith("تلفن مهمان:")) {
      var inf = messageText.replace("تلفن مهمان:", "").trim();
      storeGuestPhoneNumber(inf);
      sendMessage(userId, "تلفن مهمان شما ثبت شد. لطفا تاریخ حضور مهمان خود را بترتیب سال ماه روز وارد کنید تاریخ:30/12/1402");
    }


    if (messageText.startsWith("تاریخ:")) {
      var inf = messageText.replace("تاریخ:", "").trim();
      storeGuestDate(inf);
      sendMessage(userId, "تاریخ حضور مهمان شما ثبت شد. لطفا ساعت ورود مهمان خود را بااین الگو بدون فاصله وارد کنید ساعت:0-23");
    }

    if (messageText.startsWith("ساعت:")) {
      var inf = messageText.replace("ساعت:", "").trim();
      storeGuestHour(inf);
        sendMessage(userId, "ساعت ورود مهمان شما ثبت شد. ایشان میتوانند با در دست داشتن کارت ملی خود از درب نگهبانی وارد مجموعه شوند.");
        
        guestArrivalDate = combineAndConvertToISO(userId, guestDate, guestHour);
        storeGuestArrivalDate(guestArrivalDate);
    }


  } catch (error) {
    sendMessage(userId, "Error in doPost: " + error);
    Logger.log("Error in doPost: " + error);
  }
}
