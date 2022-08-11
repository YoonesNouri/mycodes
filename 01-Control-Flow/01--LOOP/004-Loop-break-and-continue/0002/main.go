//این کد اعداد زوج بین 0 تا 100 را پرینت میکند اما اگر بجای continue ، break بذاریم فقط عدد 2 که همان دور اول لوپ است را پرینت میکند که در زیر آمده:

package main

import (
	"fmt"
)

func main() {
	x := 1
	for x < 100 {

		x++
		if x%2 != 0 {
			break
		}

		fmt.Println(x)

	}
	fmt.Println("done.")
}
