// Yoones
package main

import "fmt"

func main() {

	x := []int{11, 12, 13, 14, 15}

	fmt.Println(x[1:])
	fmt.Println(x[1:3])
	for I, v := range x {
		fmt.Println(I, v)
	}
	for i := 0; i <= 5; i++ {
		fmt.Println(i, x[i])
	}

}
