class Dog:
    def __init__(self, name, age):
        self.name = name
        self.age = age
    
    def bark(self):
        print("woof!")

roger = Dog("Roger", 8)


#?_______________________________________________________________


class Dog:
    def __init__(self, name, age):
        self.name = name
        self.age = age
    
    def bark(self):
        print(f"{self.name} says woof!")

roger = Dog("Roger", 8)
roger.bark()  # Output: Roger says woof!