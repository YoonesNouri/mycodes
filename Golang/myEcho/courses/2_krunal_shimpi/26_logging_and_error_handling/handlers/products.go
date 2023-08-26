package handlers

import (
	"context"
	"encoding/json"
	"io"
	"net/http"
	"net/url"

	"mymodule/2_krunal_shimpi/26_logging_and_error_handling/dbiface"

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

// * findProduct finds a single product
func findProduct(ctx context.Context, id string, collection dbiface.CollectionAPI) (Product, error) {
	var product Product
	docID, err := primitive.ObjectIDFromHex(id)
	if err != nil {
		return product, err
	}
	res := collection.FindOne(ctx, bson.M{"_id": docID})
	err = res.Decode(&product)
	if err != nil {
		return product, err
	}
	return product, nil
}

// * findProducts finds a list of products
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

func deleteProduct(ctx context.Context, id string, collection dbiface.CollectionAPI) (int64, *echo.HTTPError) {
	docID, err := primitive.ObjectIDFromHex(id)
	if err != nil {
		log.Errorf("Unable to convert to ObjectID : %v", err)
		return 0, echo.NewHTTPError(http.StatusInternalServerError, "Unable to convert to ObjectID")
	}
	res, err := collection.DeleteOne(ctx, bson.M{"id": docID})
	if err != nil {
		log.Errorf("Unable to delete the product : %v", err)
		return 0, echo.NewHTTPError(http.StatusInternalServerError, "Unable to delete the product")
	}
	return res.DeletedCount, nil
}

// * DeleteProduct deletes a single product
func (h *ProductHandler) DeleteProduct(c echo.Context) error {
	delCount, err := deleteProduct(context.Background(), c.Param("id"), h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusOK, delCount)
}

func modifyProduct(ctx context.Context, id string, reqBody io.ReadCloser, collection dbiface.CollectionAPI) (Product, *echo.HTTPError) {
	var product Product
	// find if the product exists, if err return 404
	docID, err := primitive.ObjectIDFromHex(id)
	if err != nil {
		log.Errorf("can not convert to ObjectID : %v", err)
		return product, echo.NewHTTPError(http.StatusInternalServerError, "Unable to convert to ObjectID")
	}
	filter := bson.M{"_id": docID}
	res := collection.FindOne(ctx, filter)
	if err := res.Decode(&product); err != nil {
		log.Errorf("Unable to decode to product : %v", err)
		return product, echo.NewHTTPError(http.StatusNotFound, "Unable to find to product")
	}

	// decode the req payload, if err return 500
	if err := json.NewDecoder(reqBody).Decode(&product); err != nil {
		log.Errorf("Unable to decode using reqbody : %v", err)
		return product, echo.NewHTTPError(http.StatusBadRequest, "Unable to parse the request payload")
	}

	// validate the req, if err return 400
	if err := v.Struct(product); err != nil {
		log.Errorf("Unable to validate the struct : %v", err)
		return product, echo.NewHTTPError(http.StatusBadRequest, "Unable to validate the request payload")
	}

	// update the product, if err return 500
	_, err = collection.UpdateOne(ctx, filter, bson.M{"$set": product})
	if err != nil {
		log.Errorf("Unable to update the Product : %v", err)
		return product, echo.NewHTTPError(http.StatusInternalServerError, "Unable to update the product")

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

// * GetProduct gets a single product
func (h *ProductHandler) GetProduct(c echo.Context) error {
	product, err := findProduct(context.Background(), c.Param("id"), h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusOK, product)
}

// * GetProducts gets a list of products
func (h *ProductHandler) GetProducts(c echo.Context) error {
	products, err := findProducts(context.Background(), c.QueryParams(), h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusOK, products)
}

func insertProducts(ctx context.Context, products []Product, collection dbiface.CollectionAPI) ([]interface{}, *echo.HTTPError) {
	var insertedIds []interface{}
	for _, product := range products {
		product.ID = primitive.NewObjectID()
		insertID, err := collection.InsertOne(ctx, product)
		if err != nil {
			log.Errorf("Unable to insert : %v", err)
			return nil, echo.NewHTTPError(http.StatusInternalServerError, "Unable to insert to database")
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
		return echo.NewHTTPError(http.StatusBadRequest, "Unable to parse request payload")
	}
	for _, product := range products {
		if err := c.Validate(product); err != nil {
			log.Errorf("Unable to validate product: %+v %v", product, err)
			return echo.NewHTTPError(http.StatusBadRequest, "Unable to validate request payload")
		}
	}
	IDs, err := insertProducts(context.Background(), products, h.Col)
	if err != nil {
		return err
	}
	return c.JSON(http.StatusCreated, IDs)
}
