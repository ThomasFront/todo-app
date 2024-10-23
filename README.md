## Running the Application

You have two options for running the application: with Docker Compose or locally.

### Option 1: Running with Docker Compose (Optional)

If you prefer to run the application using Docker Compose, use the following command:

```bash
docker-compose up
```

This will start all services defined in the docker-compose.yml file, including any necessary databases or dependencies.

### Option 2: Running Locally

Alternatively, you can run the application locally without Docker Compose. In this case, ensure you have set up the required dependencies, including the database, and that the environment variables are properly configured.

### Environment Variables

Regardless of the method you choose, make sure all necessary environment variables are set. You can define these variables in a .env file or directly in the docker-compose.yml file if using Docker Compose.

## Install PHP dependencies using Composer and start application:

```bash
composer install
```

#### Run the database migrations:

```bash
php artisan migrate
```

#### Start application:

```bash
php artisan serve
```
