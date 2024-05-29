set1 = {1, 2, 3, 4}
set2 = {3, 4, 5, 6}

# عملگر اتحاد
union_set = set1 | set2
print(union_set)  # Output: {1, 2, 3, 4, 5, 6}

# عملگر تقاطع
intersection_set = set1 & set2
print(intersection_set)  # Output: {3, 4}

# عملگر تفاوت مجموعه‌ای
difference_set = set1 - set2
print(difference_set)  # Output: {1, 2}
