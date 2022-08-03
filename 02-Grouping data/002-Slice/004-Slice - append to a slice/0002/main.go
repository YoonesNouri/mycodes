//میتوان یک slice دیگر را هم به صورت متغیر به slice قبلی (با استفاده از سه نقطه بعد از آن) اضافه کرد. مانند کد زیر:
// Yoones
package main

import (
	"fmt"
)

func main() {

	x := []int{11, 12, 13, 14, 15}
	fmt.Println(x)
	x = append(x, 16, 17, 18, 19)
	fmt.Println(x)
	y := []int{22, 33, 44}
	x = append(x, y...)
	fmt.Println(x)
}
