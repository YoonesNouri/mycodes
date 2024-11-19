// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var url = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN;
var webAppUrl = "https://script.google.com/macros/s/AKfycbxwrqX8D3fvj8jMI4ClLTLsADAij2tsIMksl2VJt8UmVdXtDQhrB0hz5OHXWKdYQgy1Ew/exec";

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

function storeGuestInfo(userId, guestNationalCode, guestPhoneNumber, daysAfterToday, hourOfDay) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);

  // Calculate entrance time
  var currentDate = new Date();
  var entranceDate = new Date(currentDate);
  entranceDate.setDate(currentDate.getDate() + daysAfterToday);
  entranceDate.setHours(hourOfDay);

  // Convert entranceDate to ISO format
  var entranceISODate = entranceDate.toISOString();

  // Append row to the sheet with entranceISODate only
  sheet.appendRow([new Date(), userId, guestNationalCode, guestPhoneNumber, entranceISODate]);
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
  var userInfoPattern = /^([\u0600-\u06FFA-Za-z]+)-([\u0600-\u06FFA-Za-z]+)-(\d{11})$/;
  return userInfoPattern.test(userInfo) && userInfoPattern.exec(userInfo) !== null;
}

function isValidGuestInfo(guestInfo) {
  var guestInfoPattern = /^(\d{10})-(\d{11})-(\d+)-(\d{1,2})$/;
  return guestInfoPattern.test(guestInfo) && guestInfo.match(guestInfoPattern) !== null;
}

function isUserTelegramIdExist(userId) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  for (var i = 0; i < data.length; i++) {
    if (data[i][1] == userId) {
      return true; // User with the given Telegram ID already exists
    }
  }
  return false; // User with the given Telegram ID does not exist
}

function handleUserInfo(userId, messageText) {
  var userInfoPattern = /^([\u0600-\u06FFA-Za-z]+)-([\u0600-\u06FFA-Za-z]+)-(\d{11})$/;
  var userInfo = userInfoPattern.exec(messageText);
  if (userInfo && userInfo[3].length === 11) {
    // Check if the user with the given Telegram ID already exists
    if (isUserTelegramIdExist(userId)) {
      sendMessage(userId, "اطلاعات شما قبلا وارد شده است.");
    } else {
      storeUserInfo(userId, userInfo[1], userInfo[2], userInfo[3]);
      sendMessage(userId, "اطلاعات شما با موفقیت ثبت شد. لطفا منتظر تماس اپراتور باشید. ");
    }
  } else {
    sendMessage(userId, "قالب اطلاعات کاربر نامعتبر است. لطفاً مطمئن شوید که شماره تلفن دقیقاً 11 رقم باشد.");
  }
}

function handleGuestInfo(userId, messageText) {
  var guestInfoPattern = /^(\d{10})-(\d{11})-(\d+)-(\d{1,2})$/;
  var guestInfo = guestInfoPattern.exec(messageText);

  if (guestInfo) {
    var daysAfterToday = parseInt(guestInfo[3], 10);
    var hourOfDay = parseInt(guestInfo[4], 10);

    if (isGuestNationalCodeExist(guestInfo[1])) {
      sendMessage(userId, "کد ملی مهمان از قبل وجود دارد.");
    } else {
      storeGuestInfo(userId, guestInfo[1], guestInfo[2], daysAfterToday, hourOfDay);
      sendMessage(userId, "اطلاعات مهمان شما با موفقیت ثبت شد");
    }
  } else {
    sendMessage(userId, "قالب اطلاعات مهمان نامعتبر است. لطفا این الگو را برای مهمانان دنبال کنید => 1234567890-09127654321-1-24");
  }
}

function handleStartCommand(userId) {
  if (authenticateUser(userId)) {
    sendMessage(userId, "شما قبلا ثبت نام کرده اید.");
    if (isContractNotExpired(userId)) {
      sendMessage(userId, "لطفا به ترتیب کد ملی 10 رقمی-شماره تلفن 11 رقمی -چند روز بعد از امروز حضور میابند؟(0 =امروز)-ساعت ورود مهمان خود را بدون فاصله مطابق این الگو وارد کنید:1234567890-09127654321-1-24");
    } else {
      sendMessage(userId, "قرارداد شما به پایان رسیده است. لطفا قرارداد خود را تمدید کنید.");
    }
  } else {
    sendMessage(userId, "شما هنوز ثبت نام نشده اید. در اینجا می توانید اطلاعات بیشتری در مورد ما کسب کنید: https://sadrun.ir/ اگر می خواهید ثبت نام کنید، لطفا نام، نام خانوادگی و شماره تلفن 11 رقمی خود را مطابق این الگو firstName-lastName-09127654321 یا این الگو نام-نام خانوادگی-09127654321 وارد کنید و منتظر بمانید تا اپراتور با شما تماس بگیرد.");
  }
}

function handleOtherCommands(userId, messageText) {
  if (isValidGuestInfo(messageText)) {
    handleGuestInfo(userId, messageText);
  } else if (isValidUserInfo(messageText)) {
    handleUserInfo(userId, messageText);
  } else {
    sendMessage(userId, "قالب اطلاعات نامعتبر است.");
  }
}

function doPost(e) {
  try {
    var contents = JSON.parse(e.postData.contents);
    var userId = contents.message.from.id;
    var messageText = contents.message.text;

    // Check if the command is "/start"
    if (messageText === "/start") {
      handleStartCommand(userId);
    } else {
      handleOtherCommands(userId, messageText);
    }
  } catch (error) {
    sendMessage(userId, "Error: " + error);
    Logger.log("Error in doPost: " + error);
  }
}