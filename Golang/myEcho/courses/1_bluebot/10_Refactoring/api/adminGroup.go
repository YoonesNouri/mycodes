package api

import (
    "github.com/labstack/echo/v4"
    "mymodule/1_bluebot/10_Refactoring/api/handlers"
)
func AdminGroup(g *echo.Group) {
    g.GET("/main", handlers.MainAdmin)
}