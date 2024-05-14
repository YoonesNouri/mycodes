void main() {
  List<int> numbers = [1, 2, 3]; // <int> is optional

  // Add element
  numbers.add(4); // [1, 2, 3, 4]

  // Add all elements
  numbers.addAll([5, 6, 7]); // [1, 2, 3, 4, 5, 6, 7]

  // Insert element at index
  numbers.insert(1, 8); // [1, 8, 2, 3, 4, 5, 6, 7]

  // Remove element
  numbers.remove(3); // [1, 8, 2, 4, 5, 6, 7]

  // Remove element at index
  numbers.removeAt(1); // [1, 2, 4, 5, 6, 7]

  // Clear list
  numbers.clear(); // []

  // Length of the list
  int length = numbers.length; // 0
}
