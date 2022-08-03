//yoones ویدیو 117
//Returning a func: (ص51 جزوه)
package main

import "fmt"

func main() {

	fmt.Println(bar())
	fmt.Printf("%T\n\n", bar())

	fmt.Println(bar()())
	fmt.Printf("%T\n", bar()())

}

func bar() func() int {
	return func() int {
		return 14
	}
}
