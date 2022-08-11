//کد مکلئود پرینت اعداد زوج از 0 تا 100 با استفاده از for و break و continue و if:

package main

import (
	"fmt"
)

func main() {
	x := 1
	for {
		x++
		if x > 100 {
			break
		}

		if x%2 != 0 {
			continue
		}

		fmt.Println(x)

	}
	fmt.Println("done.")
}

//(اگر != را به == تبدیل کنیم ، اعداد فرد را پرینت میکند.)
