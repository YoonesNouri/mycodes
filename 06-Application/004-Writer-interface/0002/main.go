//در پکیج io هم در قسمت WriteString به این فانکشن پرینت میرسیم:
//
//func WriteString(w Writer, s string) (n int, err error)
//
//که با آن هم میتوان پرینت کرد
package main

import (
	"fmt"
	"io"
	"os"
)

func main() {
	fmt.Println("Hello, 世界")
	fmt.Fprintln(os.Stdout, "Hello, 世界")
	io.WriteString(os.Stdout, "Hello, 世界")
}
