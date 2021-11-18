## Install
- rename .env.example to .env and update connections to database.
- composer update.
- php artisan migrate:fresh.
- php artisan serve

After installation go to [Register page](http://127.0.0.1/api/register)

## Api urls
1. [POST] api/register with data: <code>{"name", "email", "password"}</code>
2. [POST] api/login with data: <code>{"email", "password"}</code>
3. [GET] api/products with header "Bearer: token that is obtained from the login response"
4. [POST] api/products with data: <code>{
   "product_name": "Test product2",
   "barcode": "012345678912",
   "sku": "UGG-BB-PUR-06",
   "ean13": "1234567890123",
   "asin": "B0006GQ8RW",
   "isbn": "978-3-16-148410-0",
   "price": "10.25",
   "stock": 100
   }</code>
5. [PUT] api/products with product object to update
6. [DELETE] api/products/id
7. [GET] api/products/id
8. [GET] api/documentation
