String greet({String? name, required int age}) {
  return 'hello I\'m $name and I\'m $age years old.';
}

void main() {
  final greeting = greet(name: 'yoones', age: 35);
  print(greeting);
}
