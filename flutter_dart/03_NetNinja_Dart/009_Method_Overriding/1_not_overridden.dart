class Animal {
  void makeSound() {
    print('Animal makes a sound');
  }
}

class Dog extends Animal {
  // makeSound method is not overridden in the Dog class
}

void main() {
  Dog dog = Dog();
  dog.makeSound(); // Output: Animal makes a sound
}
