def count():
    count = 0
    
    def increment():
        nonlocal count
        count = count + 1
        print(count)
    
    increment()

count()
