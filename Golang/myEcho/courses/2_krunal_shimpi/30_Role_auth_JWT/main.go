package main

import (
	"context"
	"fmt"

	"mymodule/2_krunal_shimpi/29_JWT_middleware_auth/config"
	"mymodule/2_krunal_shimpi/29_JWT_middleware_auth/handlers"

	"github.com/ilyakaznacheev/cleanenv"
	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"
	"github.com/labstack/gommon/log"
	"github.com/labstack/gommon/random"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
)

const (
	//* CorrelationID is a request id unique to the request being made
	CorrelationID = "X-Correlation-ID"
)

var (
	c        *mongo.Client
	db       *mongo.Database
	prodCol  *mongo.Collection
	usersCol *mongo.Collection
	cfg      config.Properties
)

func init() {
	if err := cleanenv.ReadEnv(&cfg); err != nil {
		log.Fatalf("Configuration can not be read: %v", err)
	}

	connectURI := fmt.Sprintf("mongodb://%s:%s", cfg.DBHost, cfg.DBPort)
	c, err := mongo.Connect(context.Background(), options.Client().ApplyURI(connectURI))
	if err != nil {
		log.Fatalf("Unable to connect to database: %v", err)
	}
	db = c.Database(cfg.DBName)
	prodCol = db.Collection(cfg.ProductCollection)
	usersCol = db.Collection(cfg.UsersCollection)

	isUserIndexUnique := true
	indexModel := mongo.IndexModel{
		Keys: bson.D{{Key: "username", Value: 1}},
		Options: &options.IndexOptions{
			Unique: &isUserIndexUnique,
		},
	}
	_, err = usersCol.Indexes().CreateOne(context.Background(), indexModel)
	if err != nil {
		log.Fatalf("Unable to create an index : %+v", err)
	}
}

func addCorrelationID(next echo.HandlerFunc) echo.HandlerFunc {
	return func(c echo.Context) error {
		//* generate correlation ID
		id := c.Request().Header.Get("CorrelationID")
		var NewID string
		if id == "" {
			//* generate a random number
			NewID = random.String(12)

		} else {
			NewID = id
		}

		c.Request().Header.Set("CorrelationID", NewID)
		c.Response().Header().Set("CorrelationID", NewID)
		return next(c)
	}
}

func main() {
	e := echo.New()
	e.Logger.SetLevel(log.ERROR)
	e.Pre(middleware.RemoveTrailingSlash())
	e.Pre(addCorrelationID)
	jwtMiddleware := middleware.JWTWithConfig(middleware.JWTConfig{
		SigningKey:  []byte(cfg.JwtTokenSecret),
		TokenLookup: "header:x-auth-token",
	})
	e.Use(middleware.LoggerWithConfig(middleware.LoggerConfig{
		Format: `${time_rfc3339_nano} ${remote_ip} ${header:X-Correlation-ID} ${host} ${method} ${uri} ${user_agent}` +
			` ${status} ${error} ${latency_human}` + "\n",
	}))
	h := &handlers.ProductHandler{Col: prodCol}
	uh := &handlers.UsersHandler{Col: usersCol}
	e.GET("/products/:id", h.GetProduct)
	e.DELETE("/products/:id", h.DeleteProduct, jwtMiddleware)
	e.PUT("/products", h.UpdateProduct, middleware.BodyLimit("1M"), jwtMiddleware)
	e.POST("/products", h.CreateProducts, middleware.BodyLimit("1M"), jwtMiddleware)
	e.GET("/products", h.GetProducts)
	e.POST("/users", uh.CreateUser)
	e.POST("/auth", uh.AuthnUser)
	e.Logger.Infof("Listening on %s:%s", cfg.Host, cfg.Port)
	e.Logger.Fatal(e.Start(fmt.Sprintf("%s:%s", cfg.Host, cfg.Port)))
}
