//yoones ویدیو 179 Checking errors (ص73 جزوه1)
//fmt error

package main

import (
	"fmt"
)

func main() {
	n, err := fmt.Println("something") //err = returning an error , n = number of  bytes written
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println(n)
}
