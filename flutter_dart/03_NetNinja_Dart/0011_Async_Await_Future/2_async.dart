void main() async { // async marks the function as asynchronous and allows the use of await within its body.
  final post = await fetchPost(); // it's not Future anymore, it's post because we wait until the Future completes.
  print(post.title);
  print(post.userId);
}

Future<Post> fetchPost() {
  // <Post> is the generic type of the Future class
  const delay = Duration(seconds: 3);

  return Future.delayed(delay, () {
    return Post('my post', 123);
  });
}

class Post {
  String title;
  int userId;
  Post(this.title, this.userId);
}
