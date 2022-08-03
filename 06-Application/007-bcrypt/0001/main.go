//بی کریپت bcrypt یکی از ابزارهایی است که می توانید با رمزگذاری از داده های کاربران محافظت کنید.
//تاد رفت به این آدرس https://pkg.go.dev/golang.org/x/crypto/bcrypt و از پکیج bcrypt
//
// GenerateFromPassword(password []byte, cost int) ([]byte, error)
//CompareHashAndPassword(hashedPassword, password []byte) error
//
//رو کپی کرد. و پکیج bcrypt  رو ایمپورت کرد و قسمت¬های اضافیشو پاک کرد.
// به این صورت:
//
// bcrypt. GenerateFromPassword([]byte(s), bcrypt.MinCost)
//bcrypt.CompareHashAndPassword(hashedPassword, password []byte) error
//
//و ادامه داستان... که نتونست در Golang playground پکیج bcrypt رو ایمپورت کنه و از برنامه Goland استفاده کرد.
//اما من خودم بصورت دستی "golang.org/x/crypto/bcrypt" رو از سربرگ آدرس پیکج bcrypt کپی کردم و در قسمت ایمپورت کد پیست کردم و جواب داد.
//
//کد مکلئود که پیکج bcrypt رو خودم دستی ایمپورت کردم (1تیر1401):
//
//https://go.dev/play/p/oFc4HlU3R-_B
// yoones imported "golang.org/x/crypto/bcrypt" manually
package main

import (
	"fmt"

	"golang.org/x/crypto/bcrypt"
)

func main() {
	s := `password123`
	bs, err := bcrypt.GenerateFromPassword([]byte(s), bcrypt.MinCost)
	if err != nil {
		fmt.Println(err)

	}
	fmt.Println(s)
	fmt.Println(bs)

	loginpword1 := `password123`
	err = bcrypt.CompareHashAndPassword(bs, []byte(loginpword1))
	if err != nil {
		fmt.Println(`you cant login`)
		return
	}
	fmt.Println(`you are logged in`)
}
