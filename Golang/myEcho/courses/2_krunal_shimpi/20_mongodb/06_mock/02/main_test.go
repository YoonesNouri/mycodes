package main

import (
	"context"
	"testing"

	"github.com/tj/assert"

	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
)

type mockCollection struct {
	FirstName string
	LastName  string
}

// InsertOne is a mock implementation of Insert
func (m *mockCollection) InsertOne(ctx context.Context, document interface{}, opts ...*options.InsertOneOptions) (*mongo.InsertOneResult, error) {
	c := &mongo.InsertOneResult{}
	c.InsertedID = "abcd"
	return c, nil
}

func (m *mockCollection) Find(ctx context.Context, filter interface{}, opts ...*options.FindOptions) (*mongo.Cursor, error) {
	c := &mongo.Cursor{}
	c.Current = bson.Raw{[]byte(`
	[
		{
			"first_name": "yoones",
			"last_name": "nouri",
		}
	]
	`)}
	return c, nil
}

func TestInsertData(t *testing.T) {
	mockCol := &mockCollection{}
	res, err := insertData(mockCol, User{"yoones", "nouri"})
	assert.Nil(t, err)
	assert.IsType(t, &mongo.InsertOneResult{}, res)
	assert.Equal(t, "abcd", res.InsertedID)
	res, err = insertData(mockCol, User{"ali", "shekari"})
	assert.NotNil(t, err)
	assert.IsType(t, &mongo.InsertOneResult{}, res)
}

func TestFindData(t *testing.T) {
	mockCol := &mockCollection{}
	users, err := findData(mockCol)
	assert.Nil(t, err)
	for _, user := range users {
		assert.Equal(t, "yoones", user.FirstName)
	}
}
