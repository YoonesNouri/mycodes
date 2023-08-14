package main

import (
	"fmt"
	"mymodule/1_bluebot/10_Refactoring/router"
)

func main() {
	fmt.Println("Welcome to the webserver")
	e := router.New()

	e.Start(":8000")
}


