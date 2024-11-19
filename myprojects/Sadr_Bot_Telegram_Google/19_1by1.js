// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbyq-YMSQe-oIQNqkasXXqwY0--E5Yv8HSoMttS1cvP-rK7hz313CItMOumjIAkEHDAAhQ/exec";

var userFirstName;
var userLastName;
var userPhoneNumber;
var guestNationalCode;
var guestPhoneNumber;
var guestDate;
var guestHour;
var guestArrivalDate;

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

function storeUserInfo(userId, userFirstName, userLastName, userPhoneNumber) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  sheet.appendRow([new Date(), userId, userFirstName, userLastName, userPhoneNumber, ""]);
}

function storeGuestInfo(userId, guestNationalCode, guestPhoneNumber, guestArrivalDate) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  sheet.appendRow([new Date(), userId, guestNationalCode, guestPhoneNumber, guestArrivalDate]);
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

function isGuestInfoDuplicate(guestNationalCode, guestArrivalDate) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][2] == guestNationalCode && data[i][4] == guestArrivalDate) {
      return true; // Guest information already exists
    }
  }
  return false; // Guest information does not exist
}

function combineAndConvertToISO(guestDate, guestHour) {
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

//! USER INFO ****************************************************************
function handleUserInput(userId ,messageText) {
   do {
    sendMessage(userId, "لطفا نام خود را وارد کنید:");
      userFirstName = messageText;
     sendMessage(userId, isValidString(userFirstName) ? "نام شما ثبت شد." : "قالب اطلاعات نامعتبر است.");
     } while (!isValidString(userFirstName));
  //?-----------------------------------------------------------------------------------------------------
do {
  sendMessage(userId, "لطفا نام خانوادگی خود را وارد کنید:");
  userLastName = messageText;
  sendMessage(userId, isValidString(userLastName) ? "نام خانوادگی شما ثبت شد." : "قالب اطلاعات نامعتبر است.");
} while (!isValidString(userLastName));
  //?-----------------------------------------------------------------------------------------------------
do {
  sendMessage(userId, "لطفا شماره تلفن 11 رقمی خود را وارد کنید:");
  if (isValidPhoneNumber(messageText)) {
    userPhoneNumber = messageText;
    sendMessage(userId, "شماره تلفن شما ثبت شد.");
    break;
  } else { sendMessage(userId, "قالب اطلاعات نامعتبر است."); }
} while (!isValidPhoneNumber(userPhoneNumber));
  //?-----------------------------------------------------------------------------------------------------
  storeUserInfo(userId, userFirstName, userLastName, userPhoneNumber);
  sendMessage(userId, "اطلاعات شما با موفقیت ذخیره شد. لطفا منتظر تماس اپراتور باشید.");
}

//! GUEST INFO ****************************************************************
function handleGuestInput(userId ,messageText) {
  //جلوگیری از ثبت مهمان تکراری در تاریخ تکراری
  if (isGuestInfoDuplicate(guestNationalCode, guestArrivalDate)) {
    sendMessage(userId, "مهمان شما در این تاریخ قبلا مجوز ورود گرفته است.");
  } else {
do {
  sendMessage(userId, "لطفا کد ملی مهمان خود را وارد کنید.");
  if (isValidNationalCode(messageText)) {
    guestNationalCode = messageText;
    sendMessage(userId, "کد ملی مهمان شما ثبت شد.");
    break;
  } else { sendMessage(userId, "قالب اطلاعات نامعتبر است."); }
} while (!isValidNationalCode(guestNationalCode));
    //?-----------------------------------------------------------------------------------------------------
do {
  sendMessage(userId, "لطفا شماره تلفن 11 رقمی مهمان خود را وارد کنید.");
  if (isValidPhoneNumber(messageText)) {
    guestPhoneNumber = messageText;
    sendMessage(userId, "شماره تلفن مهمان شما ثبت شد.");
    break;
  } else { sendMessage(userId, "قالب اطلاعات نامعتبر است."); }
} while (!isValidPhoneNumber(guestPhoneNumber));
    //?-----------------------------------------------------------------------------------------------------
do {
  sendMessage(userId, "لطفا تاریخ حضور مهمان خود را در قالب: yyyy/mm/dd وارد کنید");
  if (isValidDate(messageText)) {
    guestDate = messageText;
    sendMessage(userId, "تاریخ حضور مهمان شما ثبت شد.");
    break;
  } else { sendMessage(userId, "قالب اطلاعات نامعتبر است."); }
} while (!isValidDate(guestDate));
    //?-----------------------------------------------------------------------------------------------------
do {
  sendMessage(userId, "لطفا ساعت ورود مهمان را مطابق قالب وارد کنید (مثال: 0-23):");
  if (isValidHour(messageText)) {
    guestHour = messageText;
    sendMessage(userId, "ساعت حضور مهمان شما ثبت شد.");
    break;
  } else { sendMessage(userId, "قالب اطلاعات نامعتبر است."); }
} while (!isValidHour(guestHour));
    //?-----------------------------------------------------------------------------------------------------
    //ترکیب تاریخ و ساعت و تبدیل به ایزو
    guestArrivalDate = combineAndConvertToISO(guestDate, guestHour);
    storeGuestInfo(userId, guestNationalCode, guestPhoneNumber, guestArrivalDate);
  }
  }  

function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    userId = contents.message.from.id;
    var messageText = contents.message.text;

    // Check if the command is "/start"
    if (messageText === "/start") {
      if (authenticateUser(userId)) {
        sendMessage(userId, "شما قبلا ثبت نام کرده اید.");
        if (isContractNotExpired(userId)) {
          handleGuestInput(userId ,messageText);
        } else { sendMessage(userId, "قرارداد شما منقضی شده لطفا آن را تمدید کنید."); }
      } else { handleUserInput(userId ,messageText); }
    } else { sendMessage(userId, "لطفا برای شروع دستور /start را ارسال کنید. "); }
  
  } catch (error) {
    sendMessage(userId, "Error in doPost: " + error);
    Logger.log("Error in doPost: " + error);
  }
}

