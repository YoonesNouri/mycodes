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
      body: Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text('Row text'),
          TextButton(
            onPressed: () {},
            child: Text('TextButton'),
            style: TextButton.styleFrom(
              backgroundColor: Color(0xFF539CF7),
            ),
          ),
          Container(
            color: Color(0xFFE1D1DD),
            padding: EdgeInsets.all(30),
            child: Text('inside Container'),
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
