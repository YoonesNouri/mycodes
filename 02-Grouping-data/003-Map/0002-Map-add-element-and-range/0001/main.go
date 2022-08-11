//برای اضافه کردن یک value به map از m[“todd”] = 14  استفاده می¬شود.
//که k نماد string که اول حرف key است و v نماد value است
//و i اول index پوزیشن اسلایس است.(حروف k و v و i موضوعیت ندارند)
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
}
