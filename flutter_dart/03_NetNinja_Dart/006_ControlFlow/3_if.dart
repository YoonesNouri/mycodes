// range over List
void main() {
  List<int> scores = [22, 55, 44, 33];
  for (int score in scores) {
    if (score > 40) {
      print('$score is bigger than 40');
    } else {
      print('$score is smaller than 40');
    }
  }
}

