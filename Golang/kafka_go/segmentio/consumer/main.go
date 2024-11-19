package main

import (
	"context"
	"encoding/json"
	"log"

	"github.com/segmentio/kafka-go"
)

type CoffeeOrder struct {
	CustomerName string `json:"customer_name"`
	CoffeeType   string `json:"coffee_type"`
}

func main() {
	reader := kafka.NewReader(kafka.ReaderConfig{
		Brokers: []string{"localhost:9092"},
		Topic:   "coffee_orders",
		GroupID: "order_consumers",
	})

	for {
		msg, err := reader.ReadMessage(context.Background())
		if err != nil {
			log.Println("Error reading message:", err)
			continue
		}

		var order CoffeeOrder
		err = json.Unmarshal(msg.Value, &order)
		if err != nil {
			log.Println("Error unmarshalling order:", err)
			continue
		}

		log.Printf("New order received:\n- Customer: %s\n- Coffee Type: %s\n", order.CustomerName, order.CoffeeType)
	}
}
