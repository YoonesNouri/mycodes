//yoones ویدیو 162 01-Understanding 08-channels (1ص66 جزوه)
//buffer

package main

import (
	"fmt"
)

func main() {
	c := make(chan int, 1)
	c <- 42
	fmt.Println(<-c)
}
