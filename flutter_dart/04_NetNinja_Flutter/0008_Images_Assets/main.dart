import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(home: Home()));
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
        child: Image.network('tasvir/horizon_3.jpg'),
        // child: Image.network('www.URL.com'),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          print('button clicked');
        },
        child: Text('click'),
        backgroundColor: Color.fromARGB(255, 54, 122, 179),
      ),
    );
  }
}
