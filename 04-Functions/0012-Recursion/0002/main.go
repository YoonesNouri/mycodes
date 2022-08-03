//yoones ویدیو 120
//Returning a recursion: (ص52 جزوه)
//fac = factorial فاکتوریل با لوپ loop
//کد خودم بدون فانکشن است
package main

import "fmt"

func main() {
	x := 1
	for i := 1; i <= 4; i++ {
		x *= i
		if i == 4 {
			fmt.Println(x)
		}
	}
}
