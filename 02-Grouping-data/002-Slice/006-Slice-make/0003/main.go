//میتوان به slice با append پوزیشن و مقدار اضافه کرد:
// Yoones
package main

import "fmt"

func main() {

	x := make([]int, 10, 100)
	fmt.Println(x)
	fmt.Println(len(x))
	fmt.Println(cap(x))
	x[0] = 7
	x[9] = 14
	fmt.Println(x)
	x = append(x, 72)
	fmt.Println(len(x))
	fmt.Println(cap(x))

}
