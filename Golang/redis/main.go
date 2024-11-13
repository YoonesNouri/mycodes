package main

import (
	"context"
	"encoding/json"
	"fmt"

	"github.com/go-redis/redis/v8"
	"github.com/google/uuid"
)

var ctx = context.Background()

func main() {
    fmt.Println("redis example")

    client := redis.NewClient(&redis.Options{
        Addr: "localhost:6379",
        Password: "",
        DB:       0,
    })

    ping, err := client.Ping(ctx).Result()
    if err != nil {
        fmt.Println(err.Error())
        return
    }
    fmt.Println(ping)

    type Person struct {
        ID          string
        Name        string  `json:"name"`
        Age         int     `json:"age"`
        Occupation  string  `json:"occupation"`
    }

    elliotID := uuid.NewString()
    jsonString, err := json.Marshal(Person{
        ID: elliotID,
        Name: "Elliot",
        Age: 30,
        Occupation: "Staff Software Engineer",
    })
    if err != nil {
        fmt.Printf("failed to marshal: %s", err.Error())
        return
    }

    elliotKey := fmt.Sprintf("person:%s", elliotID)
    err = client.Set(ctx, elliotKey, jsonString, 0).Err()
    if err != nil {
        fmt.Printf("failed to set value in redis instnace: %s", err.Error())
        return
    }

    val, err := client.Get(ctx, elliotKey).Result()
    if err != nil {
        fmt.Printf("failed to get value from redis: %s", err.Error())
        return
    }

    fmt.Printf("value retrieved from redis: %s\n", val)
    
}
