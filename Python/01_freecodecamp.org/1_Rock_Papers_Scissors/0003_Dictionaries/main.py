# تعریف یک دیکشنری
my_dict = {
    "name": "Alice",
    "age": 30,
    "city": "New York"
}

# دسترسی به مقدار با استفاده از کلید
print(my_dict["name"])  # خروجی: Alice

# افزودن یک جفت کلید-مقدار جدید
my_dict["email"] = "alice@example.com"

# حلقه زدن روی دیکشنری
for key, value in my_dict.items():
    print(key, value)


