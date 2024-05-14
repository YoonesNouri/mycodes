class User {
  String username;
  int age;

  User(this.username, this.age); // constructor
  String format() {
    return '${this.username} --> ${this.age}';
  }
}

void main() {
  User user1 = User('yoon', 35);
  User user2 = User('Ali', 36);

  print(user1.format());
  print(user2.format());
}
  // output:
  // yoon --> 35
  // Ali --> 36