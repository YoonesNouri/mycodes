// Leap Persian
Converter.leapPersian = function (year) {
  if (year === 1403) return true;
  return (
    (((((year - ((year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816
  ) < 682;
};
