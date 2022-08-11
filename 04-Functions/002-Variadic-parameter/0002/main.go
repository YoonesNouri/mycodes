// Yoones veriadic parameter ویددیو 110 (ص50 جزوه)
package main

import "fmt"

func main() {
	fa(1, 2, 3, 4, 5)
}
func fa(x ...int) { // ... = unlimited number of values
	fmt.Println(x)
	fmt.Printf("%T\n", x)

	sum := 0
	for _, v := range x {
		fmt.Println(sum, "+")
		sum += v
		fmt.Println(v, "=", sum)
		fmt.Println("----------")
	}
	fmt.Println("total =", sum) // "sum" is NOT a KEYWORD
}
