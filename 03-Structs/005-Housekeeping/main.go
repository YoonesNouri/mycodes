package _05_Housekeeping

//ویدیو 104
//Housekeeping: (ص47 جزوه1)
//توضیح کلی در مورد شیءگرا بودن Go و چیزهای دیگه بود که Go هم شیء گرا هست هم نیست.
//در Go از class استفاده نمیشه بلکه از TYPE استفاده میشه و کپسول سازی و ... هم داره . یک ویوی کلی از Go.
//It’s all about type
//○ Is go an object oriented language? Go has OOP aspects. But it’s all about TYPE.
//We create TYPES in Go; user-defined TYPES. We can then have VALUES of
//that type. We can assign VALUES of a user-defined TYPE to VARIABLES.
//Anecdote: makes me think of that song, “It’s all about the bass, all about the
//bass” except “it’s all about the TYPE, all about the TYPE”
//● Go is Object Oriented
//1. Encapsulation
//a. state ("fields")
//b. behavior ("methods")
//c. exported & unexported; viewable & not viewable
//2. Reusability
//a. inheritance ("embedded types")
//3. Polymorphism
//a. interfaces
//4. Overriding
//a. "promotion"
//● Traditional OOP
//1. Classes
//a. data structure describing a type of object
//b. you can then create "instances"/"objects" from the class / blueprint
//c. classes hold both:
//i. state / data / fields
//ii. behavior / methods
//d. public / private
//2. Inheritance
//● In Go:
//1. you don't create classes, you create a TYPE
//2. you don't instantiate, you create a VALUE of a TYPE
//● User defined types
//● We can declare a new type
//● foo
//○ the underlying type of foo is int
//○ int conversion
//■ int(myAge)
//■ converting type foo to type int
//● IT IS A BAD PRACTICE TO ALIAS TYPES
//○ one exception:
//■ if you need to attach methods to a type
//■ see the time package for an example of this godoc.org/time
//Todd McLeod - Learn To Code Go on Udemy - Part 1 - Page 47
//● type Duration int64
//● Duration has methods attached to it
//● Named types vs anonymous types
//○ Anonymous types are indeterminate. They have not been declared as a
//type yet. The compiler has flexibility with anonymous types. You can
//assign an anonymous type to a variable declared as a certain type. If the
//assignment can occur, the compiler will figure it out; the compiler will do
//an implicit conversion. You cannot assign a named type to a different
//named type.
//● Padding & architectural alignment
//○ Convention: logically organize your fields together. Readability & clarity
//trump performance as a design concern. Go will be performant. Go for
//readability first. However, if you are in a situation where you need to
//prioritize performance: lay the fields out from largest to smallest, eg, int
//64, int64, float32, bool
//code:
//● https://play.golang.org/p/eQP7I3dW_r
