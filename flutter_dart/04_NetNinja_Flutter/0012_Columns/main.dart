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
      body: Column(
        mainAxisAlignment: MainAxisAlignment.end,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          Row(
            children: [
              Container(
                padding: EdgeInsets.all(30),
                color: Color(0xFF8AE231),
                child: Text('R1'),
              ),
              Container(
                padding: EdgeInsets.all(30),
                color: Color(0xFFFD971F),
                child: Text('R2'),
              ),
            ],
          ),
          Container(
            padding: EdgeInsets.all(20),
            color: Color(0xFF9CDCFD),
            child: Text('Column 1'),
          ),
          Container(
            padding: EdgeInsets.all(30),
            color: Color(0xFF5B3C70),
            child: Text('Column 2'),
          ),
          Container(
            padding: EdgeInsets.all(40),
            color: Color(0xFF238F41),
            child: Text('Column 3'),
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
