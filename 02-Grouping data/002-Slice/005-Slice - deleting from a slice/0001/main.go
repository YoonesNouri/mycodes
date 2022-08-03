//برای حذف کردن در واقع از یک نوع فن کد نویسی استفاده میکند و با همان slicing a slice که بوسیله colon (دو نقطه : ) بازه ی مقادیر را مشخص میکرد، یک append جدید معرفی میکند و مقادیری را که میخواهد نباشد را در بازه قرار نمیدهد، مثل کد زیر:
// Yoones میخوام 16 و 17 رو حذف کنم.
Package main

import "fmt"

func main() {

x := []int{11, 12, 13, 14, 15}
fmt.Println(x)
x = append(x, 16, 17, 18, 19)
fmt.Println(x)
y := []int{22, 33, 44}
x = append(x, y...)
fmt.Println(x)
x = append(x[:5], x[7:]...)
fmt.Println(x)
}
