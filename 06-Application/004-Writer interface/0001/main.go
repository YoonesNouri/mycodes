//اینکد encode یعنی کدگذاری کردن و به writerِ Golang دادن یعنی این کد رو داخل اون خروجی بنویس یعنی از Golang خارج میشه. مثل marshal
//دیکد decode یعنی از کدگذاری درآوردن و قابل خواندن کردن و به readerِ Golang دادن یعنی این کد رو بخون و به Golang وارد میشه. مثل unmarshal
//
//نکته: تایپ Writer و Reader (با حرف اول بزرگ) ، هردو از تایپ ِ interface هستند.
//
//type Writer interface {
//    Write(p []byte) (n int, err error)
//}
//
//type Reader interface {
//    Read(p []byte) (n int, err error)
//}
//
//و هر تایپ دیگری که به تایپ Writer یا Reader اتچ شود(در receiver) از تایپ ِ Writer یا Reader است.
//
//در پکیج fmt به این آدرس: https://pkg.go.dev/fmt#example-Println
//فانکشن Println به این صورت هست:
//
//func Println(a ...any) (n int, err error)
//
//و وقتی Println رو باز میکنی به کد اصلی که برنامه نویسان گولنگ آنرا نوشته اند میرود در پکیج os در آنجا این کد را مشاهده میکنیم:
//
//Fprintln(os.Stdout, a...)
//
//نکته: تایپ os.Stdout از *file است به این معنی که یک متود داره که بهش اتچ شده.
//که همان کار Println رو انجام میده.
package main

import (
	"fmt"
	"os"
)

func main() {
	fmt.Println("Hello, 世界")
	fmt.Fprintln(os.Stdout, "Hello, 世界")
}
