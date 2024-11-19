  var token = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
  var url = "https://api.telegram.org/bot" + token ;
  var webAppUrl = "https://script.google.com/macros/s/AKfycbx5GKvSgpzLN-AVvJYGALd8ZEgyT-fOp9k0bCO1AFmuP14bCPYenOEJVJLxQL3qGia6eA/exec";
  var ssId = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";

function getMe() {
  var response = UrlFetchApp.fetch(url + "/getMe");
  Logger.log(response.getContentText());
}

function getUpdates() {
  var response = UrlFetchApp.fetch(url + "/getUpdates");
  Logger.log(response.getContentText());
}

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

function sendMessage(id , text) {
  var response = UrlFetchApp.fetch(url + "/sendMessage?chat_id=" + id + "&text=" + text);
  Logger.log(response.getContentText());
}

function doGet(e) {
  return HtmlService.createHtmlOutput("Hello" + JSON.stringify(e));
}

function doPost(e) {
  var contents = JSON.parse(e.postData.contents);
  var text = contents.message.text;
  var id = contents.message.from.id;
  var name = contents.message.chat.first_name + " " + contents.message.chat.last_name;
  sendMessage(id , "sending...");
  var item = text.split(":");
  var sheet = SpreadsheetApp.openById(ssId).getSheetByName("UsersSheet");
  sheet.appendRow([new Date, id, name, item[0], item[1], item[2], item[3]]);
}
