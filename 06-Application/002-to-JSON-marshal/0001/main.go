//JSON = JavaScript Object Notation
//
//مارشالینگ marshaling یعنی نمایش حافظه¬ی یک شیء(object) را به داده(data) تبدیل کنیم و معکوسش میشه آنمارشالینگ .
//و معمولاً زمانی استفاده می شود که داده ها باید بین قسمت های مختلف یک برنامه کامپیوتری یا از یک برنامه به برنامه دیگر منتقل شوند.
//دستور مارشال کردن اینگونه json.Marshal(value) است. M بزرگ
//خلاصه:
//
//Marshaling   -->  converts Go to JSON
//Unmarshaling -->  converts JSON to Go
//
//توضیح: در سایت گولنگ https://pkg.go.dev/github.com/tendermint/tendermint/libs/json
//
//به آدرس مذکور دستور زبان marshal را اینگونه نوشته است:
//
//func Marshal(v interface{}) ([]byte, error)
//
//یک پارامتر ورودی دارد و دو خروجی که ورودی باید از تایپ []person (در واقع اسلایس استراکت است) باشد که داخل پرانتز json.Marshal() بهش دادیم
//و خروجی که در واقع همان مارشال شده¬ی ورودی است را چون دو تا است به دو تا متغیر میدهیم که تایپ اولی []byte است که برای پرینت باید کانورت به string شود
//و دومی ارور است که با if باید پرینت شود.
package main

import (
	"encoding/json"
	"fmt"
)

type person struct {
	First string
	Last  string
	Age   int
}

func main() {
	p1 := person{
		First: "James",
		Last:  "Bond",
		Age:   32,
	}

	p2 := person{
		First: "Miss",
		Last:  "Moneypenny",
		Age:   27,
	}

	people := []person{p1, p2}

	fmt.Println("Golang = ", people)

	bs, err := json.Marshal(people)
	if err != nil {
		//nil in Go means a zero value for pointers, interfaces, maps, slices, and channels.
		//It means the value is uninitialized
		//کلمه ی nil در Go به معنای یک مقدار صفر برای نشانگرها، رابط ها، نقشه ها، برش ها و کانال ها است. به این معنی است که مقدار نامشخص است
		fmt.Println(err)
	}
	fmt.Println("JSON = ", string(bs))
}
