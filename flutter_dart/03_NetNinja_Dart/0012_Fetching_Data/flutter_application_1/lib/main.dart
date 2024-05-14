import 'dart:convert' as convert;

import 'package:http/http.dart' as http;

void main() async {
  final post = await fetchPost();
  print(post.title);
  print(post.userId);
}

Future<Post> fetchPost() async {
  var url = Uri.https('jsonplaceholder.typicode.com', '/posts/1');
  final response = await http.get(url);
  Map<String, dynamic> jRes = convert.jsonDecode(response.body);
  return Post(jRes["title"], jRes["userId"]);
}

class Post {
  String title;
  int userId;
  Post(this.title, this.userId);
}
