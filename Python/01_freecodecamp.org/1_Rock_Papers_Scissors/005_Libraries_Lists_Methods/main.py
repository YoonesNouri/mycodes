my_list = [1, 2, 3, 4, 5]
print(my_list[0])  # خروجی: 1

my_list.append(6)
print(my_list)  # خروجی: [1, 2, 3, 4, 5, 6]

_______________________________________________________________________

import random

my_list = [1, 2, 3, 4, 5]
random_value = random.choice(my_list)
print(random_value)


____________________________________________________________

class MyClass:
    def __init__(self, value):
        self.value = value

    def show_value(self):
        print(self.value)

obj = MyClass(10)
obj.show_value()  # خروجی: 10
