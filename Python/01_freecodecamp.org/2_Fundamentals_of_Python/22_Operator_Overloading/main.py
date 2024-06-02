class Vector:
    def __init__(self, x, y):
        self.x = x
        self.y = y

    def __add__(self, other):
        return Vector(self.x + other.x, self.y + other.y)

    def __repr__(self):
        return f"Vector({self.x}, {self.y})"

# ایجاد دو شیء Vector
v1 = Vector(2, 3)
v2 = Vector(4, 5)

# استفاده از عملگر + برای جمع دو بردار
v3 = v1 + v2
print(v3)  # خروجی: Vector(6, 8)

#___________________________________________________________________

class Vector:
    def __init__(self, x, y):
        self.x = x
        self.y = y

    def __mul__(self, scalar):
        return Vector(self.x * scalar, self.y * scalar)

    def __repr__(self):
        return f"Vector({self.x}, {self.y})"

# ایجاد یک شیء Vector
v = Vector(2, 3)

# استفاده از عملگر * برای ضرب بردار با عدد صحیح
v2 = v * 3
print(v2)  # خروجی: Vector(6, 9)
