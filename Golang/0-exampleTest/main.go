package main

import (
	"fmt"
	"math/rand"
	"time"
)

// generateOTP generates a random OTP of the specified length
func generateOTP(length int) string {
	rand.Seed(time.Now().UnixNano())

	// Define the character set for the OTP
	charSet := "0123456789qwertyuioplkjhgfdsazxcvbnm" // You can extend this to include letters and symbols if needed

	otp := make([]byte, length)
	for i := range otp {
		otp[i] = charSet[rand.Intn(len(charSet))]
	}
	return string(otp)
}

func main() {
	// Generate a OTP
	otp := generateOTP(8)

	// Print the OTP
	fmt.Println("One-Time Password (OTP):", otp)
}
