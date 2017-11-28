# Install
Download and copy the `Magmalabs` directory into `app/code/`

Then call:
```sh
php bin/magento setup:upgrade
```

# How to use
```
Usage:
  magmalabs:delete [options] [--] [<id>]

Arguments:
  id                    Id

Options:
  -o, --order             Work with Orders
  -p, --product         Work with Products
  -c, --category        Work with Categories
  -a, --delete-all        Delete all
  -h, --help               Display this help message
```

Eg:
```sh
# Delete order with id = 50
php bin/magento magmalabs:delete -o 50

# Delete all orders
php bin/magento magmalabs:delete -oa
--------------------------------------------------------------------------
# Delete product with id = 25
php bin/magento magmalabs:delete -p 25

# Delete all products
php bin/magento magmalabs:delete -pa
--------------------------------------------------------------------------
# Delete category with id = 15
php bin/magento magmalabs:delete -c 25

# Delete all categories
php bin/magento magmalabs:delete -ca
```