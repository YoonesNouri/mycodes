//طبق قاعده ی control flow ابتدا فانکشن بیرونی ریترن/اجرا میشود
//سپس فانکشن درونی ریترن/اجرا میشود.
package main

func main() {
	c()
}

func c() (i int) {
	defer func() { i++ }()
	return 1
}
