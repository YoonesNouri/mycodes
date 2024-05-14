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
      body: Center(
        child: IconButton(
          onPressed: () {
            print('you clicked IconButton');
          },
          icon: Icon(Icons.alternate_email),
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.amber,
          ),
        ),
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
