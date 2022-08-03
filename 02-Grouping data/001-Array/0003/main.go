//_ با اضافه کردن len که بمعنی length است میتوان طول آنرا پرینت کرد.
//که باید x داخل پرانتز باشد. به این صورت: fmt.Println(len(x))
// array خیلی در Golang کاربرد ندارد و slice کاربردی تر است که در بعدا میاید.
// Yoones
package main

import "fmt"

var x [5]int

func main() {

	fmt.Println(x)
	x[3] = 42
	fmt.Println(x)
	fmt.Println(len(x))
}
