package refactor

import (
	"github.com/labstack/echo/v4"
)

var e *echo.Echo

func NewEcho() *echo.Echo {
	e = echo.New()
	return e
}

func Initialize() {
	Init(e)
}

func ConfigureRoutes() {
	// Define your route handlers here
	e.GET("/products", getProducts)
	// ... Define other routes similarly
}

func Start() {
	e.Logger.Fatal(e.Start("localhost:8080"))
}
