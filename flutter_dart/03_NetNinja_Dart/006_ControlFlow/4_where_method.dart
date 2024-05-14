// range over List with where method
void main() {
  List<int> scores = [22, 55, 44, 33];
  for (int score in scores.where((s) => s < 50)) {
    print('score is $score');
  }
}

