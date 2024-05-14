import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';

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
      body: Row(
        children: [
          Expanded(flex: 3, child: Image.asset('tasvir/horizon_1.jpg')),
          Expanded(
            flex: 1,
            child: Container(
              padding: EdgeInsets.all(30),
              color: Color.fromARGB(255, 212, 235, 8),
              child: Text('container 1'),
            ),
          ),
          Expanded(
            flex: 1,
            child: Container(
              padding: EdgeInsets.all(30),
              color: Color(0xFF9AB536),
              child: Text('container 2'),
            ),
          ),
          Expanded(
            flex: 1,
            child: Container(
              padding: EdgeInsets.all(30),
              color: Color(0xFFFF5680),
              child: Text('container 3'),
            ),
          ),
        ],
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
