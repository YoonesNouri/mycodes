  var token = "6479253293:AAFjCdf91FWkA5_r162_Kjgej2e3miqtx2g";
  var apiUrl = "https://api.telegram.org/bot" + token ;
  var appUrl = "https://script.google.com/macros/s/AKfycbx5GKvSgpzLN-AVvJYGALd8ZEgyT-fOp9k0bCO1AFmuP14bCPYenOEJVJLxQL3qGia6eA/exec";
  var ssId = "101JN0hqXmQqM-_RKKxJxHoWK31dCbwfHOikUy_2Yhyk";

var command  = {
  "/start": "welcome to my bot",
  "hi": "hello",
  "what is your name?": "my name is devisty bot"
}


// set webhook
function setWebhook(){
  var url = apiUrl + "/setwebhook?url="+appUrl;
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

