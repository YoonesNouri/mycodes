//با interface/رابط (که کلیدواژه است و خودش یک تایپ است) میتوانیم polymorphism/چندریختی داشته باشیم.
//با interface میتوانیم رفتار کد را توضیح بدهیم.
//“ a VALUE can be of more than one TYPE”
//این جمله را مکلئود چندین بار تکرار کرد: «یک ولیو میتواند متعلق به چندین تایپ باشد» که در واقع همان «چندریختی» است.
//متود که خودش در واقع یک فانکشن اتچ شده به یک تایپ است، حالا تایپ جدیدی را با interface به این صورت معرفی میکنیم:
//
//type human interface {
//speak()
//}
//
//که تایپ جدید human است که خودش از تایپ ِ  interface  است و متود speak است،
//حالا وقتی speak به هر تایپی اَتَچ بشه(یعنی فانکشنی که ریسیورش آن تایپ مشخص است و اسم فانکشن که identifier هست درواقع همون متود هست)،
//آن تایپ، تحت تایپ ِ human هم هست.
//خلاصه: هر تایپی که این متود را داشته باشه تحت تایپی که متود تحت interface ِ آن تایپ هست هم هست. میشه یک ولیو و چند تایپ، میشه چندریختی.
package main

import (
	"fmt"
)

type person struct {
	first string
	last  string
}

type secretAgent struct {
	person
	ltk bool
}

func (s secretAgent) speak() {
	fmt.Println("I am", s.first, s.last, " - the secretAgent speak")
}

func (p person) speak() {
	fmt.Println("I am", p.first, p.last, " - the person speak")
}

type human interface {
	speak()
}

func bar(h human) {
	switch h.(type) {
	case person:
		fmt.Println("I was passed into barrrrrr", h.(person).first)
	case secretAgent:
		fmt.Println("I was passed into barrrrrr", h.(secretAgent).first)
	}
	fmt.Println("I was passed into bar", h)
}

type hotdog int

func main() {
	sa1 := secretAgent{
		person: person{
			"James",
			"Bond",
		},
		ltk: true,
	}

	sa2 := secretAgent{
		person: person{
			"Miss",
			"Moneypenny",
		},
		ltk: true,
	}

	p1 := person{
		first: "Dr.",
		last:  "Yes",
	}

	fmt.Println(sa1)
	sa1.speak()
	sa2.speak()

	fmt.Println(p1)

	bar(sa1)
	bar(sa2)
	bar(p1)

	// conversion
	var x hotdog = 42
	fmt.Println(x)
	fmt.Printf("%T\n", x)
	var y int
	y = int(x)
	fmt.Println(y)
	fmt.Printf("%T\n", y)

}
