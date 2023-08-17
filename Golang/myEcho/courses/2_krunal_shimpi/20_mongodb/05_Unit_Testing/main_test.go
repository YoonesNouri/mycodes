package main

import (
	"context"
	"testing"

	"github.com/tj/assert"

	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
)

type mockCollection struct {
}

// InsertOne is a mock implementation of Insert
func (m *mockCollection) InsertOne(ctx context.Context, document interface{}, opts ...*options.InsertOneOptions) (*mongo.InsertOneResult, error) {
	c := &mongo.InsertOneResult{}
	return c, nil
}

func TestInsertData(t *testing.T) {
	mockCol := &mockCollection{}
	res, err := insertData(mockCol, User{"yoones", "nouri"})
	assert.Nil(t, err)
	assert.IsType(t, &mongo.InsertOneResult{}, res)
	res, err = insertData(mockCol, User{"ali", "shekari"})
	assert.NotNil(t, err)
	assert.IsType(t, &mongo.InsertOneResult{}, res)
}
