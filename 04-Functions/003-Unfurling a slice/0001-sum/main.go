//yoones ویدیو 111 Unfurling a slice (ص50 جزوه1)
//sum

package main

import "fmt"

func sum(xi ...int) int {
	s := 0
	for _, v := range xi {

		s += v
	}
	return s
}
func main() {
	fmt.Println(sum(131, 223, 133, 454))
}
