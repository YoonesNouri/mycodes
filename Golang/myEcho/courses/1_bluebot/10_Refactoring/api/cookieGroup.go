package api

import (
    "mymodule/1_bluebot/10_Refactoring/api/handlers"

    "github.com/labstack/echo/v4"
)

func CookieGroup(g *echo.Group) {
    g.GET("/main", handlers.MainCookie)
}