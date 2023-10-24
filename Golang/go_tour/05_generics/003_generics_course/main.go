// https://www.youtube.com/watch?v=WpTKqnfp5dY
// جرنیک برای جلوگیری از تکرار کد استفاده میشه
package main

import (
	"fmt"
	//"golang.org/x/exp/constraints"
)

type CustomMap[T comparable, V int | string] map[T]V 
// comparable: a == b یعنی هر تایپی که با خودش مقایسه بشه و مساوی باشه


func main() {

	m:= make(CustomMap[int,string])
	m[3] = "3"
    fmt.Printf("result: %+v\n", m)
}

