# اعداد صحیح
a = 10
print(type(a))  # خروجی: <class 'int'>

# اعداد اعشاری
b = 3.14
print(type(b))  # خروجی: <class 'float'>

# اعداد مختلط
c = 1 + 2j
print(type(c))  # خروجی: <class 'complex'>

# رشته‌ها
d = "سلام"
print(type(d))  # خروجی: <class 'str'>

# لیست‌ها
e = [1, 2, 3, 4]
print(type(e))  # خروجی: <class 'list'>

# تاپل‌ها
f = (1, 2, 3, 4)
print(type(f))  # خروجی: <class 'tuple'>

# مجموعه‌ها
g = {1, 2, 3, 4}
print(type(g))  # خروجی: <class 'set'>

# دیکشنری‌ها
h = {"نام": "یونس", "سن": 35}
print(type(h))  # خروجی: <class 'dict'>

# بولین‌ها
i = True
print(type(i))  # خروجی: <class 'bool'>

# None
j = None
print(type(j))  # خروجی: <class 'NoneType'>

________________________________________________________________
isinstance(object, classinfo)
________________________________________________________________
# بررسی یک شیء با یک کلاس
# بررسی یک عدد صحیح
a = 10
print(isinstance(a, int))  # خروجی: True

# بررسی یک رشته
b = "سلام"
print(isinstance(b, str))  # خروجی: True
________________________________________________________________
# بررسی یک شیء با چندین کلاس
# بررسی یک عدد صحیح یا رشته
a = 10
print(isinstance(a, (int, str)))  # خروجی: True

b = "سلام"
print(isinstance(b, (int, str)))  # خروجی: True

# بررسی یک عدد اعشاری یا رشته
c = 3.14
print(isinstance(c, (int, str)))  # خروجی: False

________________________________________________________________
# تفاوت isinstance() با type()
class A:
    pass

class B(A):
    pass

obj = B()

print(type(obj) == A)         # خروجی: False
print(isinstance(obj, A))     # خروجی: True
________________________________________________________________
