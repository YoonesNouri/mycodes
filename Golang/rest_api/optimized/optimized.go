package main

import (
	"encoding/json"
	"log"
	"math/rand"
	"net/http"
	"strconv"

	"github.com/gorilla/mux"
)

// Book struct (Model)
type Book struct {
	ID     string  `json:"id"`
	Isbn   string  `json:"isbn"`
	Title  string  `json:"title"`
	Author *Author `json:"author"`
}

// Author struct
type Author struct {
	Firstname string `json:"firstname"`
	Lastname  string `json:"lastname"`
}

// Init books var as a slice Book struct
var books []Book

// JSONResponse sets the content type and sends a JSON response
func JSONResponse(w http.ResponseWriter, data interface{}) {
	w.Header().Set("Content-Type", "application/json")
	json.NewEncoder(w).Encode(data)
}

// Get all books
func getBooks(w http.ResponseWriter, r *http.Request) {
	JSONResponse(w, books)
}

// Get single book
func getBook(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	for _, item := range books {
		if item.ID == params["id"] {
			JSONResponse(w, item)
			return
		}
	}
	JSONResponse(w, &Book{})
}

// Add new book
func createBook(w http.ResponseWriter, r *http.Request) {
	var book Book
	_ = json.NewDecoder(r.Body).Decode(&book)
	book.ID = strconv.Itoa(rand.Intn(100000000))
	books = append(books, book)
	JSONResponse(w, book)
}

// Update book
func updateBook(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	var updatedBook Book
	_ = json.NewDecoder(r.Body).Decode(&updatedBook)
	updatedBook.ID = params["id"]

	for i, item := range books {
		if item.ID == params["id"] {
			books[i] = updatedBook
			JSONResponse(w, updatedBook)
			return
		}
	}
	JSONResponse(w, &Book{})
}

// Delete book
func deleteBook(w http.ResponseWriter, r *http.Request) {
	params := mux.Vars(r)
	for i, item := range books {
		if item.ID == params["id"] {
			books = append(books[:i], books[i+1:]...)
			break
		}
	}
	JSONResponse(w, books)
}

// Main function
func main() {
	// Init router
	r := mux.NewRouter()

	// Hardcoded data - @todo: add database
	books = append(books, Book{ID: "3", Isbn: "333333", Title: "Book 3", Author: &Author{Firstname: "Andrew", Lastname: "Tate"}})
	books = append(books, Book{ID: "4", Isbn: "444444", Title: "Book 4", Author: &Author{Firstname: "Tristan", Lastname: "Tate"}})

	// Route handles & endpoints
	r.HandleFunc("/books", getBooks).Methods("GET")
	r.HandleFunc("/books/{id}", getBook).Methods("GET")
	r.HandleFunc("/books", createBook).Methods("POST")
	r.HandleFunc("/books/{id}", updateBook).Methods("PUT")
	r.HandleFunc("/books/{id}", deleteBook).Methods("DELETE")

	// Start server
	log.Fatal(http.ListenAndServe(":9000", r))
}
