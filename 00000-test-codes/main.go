// Yoones
package main

import "fmt"

func main() {

	m := map[string]int{
		"mahdiyar": 1,
		"sn":       2,
	}
	fmt.Println(m)
	delete(m, "sn")
	fmt.Println(m)
	delete(m, "yoones")
	fmt.Println(m)
}
