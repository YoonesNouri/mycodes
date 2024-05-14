import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(
    home: Home(),
  ));
}

class Home extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('my first application'),
        centerTitle: true,
        backgroundColor: const Color.fromARGB(255, 241, 220, 31),
      ),
      body: Container(
        padding: EdgeInsets.fromLTRB(10, 20, 30, 40),
        margin: EdgeInsets.all(30),
        color: Colors.grey[400],
        child: Text('child Container space'),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          print('you clicked floatingActionButton');
        },
        child: Text('click FloatingActionButton'),
        backgroundColor: Color.fromARGB(255, 54, 122, 179),
      ),
    );
  }
}
