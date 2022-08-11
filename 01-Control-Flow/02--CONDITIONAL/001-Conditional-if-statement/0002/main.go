//کد مکلئود برای if با مقدار دهی اولیه initialization statement:

package main

import (
	"fmt"
)

func main() {

	if x := 42; x == 42 {
		fmt.Println("0001")
	}
	// fmt.Println(x)
	//چون X داخل اسکوپ یا همان محدوده ی if است لذا برای دستور fmt.Println(x)  تعریف نشده است و اگر // را بر داریم ارور میدهد.
}
