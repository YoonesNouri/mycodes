//yoones ویدیو 180 Printing & logging (ص73 جزوه1)
//log.Fatalln

package main

import (
	"fmt"
	"log"
	"os"
)

func main() {
	defer foo()
	_, err := os.Open("no-file.txt")
	if err != nil {
		//fmt.Println("error happened", err)
		//log.Println("error happened", err)
		log.Fatalln(err)
		//panic(err)
	}

	// Fatalln is equivalent to Println() followed by a call to os.Exit(1).
	//The Fatal functions call os.Exit(1) after writing the log message.
}
func foo() {
	fmt.Println("when os.Exit() is called, deferred functions don't run")
}
