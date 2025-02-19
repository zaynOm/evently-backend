# Evently - Event Management Platform

Evently is a simple event management platform.

## Getting Started

1. Clone the repo:

   ```bash
   git clone https://github.com/zaynOm/evently-backend.git
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Environment variables:

   Copy the `.env.example` into a new file named `.env` manually.

   Or you can use this command.

   ```bash
   cp .env.example .env
   ```

4. Database setup:
   To run the project locally you need eather `mysql` or `mariadb`.

- If you don't have it already installed on you system you can follow the official installation guide [here](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/).
- Or use `docker`.

  ```docker
  docker run --name <your-database-name> -e MYSQL_ROOT_PASSWORD=<your-database-password> -p 3306:3306 -d mysql:latest
  ```

- Change the environment variables inside `.env`.

  ```dockerfile
  # Database settings

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your-database-name
  DB_USERNAME=root
  DB_PASSWORD=your-database-password
  ```

- Run database migrations & seeds:

  ```bash
  php artisan migrate --seed
  ```

5. Mailing:

   - To enable local mailing you need to get mailpit [here](https://github.com/axllent/mailpit/releases).
   - Or if you prefer using `docker`

   ```docker
   docker pull axllent/mailpit

   docker run -d --name=mailpit --restart unless-stopped -p 8025:8025 -p 1025:1025 axllent/mailpit
   ```

   - Run the queue worker:

   ```bash
    php artisan queue:work
   ```

6. Start the server:

```bash
php artidan serve
```
