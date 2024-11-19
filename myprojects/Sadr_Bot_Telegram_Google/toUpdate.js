function storeUserData(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(USERS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var lastRow = data.length - 1; // Get the last row index since JavaScript is 0-indexed
  // Find the last record of userId
  var rowIndex = -1;
  for (var i = lastRow; i >= 0; i--) {
    if (data[i][1] === userId) {
      rowIndex = i;
      // Find the last record in columns in the row
      var numColumns = data[rowIndex].length;
      for (var j = numColumns; j >= 1; j--) {
        if (data[rowIndex][j] !== "") {
          var columnIndex = j;
          var range = sheet.getRange(rowIndex + 1, columnIndex + 2); // Adjusted both indexes by 1 since Google Sheets is 1-indexed, and columnIndex itself by 1 to go to next column
            range.setValue(messageText);
            return;
        }
      }
    }
  }
  // If userId not found, exit the function
  if (rowIndex === -1) {
    sendMessage(userId, "آیدی تلگرام کاربر در شیت یوزرها پیدا نشد.");
    return;
  }
}


function storeGuestData(messageText) {
  var sheet = SpreadsheetApp.openById(SHEET_ID).getSheetByName(GUESTS_SHEET_NAME);
  var data = sheet.getDataRange().getValues();
  var lastRow = data.length - 1; // Get the last row index since JavaScript is 0-indexed
  // Find the last record of userId
  var rowIndex = -1;
  for (var i = lastRow; i >= 0; i--) {
    if (data[i][1] === userId) {
      rowIndex = i;
      // Find the last record in columns in the row
      var numColumns = data[rowIndex].length;
      for (var j = numColumns; j >= 1; j--) {
        if (data[rowIndex][j] !== "") {
          var columnIndex = j;
          var range = sheet.getRange(rowIndex + 1, columnIndex + 2); // Adjusted both indexes by 1 since Google Sheets is 1-indexed, and columnIndex itself by 1 to go to next column
            range.setValue(messageText);
            return;
        }
      }
    }
  }
  // If userId not found, exit the function
  if (rowIndex === -1) {
    sendMessage(userId, "آیدی تلگرام کاربر در شیت مهمانها پیدا نشد.");
    return;
  }
}