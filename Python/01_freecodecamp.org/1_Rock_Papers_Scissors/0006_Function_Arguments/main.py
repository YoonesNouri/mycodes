# 1. آرگومان‌های مکانی (Positional Arguments)
# این نوع آرگومان‌ها بر اساس ترتیبشان در فراخوانی تابع، به پارامترهای تابع نسبت داده می‌شوند.
def greet(name, age):
    print(f"Hello, {name}! You are {age} years old.")

greet("Alice", 30)  # خروجی: Hello, Alice! You are 30 years old.

_____________________________________________________________________

# 2. آرگومان‌های کلیدواژه‌ای (Keyword Arguments)
# در این نوع، می‌توانید نام پارامترها را مشخص کنید و نیازی به ترتیب خاصی نیست.
def greet(name, age):
    print(f"Hello, {name}! You are {age} years old.")

greet(age=30, name="Alice")  # خروجی: Hello, Alice! You are 30 years old.


_____________________________________________________________________

# 3. آرگومان‌های پیش‌فرض (Default Arguments)
# این نوع آرگومان‌ها مقادیر پیش‌فرضی دارند که در صورت عدم ارائه مقدار توسط فراخوانی‌کننده، استفاده می‌شوند.
def greet(name, age=25):
    print(f"Hello, {name}! You are {age} years old.")

greet("Alice")  # خروجی: Hello, Alice! You are 25 years old.
greet("Bob", 30)  # خروجی: Hello, Bob! You are 30 years old.


_____________________________________________________________________

# 4. آرگومان‌های متغیر (Variable-length Arguments)
# *args
# این نوع آرگومان‌ها به شما اجازه می‌دهند تا تعداد متغیری از آرگومان‌ها را به تابع ارسال کنید. *args به صورت یک تاپل درون تابع دریافت می‌شود.
def greet(*names):
    for name in names:
        print(f"Hello, {name}!")

greet("Alice", "Bob", "Charlie")  # خروجی: Hello, Alice! Hello, Bob! Hello, Charlie!

# **kwargs
# این نوع آرگومان‌ها به شما اجازه می‌دهند تا تعداد متغیری از آرگومان‌های کلیدواژه‌ای را به تابع ارسال کنید. **kwargs به صورت یک دیکشنری درون تابع دریافت می‌شود.
def greet(**person):
    for key, value in person.items():
        print(f"{key}: {value}")

greet(name="Alice", age=30, city="New York")  # خروجی: name: Alice age: 30 city: New York


_____________________________________________________________________


# ترکیب انواع آرگومان‌ها
def complex_function(a, b, *args, c=10, d=20, **kwargs):
    print(f"a: {a}, b: {b}")
    print(f"args: {args}")
    print(f"c: {c}, d: {d}")
    print(f"kwargs: {kwargs}")

complex_function(1, 2, 3, 4, 5, c=15, d=25, e=30, f=40)
# خروجی:
# a: 1, b: 2
# args: (3, 4, 5)
# c: 15, d: 25
# kwargs: {'e': 30, 'f': 40}
