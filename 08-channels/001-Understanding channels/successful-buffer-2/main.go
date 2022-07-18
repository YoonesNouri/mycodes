//yoones ویدیو 162 01-Understanding 08-channels (1ص66 جزوه)
//successful buffer2

package main

import (
	"fmt"
)

func main() {
	c := make(chan int, 3)
	c <- 42
	c <- 43
	fmt.Println(<-c, <-c)
}
