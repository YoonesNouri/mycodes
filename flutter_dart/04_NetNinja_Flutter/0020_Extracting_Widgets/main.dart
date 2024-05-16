import 'package:flutter/material.dart';
import 'quote.dart';
import 'quote_card.dart';

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
    Quote(
        text: 'Be yourself, everyone else is already taken',
        author: 'James Bond'),
    Quote(
        text: 'I have nothing to declare except my genius',
        author: 'Marry Curry'),
    Quote(
        text: 'the truth is rarely pure and never simple',
        author: 'yoones nouri'),
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
        children: quotes.map((quote) => QuoteCard(quote: quote)).toList(),
      ),
    );
  }
}

// Widget quoteTemplate(Quote quote) {
//   // a function of type "Widget"
//   return QuoteCard(quote: quote);
// }

