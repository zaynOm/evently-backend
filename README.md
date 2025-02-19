# Evently - Event Management Platform

Evently is a simple event management platform.

## Getting Started

1. Clone the repo:

   ```sh
   git clone https://github.com/zaynOm/evently-backend.git
   ```

2. Install dependencies:

   ```sh
   composer install
   ```

3. Environment variables:

   Copy the `.env.example` into a new file named `.env` manually.

   Or you can use this command.

   ```sh
   cp .env.example .env
   ```

4. Database setup:
   To run the project locally you need `mysql`.

- If you don't have it already installed on you system you can follow the official installation guide [here](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/).
- Or use `docker`.

  ```sh
  docker run --name <your-database-name> -e MYSQL_ROOT_PASSWORD=<your-database-password> -p 3306:3306 -d mysql:latest
  ```

- Change the environment variables inside `.env`.

  ```sh
  # Database settings

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your-database-name
  DB_USERNAME=root
  DB_PASSWORD=your-database-password
  ```

- Run database migrations & seeds:

  ```sh
  php artisan migrate --seed
  ```

5. Start the server:

```sh
php artidan serve
```
