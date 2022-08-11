package main

import "fmt"

func Sum(xi ...int) int {
	s := 0
	for _, v := range xi {

		s += v
	}
	return s
}
func main() {
	fmt.Println(Sum(141, 271, 133, 454))
}
