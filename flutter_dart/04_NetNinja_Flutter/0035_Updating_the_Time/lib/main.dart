import 'package:flutter/material.dart';
import 'package:statelesswidgets_hotreload/pages/choose_location.dart';
import 'package:statelesswidgets_hotreload/pages/loading.dart';
import 'pages/home.dart';


void main() => runApp(MaterialApp(
    initialRoute: '/',
    routes: {
      '/': (context) => Loading(),
      '/home': (context) => Home(),
      '/location': (context) => ChooseLocation(),
    }
));
