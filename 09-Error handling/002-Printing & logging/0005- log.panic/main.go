//yoones ویدیو 180 Printing & logging (ص73 جزوه1)
//log.panic

package main

import (
	"os"
)

func main() {
	_, err := os.Open("no-file.txt")
	if err != nil {
		//fmt.Println("error happened", err)
		//log.Println("error happened", err)
		//log.Fatalln(err)
		panic(err)
	}

}
