// کد یونس
package main

import "fmt"

func main() {
	x := 42
	switch {
	case false:
		fmt.Println("این اجرا نمیشه")
	case true:
		fmt.Println(x + 1)
		fallthrough
	case (2 == 2):
		fmt.Println(`چون بالا فالثرو داره این خط هم پرینت میشه، وگرنه نمیشد ، میتونی خودت حذفش کنی تا نتیجه شو ببینی`)
		fallthrough
	case false:
		fmt.Println(" این دستور هم از نظر منطقی نباید اجرا بشه چون غلط «فالس» هست اما چون قبلش «فال ثرو» داره اجرا میشه هه هه ، همینی که هست میخوای بخواه نمیخوای بازم بخواه")

	}
}
