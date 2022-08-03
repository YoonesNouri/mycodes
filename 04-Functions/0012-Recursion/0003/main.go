//yoones ویدیو 120
//Returning a recursion: (ص52 جزوه)
//fac = factorial فاکتوریل با لوپ loop
//کد مکلئود با فانکشن است که کمی هم خلاصه کردمش
package main

import "fmt"

func main() {
	fmt.Println(fac(4))
}
func fac(n int) int {
	t := 1
	for ; n > 0; n-- {
		t *= n
	}
	return t
}
