package main

import (
	"context"
	"errors"
	"fmt"
	"log"

	"mymodule/2_krunal_shimpi/20_mongodb/06_mock/01/dbiface"

	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
)

// * User a dummy user
type User struct {
	FirstName string `bson:"first_name"`
	LastName  string `bson:"last_name"`
}

//* actual collection *mongo.Collection
//* implements CollectionAPI
//* InsertOne will work on actual collection
//* fake collection mockCollection
//* implements CollectionAPI
//* InsertOne fake implementation, when invoked will work on fake collection

func insertData(collection dbiface.CollectionAPI, user User) (*mongo.InsertOneResult, error) {
	if user.FirstName != "yoones" {
		return nil, errors.New("invalid user")
	}
	res, err := collection.InsertOne(context.Background(), user)
	if err != nil {
		return res, err
	}
	return res, nil
}

func findData(collection dbiface.CollectionAPI) ([]User, error) {
	var users []User
	ctx := context.Background()
	cur, err := collection.Find(ctx, bson.M{})
	if err != nil {
		fmt.Printf("find error: %+v\n", err)
		return users, err
	}
	fmt.Printf("cursor : %+v\n", cur.Current)
	err = cur.All(ctx, &users)
	if err != nil {
		return users, err
	}
	return users, nil
}

func main() {
	c, err := mongo.Connect(context.Background(), options.Client().ApplyURI("mongodb://localhost:27017"))
	if err != nil {
		log.Fatalf("Error : %v", err)
	}
	db := c.Database("tronics")
	col := db.Collection("products")
	res, err := insertData(col, User{"yoones", "nouri"})
	if err != nil {
		fmt.Printf("insert failure : %+v\n", err)
	}
	fmt.Println(res)
	users, err := findData(col)
	if err != nil {
		fmt.Printf("find failure : %+v\n", err)
	}
	fmt.Println(users)
}
