import 'package:http/http.dart' as http;

void main() async {
  fetchPost();
}

fetchPost() async {
  var url = Uri.https('jsonplaceholder.typicode.com', '/posts/1');
  final response = await http.get(url);
  print(response.body);
}

class Post {
  String title;
  int userId;
  Post(this.title, this.userId);
}
