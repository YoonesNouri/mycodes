//مپ map یک لیست بدون ترتیب است که با map[T] T{x:y}  نوشته میشود. و بین دو تایپ مقایسه میکند،
//مثلا میتوان لیستی از اسامی مخاطبین (string) و شماره تلفن¬هاشون (int) درست کرد که mapش به این صورت میشود:
//map[string] int{Home: 9127,}
//و با نوشتن اسم مخاطب (اسم موقع سرچ هم باید بین "" باشد) ، شماره اش را پرینت کند.
//همانطور که دستور میدادیم value ی یک position در slice را پرینت کند. انتهای هر جفت باید کاما , باشد.
// Yoones
package main

import "fmt"

func main() {

	m := map[string]int{
		"mahdiyar": 0,
		"sn":       1,
	}
	fmt.Println(m)
	fmt.Println(m["sn"])
}
