//code block in code block with y:
package main

import (
	"fmt"
)

func main() {
	var x int
	fmt.Println(x)
	x++

	{
		y := 42
		fmt.Println(y)
	}
	// fmt.Println(y) چون وای داخل کد بلاک هست اگر این جمله را بنویسیم ارور میدهد چون وای را فقط در محدوده¬ی کد بلاک خودش میشناسد.

	fmt.Println(x)
	foo()
}

func foo() {
	fmt.Println("hello")
}
