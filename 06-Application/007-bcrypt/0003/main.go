//همان کد قبلی با MaxCost:
// yoones imported "golang.org/x/crypto/bcrypt" manually
package main

import (
	"fmt"

	"golang.org/x/crypto/bcrypt"
)

func main() {
	s := `password123`
	bs, err := bcrypt.GenerateFromPassword([]byte(s), bcrypt.MaxCost)
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

//بخاطر تولید تعداد بسیار زیادی پسورد در سایت پلی گروند تایم اوت داد و در گولند اجرا تمام نشد
