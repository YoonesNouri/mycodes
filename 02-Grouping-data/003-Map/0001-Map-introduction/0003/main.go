//برای چک کردن اینکه آیا فلان value در مپ آمده یا نه؟ از کلیدواژه¬ی ok استفاده میکنیم.
// Yoones
package main

import "fmt"

func main() {

	m := map[string]int{
		"mahdiyar": 0,
		"sn":       1,
	}
	fmt.Println(m)
	fmt.Println(m["sn"])
	fmt.Println(m["yoones"])
	v, ok := m["yoones"]
	fmt.Println(v)
	fmt.Println(ok)
}

//وقتی false میدهد یعنی در map نیامده است.
