class User {
  var name = 'yoon';
  var age = 35;
  void login() {
    print('user logged in');
  }
}

void main() {
  User user1 = User();
  print(user1.name);
  print(user1.age);
  user1.login();
}
