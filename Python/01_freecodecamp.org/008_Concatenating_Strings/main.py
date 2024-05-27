# استفاده از عملگر +

str1 = "Hello"
str2 = "World"
result = str1 + " " + str2
print(result)  # خروجی: Hello World

________________________________________________________________

# استفاده از join()

words = ["Hello", "World"]
result = " ".join(words)
print(result)  # خروجی: Hello World

________________________________________________________________

# استفاده از f-strings (در پایتون 3.6 و بالاتر)

name = "Alice"
age = 30
print(f"My name is {name} and I am {age} years old.")  
# خروجی: My name is Alice and I am 30 years old.

________________________________________________________________

# استفاده از format()

name = "Alice"
age = 30
result = "My name is {} and I am {} years old.".format(name, age)
print(result)  # خروجی: My name is Alice and I am 30 years old.