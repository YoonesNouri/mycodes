//همچنین موسوم به
//
//self-executing func
//
//چون وقتی بانام است و بوسیله ی نامش آن را کال میکنیم
//و در انتهای نام فانکشن پرانتز() هست، وقتی بی نام میشود هم باید آن پرانتز ها برای آرگومان ها بیاید وگرنه ارور میدهد.
//به این صورت:
//	func() {} ()
package main

import (
	"fmt"
)

func main() {
	foo()

	func() {
		fmt.Println("Anonymous func ran")
	}()

	func(x int) {
		fmt.Println("The meaning of life:", x)
	}(42)
}

func foo() {
	fmt.Println("foo ran")
}
