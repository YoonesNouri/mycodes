// اکثر ریپوهای تبدیل تاریخو آورده
//https://github.com/topics/gregorian-converter

// ریپوی همین کد
//https://github.com/arashm/JDate/blob/master/src/jdate.js

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

// Usage example
function testConversion() {
  var persianDate = Converter.gregorianToPersian(2024, 1, 21);
  Logger.log(persianDate);
}

function testPersianToGregorianConversion() {
  // Replace with your Persian date
  var persianYear = 1402;
  var persianMonth = 11;
  var persianDay = 1;

  // Convert Persian to Gregorian
  var gregorianDate = Converter.persianToGregorian(persianYear, persianMonth, persianDay);

  // Log the results
  Logger.log(persianYear);
  Logger.log(persianMonth);
  Logger.log(persianDay);
  Logger.log(gregorianDate[0]);
  Logger.log(gregorianDate[1]);
  Logger.log(gregorianDate[2]);
}

