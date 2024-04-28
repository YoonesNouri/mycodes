package main

import "fmt"

func main() {
    // Creating a map with an initial capacity of 10
    m := make(map[string]int, 10)
    m["one"] = 1
    m["two"] = 2
    fmt.Println("Map:", m)
}
