//yoones ویدیو 180 Printing & logging (ص73 جزوه1)
//log set output

package main

import (
	"fmt"
	"log"
	"os"
)

func main() {
	f, err := os.Create("log.txt")
	if err != nil {
		fmt.Println(err)
	}
	defer f.Close()
	log.SetOutput(f)

	f2, err := os.Create("no-file.txt")
	if err != nil {
		//fmt.Println("error happened", err)
		log.Println("error happened", err)
		//log.Fatalln(err)
		//panic(err)	}
		defer f2.Close()

		fmt.Println("check the log.txt file in the directory")

	}
}
