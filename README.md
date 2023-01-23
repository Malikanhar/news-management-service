# News Management Service
A backend service for News Management application

## Prerequisites
All prerequisites below are mandatory:
- Setup MySQL database server
- Setup Redis server for Job-Queue

## Installing News Mangement Service
1. Apply changes to `.env` file:
    - `APP_ENV`: Deployment environment, change it to `production` when working on production
    - `APP_DEBUG`: Always set it to `false` on the production
    - `DB_HOST`: Database hostname or IP address (e.g. `DB_HOST=127.0.0.1`)
    - `DB_PORT`: Database port (e.g. `DB_PORT=3306`)
    - `DB_DATABASE`: Database name (e.g. `DB_DATABASE=news_management_service`)
    - `DB_USERNAME`: Database username
    - `DB_PASSWORD`: Database password
    - `QUEUE_CONNECTION`: Job queue mechanism (e.g. `QUEUE_CONNECTION=redis`)
    - `REDIS_HOST`: Hostname or IP address for redis server (e.g. `REDIS_HOST=127.0.0.1`)
    - `REDIS_PASSWORD`: Redis server password
    - `REDIS_PORT`: Redis port (e.g. `REDIS_PORT=6379`)
    - `REDIS_CLIENT`: Redis client used by the laravel application (e.g. `REDIS_CLIENT=predis`)

2. Optimizing Composer's class autoloader: `composer install --optimize-autoloader --no-dev`
3. Generate Laravel Application key by running: `php artisan key:generate`
4. Optimizing Configuration: `php artisan config:cache`
5. Optimizing Route Loading: `php artisan route:cache`
6. Run the database migration and add some data to the user table: `php artisan migrate:fresh --seed`
7. Create a symbolic link from public/storage to storage/app/public: `php artisan storage:link`
8. Generate the Laravel Passport encryption and grant-access keys for the client: `php artisan passport:install`

## Running News Mangement Service

1. Run the laravel-worker using [Supervisor](https://laravel.com/docs/9.x/queues#supervisor-configuration), or else you can simply run: `php artisan queue:work`
2. Run the application: `php artisan serve`
