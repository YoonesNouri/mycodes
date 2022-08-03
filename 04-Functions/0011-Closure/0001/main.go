//تدبیری که در آن تا جایی که میشود اسکوپ و محدوده¬ی متغیر را نزدیک به محدوده¬ی یک کد مشخص نگه میداریم. و تا میشود اسکوپ را باریک نگه میداریم.
//نکته مهم همیشگی:
// variables declared in the outer scope are accessible in inner scopes
//متغیرهایی که در اسکوپ/محدوده ی (که معمولا با گیومه {} معلوم میشوند و علائم دیگر هم هست) بیرونی تر معرفی شوند، در اسکوپ درونی تر هم شناخته شده و دردسترس هستند،
//اما اگر در اسکوپ درونی¬تر معرفی شوند، در اسکوپ بیرونی¬تر دردسترس نیستند و فقط داخل همان اسکوپ دردسترس-اند.
//scope of x:
package main

import (
	"fmt"
)

var x int

func main() {
	fmt.Println(x)
	x++
	fmt.Println(x)
	foo()
	fmt.Println(x)
}

func foo() {
	fmt.Println("hello")
	x++
}
