//yoones ویدیو 162 01-Understanding 08-channels (1ص66 جزوه)
//unsuccessful buffer

package main

import (
	"fmt"
)

func main() {
	c := make(chan int, 1)
	c <- 42
	c <- 43
	fmt.Println(<-c)
}
