//برای مرتب کردن فیلدهای داخل استراکت از sort.Sort(ByAge)  و sort.Sort(ByName)  استفاده میکنیم.
//اولا: همه باید حرف اولشون کپیتال باشه بخاطر ایمپورت شدن از پکیج،
//دوما: ByAge و people هر دو از تایپ ِ []Person هستند و لذا میتوان تایپ ِ یکی را کانورت به دیگری کرد
//که با این عبارت ByAge(people) تایپ people را به تایپ ByAge کانورت کرد
//تا از آن در کد استفاده کند و هر متودی که ByAge میگیرد از این به بعد people هم میگیرد.
package main

import (
	"fmt"
	"sort"
)

type Person struct {
	First string
	Age   int
}

type ByAge []Person

func (a ByAge) Len() int           { return len(a) }
func (a ByAge) Swap(i, j int)      { a[i], a[j] = a[j], a[i] }
func (a ByAge) Less(i, j int) bool { return a[i].Age < a[j].Age }

func main() {
	p1 := Person{"James", 32}
	p2 := Person{"Moneypenny", 27}
	p3 := Person{"Q", 64}
	p4 := Person{"M", 56}

	people := []Person{p1, p2, p3, p4}

	fmt.Println(people)
	sort.Sort(ByAge(people))
	fmt.Println(people)

}
