//در واقع struct of struct رو در این ویدیو داره نشون میده که typeِ struct کد ویدیوی قبلی (در کد خودم personal)
//رو میذاره درونِ یه struct جدیدِ دیگه (کد مکلئود secretagent). تمام نکته ی این ویدیو این است که وقتی داریم struct of struct را معرفی میکنیم
//باید در محدوده¬ی func main برای هر متغیری یک type معرفی کنیم. از جمله  typeِ struct قبلی درونِ structِ جدید هست.
//پس برای این کار باید متغیر بیرونی تر (sa1) را ابتدا بنویسیم، و تایپ بیرونی¬تر (secretagent) را قبل از گیومه بنویسیم،
//و در گیومه اش personal : personal{} , رو بعنوان متغیر و تایپِ درونی تر بنویسیم که یعنی متغیر personal از تایپِ  personal{} است.
// Yoones Embedded struct
package main

import "fmt"

type personal struct {
	first string
	last  string
	age   int
}
type secretagent struct {
	personal
	first string
	ltk   bool // ltk = licence to kill
}

func main() {
	sa1 := secretagent{
		personal: personal{
			first: "James",
			last:  "Bond",
			age:   32,
		},
		first: "something", //yoones: اگر این سطر نبود چه در پرینت پرسونال را می نوشتیم چه نمی نوشتیم فرقی در نتیجه نداشت و هردو "جمیز" را پرینت میکرد
		ltk:   true,
	}
	fmt.Println(sa1)
	fmt.Println(sa1.first, sa1.personal.first, sa1.last, sa1.age, sa1.ltk)

	//yoones: (sa1.first) means: I want the first that is the inner type of just sa1.
	//yoones: (sa1.personal.first) means: I want the first that is the inner type of personal that is the inner type of sa1.

	p2 := personal{
		first: "Mahdiyar",
		last:  "Nouri",
		age:   2,
	}
	fmt.Println(p2)
	fmt.Println(p2.first, p2.last, p2.age)
}
