//نکته: اگر دستور دهیم مقدار یک متغیر را که در مپ نیامده است را پرینت کند به صورت دیفالت مقدار 0 را پرینت میکند.
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
}
