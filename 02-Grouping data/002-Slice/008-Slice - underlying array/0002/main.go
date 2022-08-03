//the same array is used for TWO slices
package main

import (
	"fmt"
)

func main() {
	x := []int{42, 43, 44, 45, 46, 47, 48, 49, 50, 51}
	fmt.Println(x)

	y := append(x[:2], x[5:]...) // the same underlying array stores the value of the new slice

	fmt.Println(x)
	fmt.Println(y)
}
