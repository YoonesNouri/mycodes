//نکته: میزان سختی پسورد با این دستور اجرا میشه:
//
//const (
//	MinCost     int = 4  // the minimum allowable cost as passed in to GenerateFromPassword
//	MaxCost     int = 31 // the maximum allowable cost as passed in to GenerateFromPassword
//	DefaultCost int = 10 // the cost that will actually be set if a cost below MinCost is passed into GenerateFromPassword
//)
//همان کد قبلی با DefaultCost:
// yoones imported "golang.org/x/crypto/bcrypt" manually
package main

import (
	"fmt"

	"golang.org/x/crypto/bcrypt"
)

func main() {
	s := `password123`
	bs, err := bcrypt.GenerateFromPassword([]byte(s), bcrypt.DefaultCost)
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
