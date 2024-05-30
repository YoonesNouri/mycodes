def count():
    count = 0
    
    def increment():
        nonlocal count
        count = count + 1
        print(count)
    
    increment()

count()
count()
count()

# output:
# 1  
# 1
# 1