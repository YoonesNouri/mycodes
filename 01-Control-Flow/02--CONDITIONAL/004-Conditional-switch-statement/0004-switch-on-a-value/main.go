//روی یک متغیر خاص میشود switch کرد و یا چند متغیر را به یک case معرفی کرد که باید بینشان , کاما باشد.
//switch on a value

package main

import (
	"fmt"
)

func main() {
	n := "Bond"
	switch n {
	case "Moneypenny":
		fmt.Println("miss money")
	case "Bond":
		fmt.Println("bond james")
	case "Q":
		fmt.Println("this is q")
	default:
		fmt.Println("this is default")
	}
}
