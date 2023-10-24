// https://www.youtube.com/watch?v=VkGQFFl66X4

package main

import (
"fmt"
"net/http"
"io/ioutil"
"context"
"time"
)

func main() {

	timeoutContext, cancel := context.WithTimeout(context.Background(), time.Millisecond * 10000)
	defer cancel()

	// Create HTTP request
	req , err := http.NewRequestWithContext(timeoutContext ,http.MethodGet, "http://placehold.it/2000x2000", nil)
	if err != nil {
		panic(err)
	}

	// perform HTTP request
	res, err := http.DefaultClient.Do(req)
	if err != nil {
		panic(err)
	}
	defer res.Body.Close()

	// get data from HTTP response
	imageData, err := ioutil.ReadAll(res.Body)
	if err != nil {
		panic(err)
	}
	fmt.Printf("download image of size %d\n" ,len(imageData))

}