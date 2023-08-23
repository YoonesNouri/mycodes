package handlers

import (
	"context"
	"encoding/json"
	"io"
	"net/http"
	"net/url"

	"mymodule/2_krunal_shimpi/24_PUT_PATCH_method/dbiface"

	"github.com/go-playground/validator/v10"
	"github.com/labstack/echo/v4"
	"github.com/labstack/gommon/log"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/bson/primitive"
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

func findProducts(ctx context.Context, q url.Values, collection dbiface.CollectionAPI) ([]Product, error) {
	var products []Product
	filter := make(map[string]interface{})
	for k, v := range q {
		filter[k] = v[0]
	}
	if filter["_id"] != nil {
		docID, err := primitive.ObjectIDFromHex(filter["_id"].(string))
		if err != nil {
			return products, err
		}
		filter["_id"] = docID
	}
	cursor, err := collection.Find(ctx, bson.M(filter))
	if err != nil {
		log.Errorf("Unable to find products : %v", err)
		return products, err
	}
	err = cursor.All(ctx, &products)
	if err != nil {
		log.Errorf("Unable to read cursor : %v", err)
		return products, err
	}
	return products, nil
}

func modifyProduct(ctx context.Context, id string, reqBody io.ReadCloser, collection dbiface.CollectionAPI) (Product, error) {
	var product Product
	//* find if the product exists, if err return 404
	docID, err := primitive.ObjectIDFromHex(id)
	if err != nil {
		log.Errorf("can not convert to objectid : %v", err)
		return product, err // 500 error code
	}
	filter := bson.M{"_id": docID}
	res := collection.FindOne(ctx, filter)
	if err := res.Decode(&product); err != nil {
		log.Errorf("Unable to decode to product : %v", err)
		return product, err //echo.NewHTTPError(500, "some message")
	}

	//* decode the req payload, if err return 500
	if err := json.NewDecoder(reqBody).Decode(&product); err != nil {
		log.Errorf("Unable to decode using reqbody : %v", err)
		return product, err
	}

	//* validate the req, if err return 400
	if err := v.Struct(product); err != nil {
		log.Errorf("Unable to validate the struct : %v", err)
		return product, err
	}

	//* update the product, if err return 500
	_, err = collection.UpdateOne(ctx, filter, bson.M{"$set": product})
	if err != nil {
		log.Errorf("Unable to update the Product : %v", err)
		return product, err
	}
	return product, nil
}

// * UpadateProduct updates a product
func (h *ProductHandler) UpdateProduct(c echo.Context) error {
	product, err := modifyProduct(context.Background(), c.Param("id"), c.Request().Body, h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusOK, product)
}

// * GetProduct gets a list of products
func (h *ProductHandler) GetProducts(c echo.Context) error {
	products, err := findProducts(context.Background(), c.QueryParams(), h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusOK, products)
}

func insertProducts(ctx context.Context, products []Product, collection dbiface.CollectionAPI) ([]interface{}, error) {
	var insertedIds []interface{}
	for _, product := range products {
		product.ID = primitive.NewObjectID()
		insertID, err := collection.InsertOne(ctx, product)
		if err != nil {
			log.Errorf("Unable to insert : %v", err)
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
		log.Errorf("Unable to bind: %v", err)
		return err
	}
	for _, product := range products {
		if err := c.Validate(product); err != nil {
			log.Errorf("Unable to validate product: %+v %v", product, err)
			return err
		}
	}

	IDs, err := insertProducts(context.Background(), products, h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusCreated, IDs)
}
