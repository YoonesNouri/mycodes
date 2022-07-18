//کال¬های فانکشن defer پس از return فانکشن بیرونی
//به ترتیب Last In First Out یعنی از آخر به اول اجرا می شوند.

package main

import "fmt"

func main() {
	b()
}

func b() {
	for i := 0; i < 4; i++ {
		defer fmt.Print(i)
	}
}
