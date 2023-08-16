package main

import (
	"context"
	"fmt"
	"time"

	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/bson/primitive"
	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
)

// * Product describes an electronic product e.g. phone
type Product struct {
	ID          primitive.ObjectID `bson:"_id" bson:"_id"`
	Name        string             `json:"product_name" bson:"product_name"`
	Price       int                `json:"price" bson:"price"`
	Currency    string             `json:"currency" bson:"currency"`
	Quantity    int                `json:"quantity" bson:"quantity"`
	Discount    int                `json:"discount,omitempty" bson:"discount,omitempty"`
	Vendor      string             `json:"vendor" bson:"vendor"`
	Accessories []string           `json:"accessories,omitempty" bson:"accessories,omitempty"`
	SkuID       string             `json:"sku_id" bson:"sku_id"`
}

var iphone10 = Product{
	ID:          primitive.NewObjectID(),
	Name:        "iPhone 10",
	Price:       900,
	Currency:    "USD",
	Quantity:    40,
	Vendor:      "Apple",
	Accessories: []string{"charger", "headset", "slot opener"},
	SkuID:       "1234",
}

var trimmer = Product{
	ID:          primitive.NewObjectID(),
	Name:        "easy trimmer",
	Price:       120,
	Currency:    "USD",
	Quantity:    300,
	Vendor:      "Philips",
	Discount:    7,
	Accessories: []string{"charger", "comb", "blade set", "cleaning oil"},
	SkuID:       "2345",
}

var speaker = Product{
	ID:          primitive.NewObjectID(),
	Name:        "speaker",
	Price:       300,
	Currency:    "USD",
	Quantity:    25,
	Vendor:      "Bosch",
	Discount:    4,
	Accessories: []string{"cables", "remotes"},
	SkuID:       "4567",
}

func main() {
	client, err := mongo.NewClient(options.Client().ApplyURI("mongodb://localhost:27017"))
	if err != nil {
		fmt.Println(err)
	}
	ctx, cancel := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancel()
	err = client.Connect(ctx)
	if err != nil {
		fmt.Println(err)
	}

	//* primitive.ObjectID: primitive.NewObjectID() generates a unique MongoDB ObjectID.
	//* bson.A: bson.A{" value1", "value2" , …} creates an array with the provided values.
	//* bson.B: Not typically used directly; used internally for binary data.
	//* bson.D: bson.D{{"key", "value"}} creates an ordered document (slice of key-value pairs).
	//* bson.M: bson.M{"key": "value"} creates a map-style dynamic document.
	db := client.Database("tronics")
	collection := db.Collection("products")

	// * ******Create*******

	//? Using structs
	res, err := collection.InsertOne(context.Background(), trimmer)
	fmt.Println("----- Insert Many Using struct -----")
	fmt.Println(res.InsertedID)

	//? Using bson.D
	res, err = collection.InsertOne(context.Background(), bson.D{
		{"name", "eric"},
		{"surname", "cartman"},
		{"hobbies", bson.A{"videogame", "alexa", "kfc"}},
	})
	fmt.Println("----- Insert Many Using bson.D -----")
	fmt.Println(res.InsertedID)

	//? Using bson.M
	res, err = collection.InsertOne(context.Background(), bson.M{
		"name":    "eric",
		"surname": "cartman",
		"hobbies": bson.A{"videogame", "alexa", "kfc"},
	})
	fmt.Println("----- Insert Many Using bson.M -----")
	fmt.Println(res.InsertedID)

	//? Inserting Many documents
	resMany, err := collection.InsertMany(context.Background(), []interface{}{iphone10, speaker})
	fmt.Println("----- Insert Many -----")
	fmt.Println(resMany.InsertedIDs)

	// * ******Read*******

	//! Equality operator using FindOne
	var findOne Product
	err = collection.FindOne(context.Background(), bson.M{"price": 900}).Decode(&findOne)
	fmt.Println("----- Equality operator using FindOne -----")
	fmt.Println(findOne)

	//! Comparison operators using Find
	var find Product
	fmt.Println("----- Comparison operators using Find -----")
	findCursor, err := collection.Find(context.Background(), bson.M{"price": bson.M{"$gt": 100}})
	for findCursor.Next(context.Background()) {
		err := findCursor.Decode(&find)
		if err != nil {
			fmt.Println(err)
		}
		fmt.Println(find.Name)
	}

	//! Logical operator using Find
	var findLogic Product
	logicFilter := bson.M{
		"$and": bson.A{
			bson.M{"price": bson.M{"$gt": 100}},
			bson.M{"quantity": bson.M{"$gt": 30}},
		},
	}
	fmt.Println("----- Logical operator using Find -----")
	findLogicRes, err := collection.Find(context.Background(), logicFilter)
	for findLogicRes.Next(context.Background()) {
		err := findLogicRes.Decode(&findLogic)
		if err != nil {
			fmt.Println(err)
		}
		fmt.Println(findLogic.Name)
	}

	//! Element operator using Find
	var findElement Product
	elementFilter := bson.M{
		"accessories": bson.M{"$exists": true},
	}
	fmt.Println("----- Element operator using Find -----")
	findElementRes, err := collection.Find(context.Background(), elementFilter)
	for findElementRes.Next(context.Background()) {
		err := findElementRes.Decode(&findElement)
		if err != nil {
			fmt.Println(err)
		}
		fmt.Println(findElement.Name)
	}

	//! Array operator using Find
	var findArray Product
	arrayFilter := bson.M{
		"accessories": bson.M{"$in": bson.A{"charger", "headset", "slot opener"}},
	}
	fmt.Println("----- Array operator using Find -----")
	findArrayRes, err := collection.Find(context.Background(), arrayFilter)
	for findArrayRes.Next(context.Background()) {
		err := findArrayRes.Decode(&findArray)
		if err != nil {
			fmt.Println(err)
		}
		fmt.Println(findArray.Name)
	}

	//! Update operator for field
	fmt.Println("----- Update operator for field -----")
	updateFieldCon := bson.M{"$Set": bson.M{"isEssential": "false"}}
	updateFieldRes, err := collection.UpdateMany(context.Background(), bson.M{}, updateFieldCon)
	fmt.Println(updateFieldRes.ModifiedCount)

	//! Update operator for Array
	fmt.Println("----- Update operator for Array -----")
	updateArrayCon := bson.M{"$addToSet": bson.M{"accessories": "manual"}}
	updateArrayRes, err := collection.UpdateMany(context.Background(), arrayFilter, updateArrayCon)
	fmt.Println(updateArrayRes.ModifiedCount)

	//! Update operator fot field - multiple operators
	fmt.Println("----- Update operator fot field - multiple operators -----")
	incCon := bson.M{
		"$mul": bson.M{
			"price": 1.20},
	}
	incRes, err := collection.UpdateMany(context.Background(), bson.M{}, incCon)
	fmt.Println(incRes.MatchedCount)

	//! Delete operator using DeleteMany
	// fmt.Println("----- Delete operator ------")
	// delRes, err := collection.DeleteMany(context.Background(), arrayFilter)
	// fmt.Println(delRes.DeletedCount)

	if err != nil {
		fmt.Println(err)
	}
}
