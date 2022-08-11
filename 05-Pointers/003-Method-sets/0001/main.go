//نکته: اگر تایپ ریسیور بدون پوینتر باشد هم میتواند پوینتر را ریترن کند هم بدون پوینتر را، اما اگر تایپ ریسیور پوینتر باشد فقط میتواند پوینتر را ریترن کند.
//(t T) T and *T
//(t *T) *T
//NON-POINTER RECEIVER & NON-POINTER VALUE
package main

import (
	"fmt"
	"math"
)

type circle struct {
	radius float64
}

type shape interface {
	area() float64
}

func (c circle) area() float64 {
	return math.Pi * c.radius * c.radius
}

func info(s shape) {
	fmt.Println("area", s.area())
}

func main() {
	c := circle{5}
	info(c)
}
