//ریترن return یعنی func را تمام کن برگرد به func main و اجرا کن.
//و اگر جلوی return یک متغیر باشد یعنی func را تمام کن و این متغیر را برگردان به func main و اجرا کن.
//
//به فانکشنی که در قسمت ریترنش یک متغیر باشد Named return function گفته میشود،
//در آن صورت وقتی کال میشه بجای اسم اون متغیر هر اسم دیگر میتوان استفاده کرد،
//مثلا اگر در ریترن فانکشن از x استفاده شده در کال میشه از y استفاده کرد.
package main

import (
	"fmt"
)

func main() {
	s1 := foo()
	fmt.Println(s1)
}

func foo() string {
	s := "Hello world"
	return s
}
