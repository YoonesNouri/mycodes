import 'package:flutter/material.dart';

class ChooseLocation extends StatefulWidget {
  @override
  _ChooseLocationState createState() => _ChooseLocationState();
}

class _ChooseLocationState extends State<ChooseLocation> {
  void getData() async {

    // simulate a network request for a username
    String username = await Future.delayed(Duration(seconds: 3), () {
      return 'yoones';
    });

    // simulate a network request to get bio of the username
      String bio = await Future.delayed(Duration(seconds: 2), () {
      return 'bio: vegan, musician, egg collector';
    });

    print('$username - $bio');

  }

  @override
  void initState() {
    super.initState();
    getData();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[200],
      appBar: AppBar(
        backgroundColor: Colors.blue[900],
        iconTheme: IconThemeData(
          color: Colors.white,
        ),
        title: Text(
          'Choose a location',
          style: TextStyle(
            color: Colors.white,
          ),
        ),
        centerTitle: true,
        elevation: 0,
      ),
    );
  }
}
