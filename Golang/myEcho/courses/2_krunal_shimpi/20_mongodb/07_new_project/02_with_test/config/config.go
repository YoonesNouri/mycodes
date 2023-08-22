package config

//* Properties Configuration properties based on env variables
type Properties struct {
	Port           string `env:"MY_APP_PORT" env_default:"8080"`
	Host           string `env:"HOST" env_default:"localhost"`
	DBHost         string `env:"DB_HOST" env_default:"localhost"`
	DBPort         string `env:"DB_PORT" env_default:"27017"`
	DBName         string `env:"DB_NAME" env_default:"tronics"`
	CollectionName string `env:"COLLECTION_NAME" env_default:"products"`
}
