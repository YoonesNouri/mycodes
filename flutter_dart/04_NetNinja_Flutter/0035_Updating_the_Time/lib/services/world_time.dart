import 'package:http/http.dart' as http; // Adding alias 'http'
import 'dart:convert';
import 'package:intl/intl.dart';

class WorldTime {
  String location; // location name for UI
  late String time; // the time in that location
  String flag; // url to an asset flag icon
  String url; // location url for api endpoint
  late bool isDaytime; // true or false if daytime or not
  WorldTime({required this.location, required this.flag, required this.url});

  Future<void> getTime() async {
    try {
      // Convert the URL string to a Uri object
      Uri uri = Uri.parse('http://worldtimeapi.org/api/timezone/$url');

      // Make the request
      http.Response response = await http.get(uri);
      Map data = jsonDecode(response.body);

      // Get properties from json
      String datetime = data['datetime'];
      String offset = data['utc_offset'].substring(0, 3);

      // Create DateTime object
      DateTime now = DateTime.parse(datetime);
      now = now.add(Duration(hours: int.parse(offset)));

      // Set the time property
      isDaytime = now.hour > 6 && now.hour < 20 ? true : false;
      time = DateFormat.jm().format(now);
    } catch (e) {
      print('caught error: $e');
      time = "could not get time data";
    }
  }
}
