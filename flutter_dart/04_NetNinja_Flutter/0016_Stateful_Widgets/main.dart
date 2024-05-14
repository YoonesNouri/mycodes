// ignore_for_file: prefer_const_constructors

import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';

void main() {
  runApp(MaterialApp(
    home: NinjaCard(),
  ));
}

class NinjaCard extends StatefulWidget {
  @override
  State<NinjaCard> createState() => _NinjaCardState();
}

class _NinjaCardState extends State<NinjaCard> {
  int NinjaLevel = 0;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[900],
      appBar: AppBar(
        title: Text(
          'Ninja ID Card',
          style: TextStyle(
            color: Colors.white,
          ),
        ),
        centerTitle: true,
        backgroundColor: Colors.grey[850],
        elevation: 0,
      ),
      floatingActionButton: Stack(
        children: [
          Positioned(
            bottom: 20.0,
            right: 20.0,
            child: FloatingActionButton(
              onPressed: () {
                setState(() {
                  NinjaLevel += 1;
                });
              },
              child: Icon(Icons.add),
              backgroundColor: Colors.green,
            ),
          ),
          Positioned(
            bottom: 20.0,
            left: 20.0,
            child: FloatingActionButton(
              onPressed: () {
                setState(() {
                  NinjaLevel -= 1;
                });
              },
              child: Icon(Icons.remove),
              backgroundColor: Colors.red,
            ),
          ),
        ],
      ),
      body: Padding(
        padding: EdgeInsets.fromLTRB(30, 40, 30, 0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: CircleAvatar(
                backgroundImage: AssetImage('assets/yoones.jpg'),
                radius: 40,
              ),
            ),
            Divider(
              height: 90,
              color: Colors.grey[700],
            ),
            SizedBox(height: 10),
            Text(
              'NAME',
              style: TextStyle(
                color: Colors.grey,
                letterSpacing: 2,
              ),
            ),
            SizedBox(height: 10),
            Text(
              'Yoones Nouri',
              style: TextStyle(
                color: Colors.amberAccent[200],
                letterSpacing: 2,
                fontSize: 28,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 30),
            Text(
              'CURRENT NINJA LEVEL',
              style: TextStyle(
                color: Colors.grey,
                letterSpacing: 2,
              ),
            ),
            SizedBox(height: 10),
            Text(
              '$NinjaLevel',
              style: TextStyle(
                color: Colors.amberAccent[200],
                letterSpacing: 2,
                fontSize: 28,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 30),
            Row(
              children: [
                Icon(
                  Icons.email,
                  color: Colors.grey[400],
                ),
                SizedBox(
                  width: 10,
                ),
                Text(
                  'Yoones.Nouri@gmail.com',
                  style: TextStyle(
                    color: Colors.grey[400],
                    fontSize: 18,
                    letterSpacing: 1,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
