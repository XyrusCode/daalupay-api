# Daalu Pay API
Daalu Pay API is a Laravel-based backend solution for a currency conversion and payment application. The API supports manual transaction approvals, user role management, and secure authentication for both web and mobile clients (iOS and web).

---

## Features

- **Currency Conversion**: Handles currency conversions with accurate exchange rates.
- **Payment Processing**: Supports transactions with manual approval by admins.
- **User Roles**: 
  - **Super Admin**: Full control over the application.
  - **Admins**: Role-based permissions for managing transactions and other resources.
  - **Regular Users**: Access to app features like payments and currency conversion.
- **Authentication**: Configured with Laravel Sanctum for secure token-based authentication.
- **Multi-database Support**: Uses MySQL as the primary database and MongoDB for additional functionalities.

---

## Prerequisites

- **PHP**: ^8.2
- **Composer**: Latest version
- **Laravel**: ^11.0
- **Node.js**: (For frontend asset compilation, optional)
- **MySQL**: v8.0 or higher
- **MongoDB**: v4.4 or higher
- **Postman**: (Optional, for API testing)

---

## Installation

### Clone the Repository
```bash
git clone https://github.com/xyruscode/daalu-pay-api.git
cd daalu-pay-api
```

### Install Dependencies
```bash
composer install
```

### Environment Setup
1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
2. Update the following fields in `.env`:
   ```env
    APP_NAME=DaaluPay
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_TIMEZONE=UTC
    APP_URL=http://api.daalupay.internal

    FRONTEND_URL=http://daalupay.internal:3000

    APP_LOCALE=en
    APP_FALLBACK_LOCALE=en
    APP_FAKER_LOCALE=en_US

    APP_MAINTENANCE_DRIVER=file

    PHP_CLI_SERVER_WORKERS=4

    BCRYPT_ROUNDS=12

    LOG_CHANNEL=stack
    LOG_STACK=single
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    DB_CONNECTION=sqlite

    SANCTUM_STATEFUL_DOMAINS=daalupay.internal,api.daalupay.internal

    SESSION_DRIVER=database
    SESSION_LIFETIME=120
    SESSION_ENCRYPT=false
    SESSION_PATH=/
    SESSION_DOMAIN=null

    BROADCAST_CONNECTION=log
    FILESYSTEM_DISK=local
    QUEUE_CONNECTION=database

    CACHE_STORE=database
    CACHE_PREFIX=

    MEMCACHED_HOST=127.0.0.1

    REDIS_CLIENT=phpredis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    RESEND_KEY=
    MAIL_MAILER=log
    MAIL_HOST=127.0.0.1
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    AWS_USE_PATH_STYLE_ENDPOINT=false

    VITE_APP_NAME="${APP_NAME}"


    SENTRY_LARAVEL_DSN=

    SENTRY_TRACES_SAMPLE_RATE=1.0
    SENTRY_PROFILES_SAMPLE_RATE=1.0 
```

### Run Migrations
```bash
php artisan migrate
```

### Seed the Database
```bash
php artisan db:seed
```

---

## Usage

### Start the Development Server
```bash
php artisan serve
```

Visit `http://api.daalupay.internal` to access the API.


### Running Tests
```bash
php artisan test
```

---

## Key Components

### Authentication
- Laravel Sanctum is configured for token-based authentication. Tokens are generated for user sessions.

### Role-Based Access Control
- Admins and users have distinct permissions defined in the `permissions` table and enforced through policies and middleware.

### Multi-Database Configuration
- MySQL: Core app data (users, transactions, roles, etc.).
- MongoDB: Logs and analytics.

---

## Deployment

1. Set up the production server with PHP, MySQL, MongoDB, and a web server (Nginx or Apache).
2. Configure the `.env` file for production.
3. Run the following commands:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan migrate --force
   ```

---

## API Endpoints  

Below are the key endpoints provided by the Daalu Pay API, grouped by functionality:  

### App Info  
- **`GET /`**: Fetch application information.  
- **`GET /docs`**: Retrieve API documentation.  

### Database Management  
**Prefix**: `/db`  
- **`GET /db`**: List database migrations.  
- **`GET /db/status`**: Get the status of current migrations.  
- **`POST /db/migrate`**: Run pending migrations.  
- **`POST /db/rollback`**: Rollback the latest batch of migrations.  
- **`POST /db/seed`**: Seed the database with initial data.  

### Authentication  
- **`POST /token`**: Generate a token for mobile authentication.  
- **`POST /register`**: Register a new user (requires guest).  
- **`POST /login`**: Log in a user.  
- **`POST /forgot-password`**: Request a password reset link.  
- **`POST /reset-password`**: Reset the user's password.    
- **`POST /email/verification-notification`**: Resend email verification notification.  
- **`POST /logout`**: Log out the authenticated user.  

### User Management (Authenticated)  
**Prefix**: `/user`  
- **`GET /user`**: Get the authenticated user's profile.  
- **`GET /user/{id}`**: Fetch details of a specific user.  
- **`PUT /user/{id}`**: Update user details.  

### Transactions (Authenticated)  
**Prefix**: `/transactions`  
- **`GET /transactions`**: Fetch a list of all transactions.  
- **`GET /transactions/{id}`**: View details of a specific transaction.  
- **`POST /transactions`**: Create a new transaction.  
- **`DELETE /transactions/{id}`**: Delete a transaction.  

### Admin Routes  
**Middleware**: `auth:sanctum,admin`  
- **`GET /users`**: List all users.  
- **`POST /users`**: Create a new user.  
- **`POST /suspend-user`**: Suspend a user account.  
- **`POST /unsuspend-user`**: Unsuspend a user account.  

### Super Admin Routes  
**Middleware**: `auth:sanctum,super_admin,verify.browser`  
- **`GET /admins`**: List all admins.  
- **`GET /admins/{id}`**: Get details of a specific admin.  
- **`POST /disable-currency`**: Disable a currency from exchanges.  
- **`POST /enable-currency`**: Enable a previously disabled currency.  

---

## Notes for Developers  

### Frontend Developers  
1. Ensure token-based authentication headers are included for protected routes.  
2. Use the provided `/docs` endpoint to access detailed documentation of all API responses.  

### iOS Developers  
1. Include the `Authorization` header with the token obtained from `/token`.  
2. Use route prefixes (`/user`, `/transactions`, etc.) for proper organization in your networking layer.  

If you need further clarification, feel free to reach out!  

## Support

For questions, issues, or feature requests, contact **WalexBiz** at [walexbiz.com](mailto:walexbiz.com).

---

## License

This project is licensed under the [MIT License](./LICENSE).
```
