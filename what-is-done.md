# what is done;
1. Created the Api with Laravel 8, Mysql and Firebase;
2. The base controller has methods that handles the response type (error or success);
3. Created routes for :
   1. Products ( using Authorization header Bearer which is returned after user log in):
      1. Product creation;
         1. Create new product in mysql database and send push to firebase;
      2. Product update;
         1. Update the product in mysql database and send push to firebase;
      3. Product details;
         1. Get details from the database based on product id;
      4. Product list;
         1. Get the paginated list of products from the database;
      5. Product delete
         1. Delete the product by id from database and send notification to firebase;
      6. Product search:
         1. Get request to search a product by a part of title;
   2. Users:
      1. Registration:
         1. Post requests to create new account;
      2. Login: 
         1. Post requests to log in existing user and getting the token;
4. created new Service 'app/Services/Firebase.php' that contains methods used to send pushes to Firebase;
5. Created 'app/Observers/UserObserver.php' used to call Firebase methods when new user is created/updated/deleted;
6. Created 'app/Observers/ProductObserver.php' used to manage notifications to Firebase when new product is created/updated/deleted;
7. All observers are called from 'app/Providers/EventServiceProvider.php';
8. Created new migrations to add column 'firebase_key' to 'products' and 'users' table;
