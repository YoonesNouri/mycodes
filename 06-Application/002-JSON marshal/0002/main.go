//کد مکلئود با حروف کوچک فیلدها که جیسون را خالی میده:
package main

import (
	"encoding/json"
	"fmt"
)

type person struct {
	first string
	last  string
	age   int
}

func main() {
	p1 := person{
		first: "James",
		last:  "Bond",
		age:   32,
	}

	p2 := person{
		first: "Miss",
		last:  "Moneypenny",
		age:   27,
	}

	people := []person{p1, p2}

	fmt.Println("Golang = ", people)

	bs, err := json.Marshal(people)
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println("JSON = ", string(bs))
}
