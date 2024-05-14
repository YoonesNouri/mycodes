class Animal {
  String color;
  Animal(this.color);
  String eat() {
    return 'yummy';
  }
}

class Dog extends Animal {
  String breed;
  Dog(super.color, this.breed); // constructor
  void bark() {
    print(
        'The color of the dog is $color and its breed is $breed, hop hop hop, ${eat()}');
  }
}

class Cat extends Animal {
  int age;
  Cat(super.color, this.age); // constructor
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

// output:
// The color of the dog is brown and its breed is husky, hop hop hop, yummy
// The color of the cat is white and its age is 5 years, meow meow meow, yummy