package main

import "fmt"

// تعریف یک تابع جنریک برای مقایسه دو مقدار
func isEqual(a, b interface{}) bool {
    return a == b
}

func main() {
    // استفاده از تابع isEqual برای مقایسه دو عدد صحیح
    fmt.Println(isEqual(5, 5))   // true
    fmt.Println(isEqual(3, 7))   // false

    // استفاده از تابع isEqual برای مقایسه دو رشته
    fmt.Println(isEqual("hello", "hello"))    // true
    fmt.Println(isEqual("world", "golang"))   // false
}
