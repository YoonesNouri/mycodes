from functools import reduce

numbers = [1, 2, 3, 4, 5]

# استفاده از تابع لامبدا برای محاسبه حاصل‌ضرب
result = reduce(lambda x, y: x * y, numbers)

print(result)  # خروجی: 120
