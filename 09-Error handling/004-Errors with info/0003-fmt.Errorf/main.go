package main

import (
	"fmt"
	"log"
)

func main() {
	_, err := sqrt(-10)
	if err != nil {
		log.Fatalln(err) //if it catches error shuts the code down.
	}
}

func sqrt(f float64) (float64, error) {
	if f < 0 {
		return 0, fmt.Errorf("norgate math: square root of negative number", "%v", f)
		//f in fmt.Errorf stands for "format printing"
	}
	return 42, nil
}
