package main

import (
	"fmt"
	"sync"
)

//yoones ویدیو 159 Ninja #9 exercise #4 (1ص66 جزوه)
//Fix the race condition you created in the previous exercise by using a 002-mutex
//it makes sense to remove runtime.Gosched()

var mu sync.Mutex

func main() {
	var wg sync.WaitGroup
	incrementer := 0
	gs := 100
	wg.Add(gs)
	for i := 0; i < gs; i++ {
		go func() {
			mu.Lock()
			v := incrementer
			v++
			incrementer = v
			fmt.Println(incrementer)
			mu.Unlock()
			wg.Done()
		}()
	}
	wg.Wait()
	fmt.Println("end value:", incrementer)
}
