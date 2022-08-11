//yoones ویدیو 163 01-directional 08-channels (1ص68 جزوه)
//successful buffer2

package main

import (
	"fmt"
)

func main() {
	c := make(chan<- int, 2)
	c <- 42
	c <- 43
	fmt.Println(<-c, <-c)
	fmt.Println("--------")
	fmt.Printf("%T", c)
}
