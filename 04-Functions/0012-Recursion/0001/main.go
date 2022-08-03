//هر کاری که با recursion میشه انجام داد با for هم بعنوان loop میشه انجام داد.
//در واقع Recursion وقتی اتفاق میوفته که یک فانکشن خودشو کال کنه. (به تعداد مشخص و سپس توقف کنه)
//
//کد خودم عمل ریاضی فاکتوریل با recursion:
//yoones ویدیو 120
//Returning a recursion: (ص52 جزوه)
//fac = factorial فاکتوریل با ریکرژن
package main

import "fmt"

func main() {

	fmt.Println(4 * 3 * 2 * 1)
	n := fac(4)
	fmt.Println(n)
}
func fac(n int) int {
	if n == 1 {
		return 1
	}
	return n * fac(n-1)
}
