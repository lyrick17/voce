dict = {
     5 : "Integer", 
    True : "Boolean",
    "str" : "String",
}

list = ["item1", "item2", "item3"]

print("DICTIONARY: ")
print(dict[5])
del dict["str"]
print(dict[5])

print("LIST: ")
print(list[1])
list.remove("item1")
print(list[0])