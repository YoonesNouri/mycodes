//کد مکلئود برای بدون متغیر:

package main

import (
	"fmt"
)

func main() {
	if true {
		fmt.Println("0001")
	}

	if false {
		fmt.Println("002")
	}

	if !true {
		fmt.Println("002-Slice-for-range")
	}

	if !false {
		fmt.Println("004")
	}

	if 2 == 2 {
		fmt.Println("005")
	}

}
