//a new underlying array is allocated یک آرایه زیربنایی جدید اختصاص داده شده است
package main

import (
	"fmt"
)

func main() {
	x := []int{42, 43, 44, 45, 46, 47, 48, 49, 50, 51}
	fmt.Println(x)

	y := append(x, 43, 43, 43, 43, 43, 43, 44) // new underlying array allocated

	fmt.Println(x)
	fmt.Println(y)
}
