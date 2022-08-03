package main

import (
	"fmt"
)

func main() {
	// for init; condition; post {}
	for i := 0; i <= 3; i++ {
		for j := 0; j < 3; j++ {
			fmt.Printf("The outer loop: %d\t The inner loop: %d\n", i, j)

		}
	}
}
