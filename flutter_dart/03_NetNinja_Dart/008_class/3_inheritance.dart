class Animal {
  String color;
  Animal(this.color);
  String eat() {
    return 'yummy';
  }
}

class Dog extends Animal {
  String breed;
  Dog(String color, this.breed) : super(color); // constructor
  void bark() {
    print(
        'The color of the dog is $color and its breed is $breed, hop hop hop, ${eat()}');
  }
}

class Cat extends Animal {
  int age;
  Cat(String color, this.age) : super(color); // constructor
  void meow() {
    print(
        'The color of the cat is $color and its age is $age years, meow meow meow, ${eat()}');
  }
}

void main() {
  Dog dog = Dog('brown', 'husky');
  dog.bark();

  Cat cat = Cat('white', 5);
  cat.meow();
}
