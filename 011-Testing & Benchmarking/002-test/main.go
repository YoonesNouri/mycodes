package main

import "fmt"

func main() {
	fmt.Println("2 + 3 =", MySum(2, 3))
	fmt.Println("4 + 7 =", MySum(4, 7))
	fmt.Println("5 + 9 =", MySum(5, 9))
}

func MySum(xi ...int) int {
	sum := 0
	for _, v := range xi {
		sum += v
	}
	return sum
}
