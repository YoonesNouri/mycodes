import 'package:flutter/material.dart';
import 'quote.dart';

void main() {
  runApp(MaterialApp(
    home: QuoteList(),
  ));
}

class QuoteList extends StatefulWidget {
  @override
  _QuoteListState createState() => _QuoteListState();
}

class _QuoteListState extends State<QuoteList> {
  List<Quote> quotes = [
    Quote(text:'Be yourself, everyone else is already taken', author: 'James Bond'),
    Quote(text:'I have nothing to declare except my genius', author: 'Marry Curry' ),
    Quote(text:'the truth is rarely pure and never simple', author: 'yoones nouri'),
  ];

  List<String> authors = [];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[300],
      appBar: AppBar(
        title: Text(
          'Awesome Quotes',
          style: TextStyle(
            color: Colors.white,
          ),
        ),
        centerTitle: true,
        backgroundColor: Colors.redAccent,
      ),
      body: Column(
        children: quotes.map((quote) => _quoteTemplate(quote)).toList(),
      ),
    );
  }
}

Widget _quoteTemplate(Quote quote) { // a function of type "Widget"
  return Card(
    margin: EdgeInsets.fromLTRB(16, 16, 16, 0),
    child: Padding(
      padding: const EdgeInsets.all(12.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Text(
            quote.text,
            style: TextStyle(
              fontSize: 18,
              color: Colors.grey[600],
            ),
          ),
          SizedBox(height: 6),
          Text(
            quote.author,
            style: TextStyle(
              fontSize: 14,
              color: Colors.grey[800],
            ),
          ),
        ],
      ),
    ),
  );
}
