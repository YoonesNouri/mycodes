package config

//* Properties Configuration properties based on env variables
type Properties struct {
	Port              string `env:"MY_APP_PORT" env_default:"8080"`
	Host              string `env:"HOST" env_default:"localhost"`
	DBHost            string `env:"DB_HOST" env_default:"localhost"`
	DBPort            string `env:"DB_PORT" env_default:"27017"`
	DBName            string `env:"DB_NAME" env_default:"tronics"`
	ProductCollection string `env:"PRODUCTS_COL_NAME" env_default:"products"`
	UsersCollection   string `env:"USERS_COL_NAME" env_default:"users"`
	JwtTokenSecret string `env:"JWT_TOKEN_SECRET" env_default:"abrakadabra"`
}
