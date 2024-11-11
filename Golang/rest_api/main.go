package main

import (
	"encoding/json"
	"log"
	"net/http"
	"github.com/gorilla/mux"
)

type Message struct {
	Message string `json:"message"`
}

func GetMessage(w http.ResponseWriter, r *http.Request) {
	msg := Message{Message: "Hello, World!"}
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(msg)
}

func main() {
	r := mux.NewRouter()
	r.HandleFunc("/message", GetMessage).Methods("GET")

	log.Fatal(http.ListenAndServe(":8080", r))
}
