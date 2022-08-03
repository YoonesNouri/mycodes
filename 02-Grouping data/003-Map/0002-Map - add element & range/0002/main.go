//اضافه کردن slice
// Yoones
package main

import "fmt"

func main() {

	m := map[string]int{
		"mahdiyar": 1,
		"sn":       2,
	}
	fmt.Println(m)
	fmt.Println(m["mahdiyar"])
	fmt.Println(m["sn"])
	fmt.Println(m["yoones"])
	v, ok := m["yoones"]
	fmt.Println(v)
	fmt.Println(ok)
	//adding
	m["todd"] = 33
	if v, ok := m["mahdiyar"]; ok {
		fmt.Println("چون مهدیار در مپ آمده بود لذا درست است و پرینت میکند", v)
	}
	//ranging
	for k, v := range m {
		fmt.Println(k, v)
	}
	xi := []int{4, 5, 6, 7}
	for i, v := range xi {
		fmt.Println(i, v)
	}
}
