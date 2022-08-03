//کلیدواژه make که یک builtin function  (ساختار از پیش ساخته شده) است که برای اینکه طول و ظرفیت اسلایس را مشخص کنیم از آن استفاده میشود. به این صورت نوشته میشود:
// make( [ ] T, length, capacity)
// make([ ]int, 50, 100)
// Yoones
package main

import (
	"fmt"
)

func main() {

	x := make([]int, 10, 100)
	fmt.Println(x)
	fmt.Println(len(x))
	fmt.Println(cap(x))

}
