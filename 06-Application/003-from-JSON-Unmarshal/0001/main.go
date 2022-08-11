//سایتهای عالی :
//
//● http://rawgit.com/
//● https://mholt.github.io/json-to-go/ تبدیل جیسون به گو
//● https://github.com/goestoeleven آیدی خود مکلئود
//
//توضیح: در سایت گولنگ https://pkg.go.dev/github.com/tendermint/tendermint/libs/json
//
//به آدرس مذکور دستور زبان unmarshal را اینگونه نوشته است:
//
//func Unmarshal(bz []byte, v interface{}) error
//
//دو تا پارامتر ورودی دارد و یک خروجی که ورودی¬ها اولی باید از تایپ []byte باشد که باید کانورت شود (که در کد پایین کانورت شده []byte(s) ) و
//دومی باید آدرس آن باشد که یعنی هر چی در این آدرس است را تغییر بده و تبدیل کن به گولنگ و آدرس از تایپ interface{} است
//که باید & را در ابتدای تایپی که زیر مجموعه¬ی تایپ []person(که در واقع اسلایس استراکت است) است (که people نامگذاری شده) بنویسیم
//و یک خروجی که باید با if نوشته شود که در کد پایین آمده.
package main

import (
	"encoding/json"
	"fmt"
)

type person struct {
	First string `json:"First"`
	Last  string `json:"Last"`
	Age   int    `json:"Age"`
}

func main() {
	s := `[{"First":"James","Last":"Bond","Age":32},{"First":"Miss","Last":"Moneypenny","Age":27}]`
	bs := []byte(s)
	fmt.Printf("%T\n", s)
	fmt.Printf("%T\n", bs)

	var people []person

	err := json.Unmarshal(bs, &people)
	if err != nil {
		fmt.Println(err)
	}

	fmt.Println("\nall of the data", people)

	for i, v := range people {
		fmt.Println("\nPERSON NUMBER", i)
		fmt.Println(v.First, v.Last, v.Age)
	}
}

//ابتدا در سایت  https://mholt.github.io/json-to-go/ گولنگ را تبدیل به جیسون کرده بود
//و سپس کد جیسون را آنجا آورد و در گیومه ی type person struct{} گذاشت تا از جیسون به گولنگ تبدیل کند یا همان آنمارشال کند.
