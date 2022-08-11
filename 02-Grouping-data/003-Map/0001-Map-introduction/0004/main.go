//چون خروجی ok، true یا false است،
//از آن میتوان در if که نیاز به true یا false یا چیزی در حکم آندو را دارد، استفاده کرد.
// Yoones
package main

import "fmt"

func main() {

	m := map[string]int{
		"mahdiyar": 1,
		"sn":       2,
	}
	fmt.Println(m)
	fmt.Println(m["sn"])
	fmt.Println(m["yoones"])
	v, ok := m["yoones"]
	fmt.Println(v)
	fmt.Println(ok)
	if v, ok := m["mahdiyar"]; ok {
		fmt.Println("چون مهدیار در مپ آمده بود لذا درست است و پرینت میکند", v)
	}
	if v, ok := m["yoones"]; ok {
		fmt.Println("چون یونس در مپ آمده بود لذا درست است و اجرا و به تبعش پرینت نمیکند", v)
	}
}
