### Build and boot infrastructure with docker-compose 
docker-compose up -d --build

###  SSH into the PHP container
docker-compose exec php /bin/bash

----
_**All commands bellow are assumed to be inside PHP container**_

### **Install vendor packages**

composer install


### **Prepare Database**

- ensure you have .env.local and .env.test
- set this value in both files DATABASE_URL="mysql://root:secret@database:3306/symfony_docker?serverVersion=8.0"


php bin/console --env=local doctrine:database:create


### **Run migrations Schema**

symfony console --env=local doctrine:migrations:migrate


### **Download Postcodes**

symfony console PostCodes:DownloadPostCodesData


### **Import Postcodes**

symfony console PostCodes:ImportPostCodesData

This can be a time-consuming task as it contains 120 pages with postcodes and more than 1.7 Million
entries to be added to database.
As an alternative you can pass and argument and choose a number between 1 and 120 and limit the number of post codes .
This is better for quick tests.

### **Run Unit test**
ensure that you have the postcodes starting with BH19 in the database in case you havent uploaded everything so that you can run the unit tests.

php ./vendor/bin/phpunit

---

### **Using the Application**

You can just use the browser or use something like postman to test the application.
open http://localhost:8080/locations

Instructions will be found in the response body.
