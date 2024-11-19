package main

import (
	"bufio"
	"context"
	"encoding/json"
	"fmt"
	"log"
	"os"

	"github.com/segmentio/kafka-go"
)

type CoffeeOrder struct {
	CustomerName string `json:"customer_name"`
	CoffeeType   string `json:"coffee_type"`
}

func main() {
	// Set up Kafka writer to send messages
	writer := kafka.Writer{
		Addr:     kafka.TCP("localhost:9092"),
		Topic:    "coffee_orders",
		Balancer: &kafka.LeastBytes{},
	}

	scanner := bufio.NewScanner(os.Stdin)

	for {
		// Get customer name from input
		fmt.Print("Enter customer name (or type 'exit' to quit): ")
		scanner.Scan()
		customerName := scanner.Text()
		if customerName == "exit" {
			break
		}

		// Get coffee type from input
		fmt.Print("Enter coffee type: ")
		scanner.Scan()
		coffeeType := scanner.Text()

		// Create an order object
		order := CoffeeOrder{
			CustomerName: customerName,
			CoffeeType:   coffeeType,
		}

		// Marshal the order object to JSON
		orderJSON, err := json.Marshal(order)
		if err != nil {
			log.Println("Error marshalling order:", err)
			continue
		}

		// Send the order to Kafka
		err = writer.WriteMessages(context.Background(),
			kafka.Message{
				Key:   []byte(customerName),
				Value: orderJSON,
			},
		)
		if err != nil {
			log.Println("Failed to send order:", err)
			continue
		}

		log.Println("Order sent successfully:", string(orderJSON))
	}

	writer.Close()
	fmt.Println("Producer stopped.")
}
