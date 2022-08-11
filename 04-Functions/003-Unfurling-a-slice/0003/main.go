package main

import (
	"fmt"
)

func main() {
	x := sum("james")
	fmt.Println("The total is", x)
}

//نکته : در فانکشن ، تایپ ما به آخرین ولیو بستگی داره که اینجا چون اینت هست اما در بالا استرینگ را کال کرده لذا خودبخود مقدارش را صفر میدهد انگار اصلا آرگومانی ندارد.
func sum(s string, x ...int) int {
	fmt.Println(x)
	fmt.Printf("%T\n", x)
	fmt.Println(len(x))
	fmt.Println(cap(x))

	sum := 0
	for i, v := range x {
		sum += v
		fmt.Println("for item in index position", i, "we are now adding", v, "to the total which is now", sum)
	}
	fmt.Println("The total is", sum)
	return sum
}

/// func (r receiver) identifier(parameter(s)) (return(s)) { code}
