//نکته: آن stringای که میخواهد بر اساس حرف اول آن sort کند را با هایلایت زرد مشخص کرده ام
//که داخل فانکشنی که ایمپورت کرده خودش نسبت به نیازش تغییر داده.
//مثلا First باشه ، Last باشه یا هر فیلدی که استراکت داره و از تایپ string هست میتونه باشه
package main

import (
	"fmt"
	"sort"
)

type Person struct {
	First string
	Age   int
}

type ByName []Person

func (bn ByName) Len() int           { return len(bn) }
func (bn ByName) Swap(i, j int)      { bn[i], bn[j] = bn[j], bn[i] }
func (bn ByName) Less(i, j int) bool { return bn[i].First < bn[j].First }

func main() {
	p1 := Person{"James", 32}
	p2 := Person{"Moneypenny", 27}
	p3 := Person{"Q", 64}
	p4 := Person{"M", 56}

	people := []Person{p1, p2, p3, p4}

	fmt.Println(people)
	sort.Sort(ByName(people))
	fmt.Println(people)

}
