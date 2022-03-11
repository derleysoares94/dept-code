# Laravel + Docker

## Usage

Access the project folder

```bash
cd dept-code
```

Create a .env file

```bash
cp .env.example .env
```

Change the .env inf

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dept_code
DB_USERNAME=root
DB_PASSWORD=root
```

Install the dependencies and start the containers

```bash
composer install
docker-compose up -d
```

Then migrate the database

```bash
php artisan migrate
```

Console command to fetch data from Harvard Library

```bash
php artisan harvard:fetch_books "author" "genre"

//return success when find data and save these data in the DB
//return Neither Author nor Genre was specified
// or We didn't find any book
```

API route to fetch all books from DB

```bash
http://localhost:8989/api/books
```

API route to fetch data from DB by ID

```bash
http://localhost:8989/api/book/2
```
