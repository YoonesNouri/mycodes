//با استفاده از  %#U داخل fmt.printf() میتوانیم علامت و حروفی که در جدول ascii برای هر عددی تعریف شده را پرینت کنیم.
//کد زیر که مکلئود نوشته این کار را کرده:

package main

import (
	"fmt"
)

func main() {
	for i := 33; i <= 122; i++ {
		fmt.Printf("%v\t%#x\t%#U\n", i, i, i)
	}
}
