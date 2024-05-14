void main() {
  Map<int, String> firmament = {
    1: 'atarod',
    2: 'zohreh',
  };
  print(firmament); //out put: {1: atarod, 2: zohreh}
  print(firmament[2]); //out put: zohreh

  // update
  firmament[2] = 'venus';
  print(firmament[2]); //out put: venus

  // add
  firmament[3] = 'merikh';
  print(firmament[3]); //out put: merikh

  var find = firmament.containsKey(3);
  print(find); //out put: true

  var find2 = firmament.containsValue('zohreh');
  print(find2); //out put: false

  // remove
  firmament.remove(1);
  print(firmament); //out put: {2: venus, 3: merikh}
}
