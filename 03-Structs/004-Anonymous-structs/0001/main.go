// Yoones Anonymous struct ویدیو 103
package main

import "fmt"

//کد با نام استراکت بصورت عبارت توضیحی آمده، حالا ما تبدیلش کردیم به استراکت بدون نام، چطوری؟
//اومدیم اون چیزی که مساوی با پرسون بود رو گذاشتیم بجای پرسون، یعنی بجای اسمش، مقادیرشو گذاشتنیم
//نکته: دو تا گیومه ای که مقادیر پرسون و مقادیر متغیرها است در یک سطر باید باشد وگرنه ارور میده
//type person struct {
//	first string
//	last  string
//	age   int
//}
//func main() {
//	p1 := person {
//		first: "james",
//		last:  "bond",
//		age:   32,
//	}

func main() {
	p1 := struct {
		first string
		last  string
		age   int
	}{
		first: "james",
		last:  "bond",
		age:   32,
	}
	fmt.Println(p1)
}

//هر دو کد یک چیز را پرینت میکند.
