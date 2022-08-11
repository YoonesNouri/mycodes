package main

import "fmt"

func main() {
	x := 33
	for {
		if x > 122 {
			break
		}
		fmt.Printf("%v corresponds to %+q in ASCII\n", x, x)
		x++
	}
}
