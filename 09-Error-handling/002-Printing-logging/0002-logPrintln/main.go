//yoones ویدیو 180 Printing & logging (ص73 جزوه1)
//log.Println

package main

import (
	"log"
	"os"
)

func main() {
	_, err := os.Open("no-file.txt")
	if err != nil {
		//fmt.Println("error happened", err)
		log.Println("error happened", err)
		//log.Fatalln(err)
		//panic(err)
	}

}
