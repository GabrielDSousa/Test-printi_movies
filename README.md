## About Project

Very small api to handle a list of movies, is for a company test.

Made with Laravel, MySql, PHPUnit and Heroku.

## Heroku App
This application is hosted on Heroku, integrated with this repository, for rapid testing:

The routes are:
#### Index
```
https://printi-movies.herokuapp.com/api/movies
```

Could having some filters like:
```
https://printi-movies.herokuapp.com/api/movies?title=Spider
```

```
https://printi-movies.herokuapp.com/api/movies?category=Action
```

```
https://printi-movies.herokuapp.com/api/movies?category=Action&title=Spider
```

#### Store
```
https://printi-movies.herokuapp.com/api/add/movie
```

With body:
``` 
{
    "title":"Avengers",
    "category":"Action"
}
```

## Local install
Clone the application's repository to your local computer.

You may install the application's dependencies by navigating to the application's directory and executing the following command. This command uses a small Docker container containing PHP and Composer to install the application's dependencies:

```
sudo cp .env.example .env
sudo chown -R $USER . &&
sudo docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs &&
./vendor/bin/sail up
```

After you need to enter the app container, publish the encryption key, migrate the database and 
if you want some data to test you can seed:
```
sudo docker exec -ti printi-movies-laravel.test-1 /bin/bash
```
Inside the container.
```
php artisan key:generate
php artisan migrate
php artisan db:seed
```
