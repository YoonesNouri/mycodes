package handlers

import (
	"context"
	"net/http"
	"time"

	"mymodule/2_krunal_shimpi/28_JWT_auth/config"
	"mymodule/2_krunal_shimpi/28_JWT_auth/dbiface"

	"github.com/dgrijalva/jwt-go"
	"github.com/ilyakaznacheev/cleanenv"

	"github.com/go-playground/validator/v10"
	"github.com/labstack/echo/v4"
	"github.com/labstack/gommon/log"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo"
	"golang.org/x/crypto/bcrypt"
)

// * User represents a user
type User struct {
	Email    string `json:"username" bson:"username" validate:"required,email"`
	Password string `json:"password,omitempty" bson:"password" validate:"required,min=8,max=300"`
}

// * UsersHandler users handler
type UsersHandler struct {
	Col dbiface.CollectionAPI
}

var (
	cfg config.Properties
)

type userValidator struct {
	validator *validator.Validate
}

func (u *userValidator) Validate(i interface{}) error {
	return u.validator.Struct(i)
}

func isCredValid(givenPwd, storedPwd string) bool {
	if err := bcrypt.CompareHashAndPassword([]byte(givenPwd), []byte(storedPwd)); err != nil {
		return false
	}
	return true
}

func createToken(username string) (string, error) {
	if err := cleanenv.ReadEnv(&cfg); err != nil {
		log.Fatalf("Configuration cannot be read: %v", err)
	}
	claims := jwt.MapClaims{}
	claims["authorized"] = true
	claims["user_id"] = username
	claims["exp"] = time.Now().Add(time.Minute * 15).Unix()
	at := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
	token, err := at.SignedString([]byte(cfg.JwtTokenSecret))
	if err != nil {
		log.Errorf("Unable to generate the token: %v", err)
		return "", err
	}
	return token, nil
}

func insertUser(ctx context.Context, user User, collection dbiface.CollectionAPI) (interface{}, *echo.HTTPError) {
	var newUser User
	res := collection.FindOne(ctx, bson.M{"username": user.Email})
	err := res.Decode(&newUser)
	if err != nil && err != mongo.ErrNoDocuments {
		log.Errorf("Unable to decode retrieved user: %v", err)
		return nil, echo.NewHTTPError(http.StatusUnprocessableEntity, "Unable to decode retrieved user")
	}
	if newUser.Email != "" {
		log.Errorf("User by %s already exists", user.Email)
		return nil, echo.NewHTTPError(http.StatusBadRequest, "User already exists")
	}
	hashedPassword, err := bcrypt.GenerateFromPassword([]byte(user.Password), 8)
	if err != nil {
		log.Errorf("Unable to hash password: %v", err)
		return nil, echo.NewHTTPError(http.StatusInternalServerError, "Unable to process password")
	}
	user.Password = string(hashedPassword)
	_, err = collection.InsertOne(ctx, user)
	if err != nil {
		log.Errorf("Unable to insert the user: %+v", err)
		return nil, echo.NewHTTPError(http.StatusInternalServerError, "Unable to create the user")
	}
	return User{Email: user.Email}, nil
}

// * CreateUser creates a user
func (h *UsersHandler) CreateUser(c echo.Context) error {
	var user User
	v := validator.New()
	c.Echo().Validator = &userValidator{validator: v}
	if err := c.Bind(&user); err != nil {
		log.Errorf("Unable to bind the user struct.")
		return echo.NewHTTPError(http.StatusUnprocessableEntity, "Unable to parse the request payload.")
	}
	if err := c.Validate(user); err != nil {
		log.Errorf("Unable to validate the requested body.")
		return echo.NewHTTPError(http.StatusBadRequest, "Unable to validate request payload.")
	}
	insertedUserID, err := insertUser(context.Background(), user, h.Col)
	if err != nil {
		log.Errorf("Unable to insert to database")
		return err
	}
	//? when you sign up you are also signed in
	token, er := createToken(user.Email)
	if err != nil {
		log.Errorf("Unable to generate the token.")
		return echo.NewHTTPError(http.StatusInternalServerError, "Unable to generate the token")
	}
	c.Response().Header().Set("x-auth-token", token)
	return c.JSON(http.StatusCreated, insertedUserID)
}

func authentincateUser(ctx context.Context, reqUser User, collection dbiface.CollectionAPI) (User, *echo.HTTPError) {
	var storedUser User // user in db
	// check whether the user exists or not
	res := collection.FindOne(ctx, bson.M{"username": reqUser.Email})
	err := res.Decode(&storedUser)
	if err != nil && err != mongo.ErrNoDocuments {
		log.Errorf("Unable to decode retrieved user: %v", err)
		return storedUser, echo.NewHTTPError(http.StatusUnprocessableEntity, "Unable to decode retrieved user")
	}
	if err == mongo.ErrNoDocuments {
		log.Errorf("User %s does not exist", reqUser.Email)
		return storedUser, echo.NewHTTPError(http.StatusNotFound, "User does not exist")
	}
	//* validate the password
	if !isCredValid(reqUser.Password, storedUser.Password) {
		return storedUser, echo.NewHTTPError(http.StatusUnauthorized, "Credentials invalid")
	}
	return User{Email: storedUser.Email}, nil
}

// * AuthnUser authenticates a user
func (h *UsersHandler) AuthnUser(c echo.Context) error {
	var user User
	v := validator.New()
	c.Echo().Validator = &userValidator{validator: v}
	if err := c.Bind(&user); err != nil {
		log.Errorf("Unable to bind the user struct.")
		return echo.NewHTTPError(http.StatusUnprocessableEntity, "Unable to parse the request payload.")
	}
	if err := c.Validate(user); err != nil {
		log.Errorf("Unable to validate the requested body.")
		return echo.NewHTTPError(http.StatusBadRequest, "Unable to validate request payload.")
	}
	user, err := authentincateUser(context.Background(), user, h.Col)
	if err != nil {
		log.Errorf("Unable to authenticate to database.")
		return err
	}
	token, er := createToken(user.Email)
	if er != nil {
		log.Errorf("Unable to generate token.")
		return echo.NewHTTPError(http.StatusInternalServerError, "Unable to generate token")
	}
	c.Response().Header().Set("x-auth-token", "Bearer "+token) //space after Bearer is necessary here
	return c.JSON(http.StatusOK, User{Email: user.Email})
}
