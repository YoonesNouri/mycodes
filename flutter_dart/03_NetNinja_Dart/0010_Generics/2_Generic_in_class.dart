// A simple generic class representing a Pair of values.
class Pair<T> {
  T first;
  T second;

  Pair(this.first, this.second);

  // Method to print the pair.
  void printPair() {
    print('($first, $second)');
  }
}

void main() {
  // Creating a Pair of integers.
  var intPair = Pair<int>(1, 2);
  intPair.printPair(); // Output: (1, 2)

  // Creating a Pair of strings.
  var stringPair = Pair<String>('Hello', 'World');
  stringPair.printPair(); // Output: (Hello, World)
}
