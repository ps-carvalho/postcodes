### **Build and boot infrastructure with docker-compose **
docker-compose up -d --build

### ** SSH into the PHP container**
docker-compose exec php /bin/bash

### **Install vendor packages**

composer install


### **Prepare Database**

- ensure you have .env.local and .env.test
- set this value in both files DATABASE_URL="mysql://root:secret@database:3306/symfony_docker?serverVersion=8.0"


php bin/console --env=local doctrine:database:create

php bin/console --env=test doctrine:database:create

### **Run migrations and create Test Schema**

symfony console --env=local doctrine:migrations:migrate

symfony console --env=test doctrine:migrations:migrate

### **Download Postcodes**

symfony console PostCodes:DownloadPostCodesData


### **Import Postcodes**

symfony console PostCodes:ImportPostCodesData

symfony console --env=test PostCodes:ImportPostCodesData

### **Run Unit test**

php ./vendor/bin/phpunit
