//yoones ویدیو 111 Unfurling a slice (ص50 جزوه1)
//نکته: وقتی سه نقطه …T  قبل از یک type باشد معنایش این است که تعداد نامحدودی از مقادیر از این type را میگیرد .
//نکته: وقتی سه نقطه بعد از یک متغیر بیاید x… یعنی مقادیری که متغیر x دارد را بگیر و در اینجا بگذار.(در مبحث slice) اگر سه نقطه نگذاریم ارور میدهد.
//چون با سه نقطه میگوییم که این هم از تایپِ همان اسلایس است و مثلا تایپ int خالی نیست.
//در واقع سه نقطه بجای کروشه ای که در اسلایس استفاه میشود می آید. در ارورش هم همین را تذکر میدهد.
package main

import (
	"fmt"
)

func main() {
	xi := []int{2, 3, 4, 5, 6, 7, 8, 9}
	//اینجا باید سه نقطه بذاریم چون داریم یک اسلایس اینت رو کال میکنیم، به این میگوید آنفرلینگ یعنی گشودن که یعنی مقادیر اسلایس را کال میکند نه کل اسلایس را
	x := sum(xi...)
	fmt.Println("The total is", x)
}

func sum(x ...int) int { // اینجا هم از کد قبل سه نقطه داشته
	fmt.Println(x)
	fmt.Printf("%T\n", x)

	sum := 0
	for i, v := range x {
		sum += v
		fmt.Println("for item in index position", i, "we are now adding", v, "to the total which is now", sum)
	}
	fmt.Println("The total is", sum)
	return sum
}

/// func (r receiver) identifier(parameter(s)) (return(s)) { code}
