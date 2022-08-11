//کد خودم برای پرینت اعداد زوج از 0 تا 20 با استفاده از for و بدون استفاده از break و با یک if کمتر نسبت به کد مکلئود

package main

import (
	"fmt"
)

func main() {
	x := 1
	for x < 20 {

		x++
		if x%2 != 0 {
			continue
		}

		fmt.Println(x)

	}
	fmt.Println("done.")
}
