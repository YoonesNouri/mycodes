//اما اگر x خارج و قبل از if تعریف شود میتوان مقدار آن یعنی 42 را پرینت کرد. به این صورت:

package main

import (
	"fmt"
)

func main() {
	x := 42
	if x == 42 {
		fmt.Println("0001")
	}
	fmt.Println(x)
}
