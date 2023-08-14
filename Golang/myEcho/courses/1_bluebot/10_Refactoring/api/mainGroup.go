package api

import (
	"mymodule/1_bluebot/10_Refactoring/api/handlers"

	"github.com/labstack/echo/v4"
)

func MainGroup(e *echo.Echo) {
	e.GET("/login", handlers.Login)
	e.GET("/yallo", handlers.Yallo)
	e.GET("/cats/:data", handlers.GetCats)

	e.POST("/cats", handlers.AddCat)
	e.POST("/dogs", handlers.AddDog)
	e.POST("/hamsters", handlers.AddHamster)
}
