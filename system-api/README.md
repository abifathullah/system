# Base API

## About This

Base API code test which consists:

- Products
    - Product list
    - Product view
    - Add product
    - Delete product
- About
    - PHP Version
    - Laravel Version
    - Vue Version

## Steps to run

1. Create `.env` file

- Mac/Linux:
````
cp .env.example .env
````
- Windows:
````
copy .env.example .env
````

2. Compose Docker
````
docker compose up -d
````

3. Run bash on api
````
docker exec -ti [container id/name] bash
````

4. Run in bash

    1. Run composer install
    ````
    composer install
    ````

    2. Generate key

    ````
    php artisan key:generate
    ````

    2. Run command to migrate database.

    ````
    php artisan migrate
    ````

    3. Run seeder to seed initial base data.
    ````
    php artisan db:seed
    ````

## Additional
1. Run pint
````
php artisan pint
````
2. Run phpstan
````
php artisan phpstan
````
3. Run phpunit test
````
php artisan test
````
- Run `php artisan cache:clear` to clear test result.
