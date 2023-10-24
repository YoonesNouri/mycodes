// https://www.youtube.com/watch?v=VkGQFFl66X4

package main

import (
 "fmt"
// "net/http"
// "io/ioutil"
//  "context"
//  "time"

// "github.com/gin-gonic/gin"
)

type IBankAccount interface {
	GetBalance() int // 100 = 1 dollar
	Deposit(amount int)
	Withdraw(amount int) error
}

func main() {


	myAccounts := []IBankAccount{
		NewWellsFargo(),
		NewBitcoinAccount(),
	}

	for _,account := range myAccounts {
account.Deposit(500)
if err := account.Withdraw(70); err != nil {
	fmt.Printf("ERR %d\n", err)
}

		balance := account.GetBalance()
fmt.Printf("WF balance: %d\n", balance)
	}
}