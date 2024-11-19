// Constants and Configuration
var TELEGRAM_BOT_TOKEN = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
var SHEET_ID = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";
var USERS_SHEET_NAME = "UsersSheet";
var GUESTS_SHEET_NAME = "GuestsSheet";
var apiUrl = "https://api.telegram.org/bot" + TELEGRAM_BOT_TOKEN ;
var webAppUrl = "https://script.google.com/macros/s/AKfycbw7EZgGKEL0sL7JYUdAXRPHPMkGUZgHVX0OFfS8kUVTbVkbRqcYc5PEaXBnepp2oO2ufA/exec";

var command  = {
  "/start": "welcome to my bot",
  "hi": "hello",
  "what is your name?": "my name is devisty bot"
}

// set webhook
function setWebhook(){
  var url = apiUrl + "/setwebhook?url="+webAppUrl;
  var res = UrlFetchApp.fetch(url).getContentText();
  Logger.log(res);
}

// handle webhook
function doPost(e){
  var webhookData = JSON.parse(e.postData.contents);
  var from = webhookData.message.from.id;
  var text = webhookData.message.text;

  if(typeof command[text] == 'undefined'){
    var sendText = encodeURIComponent("command not found");
  }else{
    var sendText = encodeURIComponent(command[text]);
  }

  var url  = apiUrl+"/sendmessage?parse_mode=HTML&chat_id="+from+"&text="+sendText;
  var opts = {"muteHttpExceptions": true}
  UrlFetchApp.fetch(url, opts).getContentText();
}
