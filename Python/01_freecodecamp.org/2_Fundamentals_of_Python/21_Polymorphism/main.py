def add(x, y):
    return x + y

print(add(5, 10))       # برای اعداد صحیح
print(add('Hello, ', 'world!'))  # برای رشته‌ها

#______________________________________________________________________
class Animal:
    def speak(self):
        raise NotImplementedError("Subclass must implement abstract method")

class Dog(Animal):
    def speak(self):
        return "Woof!"

class Cat(Animal):
    def speak(self):
        return "Meow!"

# نمونه‌هایی از کلاس‌ها
dog = Dog()
cat = Cat()

# تابعی که از پلی‌مورفیسم استفاده می‌کند
def animal_sound(animal):
    print(animal.speak())

animal_sound(dog)  # خروجی: Woof!
animal_sound(cat)  # خروجی: Meow!
