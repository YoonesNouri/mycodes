//ترتیب تابع یا همان function به این صورت است:
//func (receiver)  identifier( parameters) (returns ) { code }
//این جایگاه ها میتوانند خالی بمانند ولی حداقل یک پرانتز و یک گیومه باید داشته باشد هر چند خالی، مثلِ func main() {}
//دلیل اصلی فانکشن¬ها این است که کد، تکه¬تکه (modular= پیمانه ای) باشد و مثل اسپاگتی یک رشته¬ی دراز نباشد تا خواندنش آسان¬تر باشد.
//هدف توابع:
//● کد انتزاعی
//● قابلیت استفاده مجدد کد
//آیدنتیفایر یا معرِّف، همان نامی است که برای فانکشن انتخاب میکنیم و با آن نام ، آن فانکشن را میشناسیم و کال(اجرا) میکنیم.
//  (ورودی اطلاعات=parameters) در این جایگاه معمولا type و متغیر نوشته میشود مثل int یا string یا ... اگر multiple باشد: (x, y int, z,t string)
//  (خروجی اطلاعات=returns)کلیدواژه است. اگر بخواهیم چند تایپ را ریترن کنیم باید داخل پرانتز و با کاما , باشد مثل: (int, string) که به آن multiple return میگویند.
// ریترن در آخر فانکشن باعث بسته شدن اون فانکشن و خارج شدن از اون فانکشن میشه. چون داره دستور میده برگرد و برو بیرون و خروجی رو اجرا کن.
package main

import (
	"fmt"
)

func main() {
	foo()
	bar("James")
}

func foo() {
	fmt.Println("hello from foo")
}

// everything in Go is PASS BY VALUE
func bar(s string) {
	fmt.Println("Hello,", s)
}
