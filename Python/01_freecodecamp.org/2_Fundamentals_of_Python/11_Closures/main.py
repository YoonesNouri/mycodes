def count():
    count = 0
    
    def increment():
        nonlocal count
        count += 1
        print(count)
    
    return increment

increment = count()  # دریافت تابع increment از تابع count

increment()  # چاپ 1
increment()  # چاپ 2
increment()  # چاپ 3
