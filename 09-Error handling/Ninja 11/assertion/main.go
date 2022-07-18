// assertion

package main

import "fmt"

func main() {
	p1 := person{first: "James"}
	saying(p1)
}

type person struct{ first string }

func (p person) speak() {}

type men interface{ speak() }

func saying(m men) {
	fmt.Println(m.first)
	//fmt.Println(m.(person).first)
}
