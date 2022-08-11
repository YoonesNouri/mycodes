//نکته: فرق بین length و capacity: در length تعداد indexها یا همان پوزیشن¬ها نمایش داده میشود
//ولی در capacity اگر مثلا یک index خودش یک slice با طول 5 باشد،
//آن را مثل length یک دانه حساب نمی¬کند بلکه 5 دانه حساب میکند. مثلا:
//اگر slice با نام x دارای 5 index باشد؛ x := []int{1, 2, 3, 4, 5} در اینجا طول و ظرفیت هر دو پنج است
//اما اگر slice با نام y دارای 2 index باشد؛ y := append(x,9) ، طول آن 2 است چون 2 index دارد
//اما ظرفیت آن 6 است زیرا x ظرفیتش 5  است و Y ظرفیتش 5+1 است.
//نکته: اگر با اضافه کردن، طول length یک slice ، از ظرفیت capacity آن بیشتر شود، مقدار cap را بطور خودکار دوبرابر میکند: مثال:
// Yoones
package main

import (
	"fmt"
)

func main() {

	x := make([]int, 10, 10)
	fmt.Println(x)
	fmt.Println(len(x))
	fmt.Println(cap(x))
	x[0] = 7
	x[9] = 14
	fmt.Println(x)
	x = append(x, 72)
	fmt.Println(len(x))
	fmt.Println(cap(x))

}
