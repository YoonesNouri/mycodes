numbers = [1, 2, 3, 4, 5, 6]

result = filter(lambda n: n % 2 == 0, numbers)

print(list(result)) # output: [2, 4, 6]