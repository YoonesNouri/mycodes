//کلمه append به معنی اضافه کردن است، که به آن builtin function میگویند
//و در Godoc در قسمت package builtin آمده، که یعنی ساختارها و دستورات از پیش ساخته شده.
//با کلیدواژه append میتوانیم به یک slice یک slice دیگر را هم اضافه کنیم.
//نکته: مقادیر append باید داخل پرانتز باشند و با مساوی قرار دادن با متغیر اسلایسی باید این کار انجام شود، مانند کد زیر:
// Yoones
package main

import "fmt"

func main() {

	x := []int{11, 12, 13, 14, 15}

	x = append(x, 16, 17, 18, 19)

	fmt.Println(x)
}
