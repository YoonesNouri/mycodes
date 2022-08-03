//سورت یک پکیج کامل است که اسلایس¬ها را مرتب (سورت) میکند.
//مثلا اعداد درهم را پشت¬سرهم میکند sort.Ints() و یا کلمات را بر اساسِ ترتیبِ حرف اولشان در حروف الفبا مرتب میکند sort.Strings()
//هر پیکجی در واقع یک فانکشن هست که با آوردن اسم پکیج سپس نقطه سپس اسم فانکشن مربوطه (با حرف اول بزرگ) اون رو ایمپورت (import = وارد) میکنی.
//از پکیج سورت به آدرس زیر میتونی دستور سورت  کردن هر تایپ رو ببینی؛
//آدرس: https://pkg.go.dev/sort
package main

import (
	"fmt"
	"sort"
)

func main() {
	xi := []int{4, 7, 3, 42, 99, 18, 16, 56, 12}
	xs := []string{"James", "Q", "M", "Moneypenny", "Dr. No"}

	fmt.Println(xi)
	sort.Ints(xi)
	fmt.Println(xi)

	fmt.Println("------")
	fmt.Println(xs)
	sort.Strings(xs)
	fmt.Println(xs)

}
