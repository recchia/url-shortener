# URL Shortener
Application for generate short url version from long urls, developed with Symfony 6.0, Api Platform and queues. 
PostgreSQL as Database and Queue service. You can use Postman or develop a js client to try the app following the api docs.

### How to
* To list more frequently accessed urls use: [http(s)://{domain}/api/short_urls?page=1&itemsPerPage=100&order%5Bhits%5D=desc](http(s)://{domain}/api/short_urls?page=1&itemsPerPage=100&order%5Bhits%5D=desc)
* To create a new one make a post request to: [http(s)://{domain}:8000/api/short_urls](http(s)://{domain}:8000/api/short_urls) with the following payload:
```{json}
{
  "longUrl": "valid_url"
}
```
###Requirements:

* Docker
* Docker Compose

###Steps for setup

***1 - Get the project:***
```{bash}
git clone git@github.com:recchia/url-shortener.git
cd shortener
```

***2 - create the following environment variables or left default in docker-compose:***

```{bash}
POSTGRES_VERSION=13
POSTGRES_DB=database_name
POSTGRES_PASSWORD=database_password
POSTGRES_USER=database_user
```

and copy .env file as .env.local, make sure the DATABASE_URL has the same values as docker-compose database.

***3 - Start containers:***
```{bash}
docker-compose up -d
```

***4 - Install vendors:***
```{bash}
docker-compose exec php composer install
```

***5 - Install and compile assets:***
```{bash}
docker-compose exec php yarn install
docker-compose exec php yarn dev
```

***6 - Execute Migrations:***
```{bash}
 docker-compose exec php bin/console d:m:m
```

***7 - Execute worker:***
```{bash}
docker-compose exec php bin/console messenger:consume async
```

***8 - Load in browser:***

* http://{domain}/api for Api docs (swagger UI)
* http://{domain}/{shortUrlId} to redirect

###Run Test Suite
#### Create test database
```{bash}
docker-compose exec php bin/console d:d:c --env=test
```
#### Execute Migrations
```{bash}
 docker-compose exec php bin/console d:m:m --env=test
```
#### Load Fixtures
```{bash}
docker-compose exec php bin/console d:f:load --env=test
```
#### Run tests
```{bash}
docker-compose exec php php bin/phpunit --testdox
```
