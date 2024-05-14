import 'package:flutter/material.dart';

void main() {
  runApp(MaterialApp(
      home: Scaffold(
    appBar: AppBar(
      title: Text('my first app'),
      centerTitle: true,
      backgroundColor: const Color.fromARGB(255, 241, 220, 31),
    ),
    body: Center(child: Text(
      'hello ninjas',
      style: TextStyle(
        fontSize: 20.0,
        fontWeight: FontWeight.bold,
        letterSpacing: 2.0,
        color: Colors.grey[600],
        fontFamily: 'IndieFlower',
      ),
    )),
    floatingActionButton: FloatingActionButton(
      onPressed: () {
        print('button clicked');
      },
      child: Text('click'),
      backgroundColor:   Color.fromARGB(255, 54, 122, 179),
    ),
  )));
}
