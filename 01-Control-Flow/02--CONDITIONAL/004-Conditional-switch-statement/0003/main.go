//اگر انتهای تمام caseها یک default بنویسیم دوباره برمیگردد به حالت اول و false ها را اجرا نمیکند

package main

import (
	"fmt"
)

func main() {
	switch {
	case false:
		fmt.Println("this should not print")
	case (2 == 4):
		fmt.Println("this should not print2")
	default:
		fmt.Println("this is default")
	}
}
