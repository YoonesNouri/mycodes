from enum import Enum

# تعریف یک enum برای روزهای هفته
class Weekday(Enum):
    MONDAY = 1
    TUESDAY = 2
    WEDNESDAY = 3
    THURSDAY = 4
    FRIDAY = 5
    SATURDAY = 6
    SUNDAY = 7

# دسترسی به اعضای enum
print(Weekday.MONDAY)  # Weekday.MONDAY
print(Weekday.MONDAY.name)  # MONDAY
print(Weekday.MONDAY.value)  # 1

# تکرار بر روی اعضای enum
for day in Weekday:
    print(day)

# مقایسه اعضای enum
if Weekday.MONDAY == Weekday.TUESDAY:
    print("They are the same day")
else:
    print("They are different days")

# استفاده از enum در توابع
def is_weekend(day):
    return day in (Weekday.SATURDAY, Weekday.SUNDAY)

print(is_weekend(Weekday.SATURDAY))  # True
print(is_weekend(Weekday.MONDAY))    # False
