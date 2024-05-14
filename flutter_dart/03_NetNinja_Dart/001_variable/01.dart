class Person {
  String _name = ''; // متغیر خصوصی با مقدار اولیه خالی

  // Getter و Setter و سایر بخش‌های کلاس اینجا می‌آیند...

  // Getter برای مقدار name
  String get name {
    return _name; // بازگرداندن مقدار متغیر _name
  }

  // Setter برای تنظیم مقدار name
  set name(String value) {
    if (value != null && value.isNotEmpty) {
      // اعتبارسنجی
      _name = value; // تنظیم مقدار متغیر _name
    } else {
      throw ArgumentError(
          'Name cannot be null or empty'); // پرتاب خطا در صورت عدم اعتبارسنجی
    }
  }
}

void main() {
  var person = Person();
  person.name = 'John'; // استفاده از Setter برای تنظیم مقدار name
  print(person.name); // استفاده از Getter برای دریافت مقدار name
}
