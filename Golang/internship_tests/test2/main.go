package main

import (
    "fmt"
    "math/rand"
    "sync"
    "time"
)

func generateRandomNumbers(count int, resultChan chan int, wg *sync.WaitGroup) {
    defer wg.Done()
    rand.Seed(time.Now().UnixNano())
    for i := 0; i < count; i++ {
        resultChan <- rand.Intn(100) // Generate a random integer between 0 and 99
    }
}

func main() {
    numCount := 1000
    resultChan := make(chan int, numCount)
    var wg sync.WaitGroup

    for i := 0; i < 5; i++ { // Start 5 goroutines
        wg.Add(1)
        go generateRandomNumbers(numCount/5, resultChan, &wg)
    }

    go func() {
        wg.Wait()
        close(resultChan)
    }()

    // Collect and process the generated random numbers
    randomNumbers := make([]int, 0, numCount)
    for num := range resultChan {
        randomNumbers = append(randomNumbers, num)
    }

    // Print the first 10 random numbers as an example
    for i := 0; i < 10; i++ {
        fmt.Println(randomNumbers[i])
    }
}