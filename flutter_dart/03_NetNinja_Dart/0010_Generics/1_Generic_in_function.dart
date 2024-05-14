// A generic function to print any type of data.
void printData<T>(T data) {
  print('Data: $data');
}

void main() {
  // Calling the generic function with an integer.
  printData<int>(10); // Output: Data: 10

  // Calling the generic function with a string.
  printData<String>('Hello'); // Output: Data: Hello
}
