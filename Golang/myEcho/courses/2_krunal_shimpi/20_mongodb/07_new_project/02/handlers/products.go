package handlers

import (
	"context"
	"log"
	"net/http"

	"mymodule/2_krunal_shimpi/20_mongodb/07_new_project/02/dbiface"

	"github.com/go-playground/validator/v10"
	"github.com/labstack/echo/v4"
	"go.mongodb.org/mongo-driver/bson/primitive"
	// "go.mongodb.org/mongo-driver/mongo"
)

var (
	v = validator.New()
)

// * Product discribes an elecrtonic product e.g. phone
type Product struct {
	ID          primitive.ObjectID `json:"_id,omitempty" bson:"_id,omitempty"`
	Name        string             `json:"product_name" bson:"product_name" validate:"required, max=10"`
	Price       int                `json:"price" bson:"price" validate:"required, max=2000"`
	Currency    string             `json:"currency" bson:"currency" validate:"required, len=3"`
	Discount    int                `json:"discount" bson:"discount"`
	Vendor      string             `json:"vendor" bson:"vendor" validate:"required"`
	Accesories  []string           `json:"accesories" bson:"accesories"`
	IsEssential bool               `json:"is_essential" bson:"is_essential"`
}

// * ProductHandler a product handler
type ProductHandler struct {
	Col dbiface.CollectionAPI
}

// * ProductValidator a product validator
type ProductValidator struct {
	validator *validator.Validate
}

// * Validate validates a product
func (p *ProductValidator) Validate(i interface{}) error {
	return p.validator.Struct(i)
}

func insertProducts(ctx context.Context, products []Product, collection dbiface.CollectionAPI) ([]interface{}, error) {
	var insertedIds []interface{}
	for _, product := range products {
		product.ID = primitive.NewObjectID()
		insertID, err := collection.InsertOne(ctx, product)
		if err != nil {
			log.Printf("Unable to insert : %v", err)
			return nil, err
		}
		insertedIds = append(insertedIds, insertID.InsertedID)
	}
	return insertedIds, nil
}

// * CreateProducts creates products on mongodb database
func (h *ProductHandler) CreateProducts(c echo.Context) error {
    var products []Product
    c.Echo().Validator = &ProductValidator{validator: v}
    if err := c.Bind(&products); err != nil {
        log.Printf("Unable to bind: %v", err)
        return err
    }
    for _, product := range products {
        if err := c.Validate(product); err != nil {
            log.Printf("Unable to validate product: %+v %v", product, err)
            return err
        }
    }

    IDs, err := insertProducts(context.Background(), products, h.Col)
    if err != nil {
        return err
    }
    return c.JSON(http.StatusCreated, IDs)
}

