//دستورات مختلف و کاربرد آنها:
//● gofmt
//○ formats go code ○ کد را قالب بندی میکند.
//● govet
//○ reports suspicious constructs ○ سازه های مشکوک را گزارش می کند
//● golint
//○ reports poor coding style ○ سبک کدنویسی ضعیف را گزارش می دهد
//
//با استفاده از دستور golint آن ساختارهای اضافی که کد را ضعیف و غیرخوانا میکند را در output گزارش میدهد.
//اگر دستور go get -u github.com/golang/lint/golint را در cmd کامپیوتر اجرا کنیم، golint میاد روی کامپیوتر.
//در ترمینال دستور ./… یعنی در این شاخه و هرشاخه ای پایین تراز اون.
package main

import "fmt"

func main() {
	fmt.Println(' ')
}
