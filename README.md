

## ATM Simulation - Laravel Backend For Basic ATM Operations

This project is an ATM simulation backend developed using Laravel. It provides API endpoints for basic banking operations such as withdrawing and depositing money, checking balance, and retrieving transaction history with unit test and feature test coverage. Additionally, it includes an admin interface for managing user accounts and tracking transactions.
## Table of Contents

1. [Requirements](#requirements)
2. [Project Setup](#project-setup)
3. [Running the Project](#running-the-project)
4. [API Documentation](#api-documentation)
5. [Database Design](#database-design)
6. [Testing](#testing)
7. [Project Structure](#project-structure)
8. [Important Notes](#important-notes)


## Requirements

Before you begin, ensure you have the following installed:

- [Docker](https://www.docker.com/), [Docker Compose](https://docs.docker.com/compose/), [Composer](https://getcomposer.org/), [Git](https://git-scm.com/)


## Project Setup
#### 1. Clone the Repository
```bash
git clone https://github.com/abdallahnasar/atm-simulation.git
cd atm-simulation
```

#### 2. Install Dependencies
```bash
composer install
```
#### 3. Copy Environment File
```bash
cp .env.example .env
```
#### 4. Generate Application Key
```bash
php artisan key:generate
```
#### 5. Start Docker Containers
```bash
./vendor/bin/sail up -d
```
#### 6. Run Database Migrations and Seeders
```bash
./vendor/bin/sail artisan migrate --seed
```
#### 7. Install Passport
```bash
./vendor/bin/sail artisan passport:install
```
#### 8. Run Tests
```bash
cp .env.testing.example .env.testing
./vendor/bin/sail artisan migrate --env=testing
./vendor/bin/sail artisan test
```

## Running the Project
This project uses Laravel Sail to dockerize isolated and consistent development environment, running the project is straightforward:

#### Start the development server:
```bash
./vendor/bin/sail up
```

this will start the application at http://localhost

#### Access the admin interface:
Visit http://localhost/admin and login with the following credentials:
- Email: admin@admin.com
- Password: Te$t1234


#### Access the API Endpoints:
The API endpoints are available under the `/api/v1` prefix. For example, to access the `balance` endpoint, visit http://localhost/api/v1/balance.

#### API Endpoints:
The following API endpoints are available:
- `Login`: POST /api/v1/login
- `Deposit`: POST /api/v1/deposit
- `Withdraw`: POST /api/v1/withdraw
- `Balance`: GET /api/v1/balance
- `Transactions`: GET /api/v1/transactions




## API Documentation

The API documentation is generated using Scribe, To generate the documentation, run:
```bash
./vendor/bin/sail artisan scribe:generate
```
Then You can view the documentation by navigating to
http://localhost/docs.
This includes detailed information on all available API endpoints, request parameters, and responses, 
If you prefer postman click the "View Postman Collection" link on left menu and import the result text collection on postman.

## Database Design
The database design consists of three main tables:
- `users`: Stores user information including the name, debit card number, PIN, and balance.
- `transactions`: Stores transaction records including the type (deposit/withdrawal), amount, and related user.
- `admins`: Stores admin information including the name, email, and password.
- `migrations`: Stores migration information.
-  passport tables for jwt authentication.


## Testing

Unit and feature tests are included to ensure the integrity of core functionalities. Run the tests using the following command:
```bash
cp .env.testing.example .env.testing
./vendor/bin/sail artisan migrate --env=testing
./vendor/bin/sail artisan test
```
note that a separate database is used for running automated tests to ensure data integrity and consistency.



## Project Structure
- `app/Services`: Contains service classes for business logic.
- `app/Repositories`: Contains repository classes for database interactions.
- `app/Http/Requests`: Contains request validation classes.
- `app/Http/Resources`: Contains resource classes for API response formatting.
- `routes/web.php`: Defines routes for the admin interface.
- `routes/api.php`: Defines API routes for the ATM simulation.
- `database/factories`: Contains model factories for generating fake data.
- `database/seeders`: Contains seeders for populating the database with initial data.
- `tests/Unit`: Contains unit tests of services and repositories.
- `tests/Feature`: Contains feature tests of api controllers.


## Important Notes

#### Token Expiration:
JWT tokens are configured to expire after 15 minutes to enhance security. You can adjust the expiration time in the `.env` file settings: TOKEN_EXPIRATION_TIME in minutes.
JWT tokens are configured to expire after 15 minutes to enhance security. You can adjust the expiration time in the `.env` file settings: TOKEN_EXPIRATION_TIME in minutes.

#### Rate Limiting:
To protect against abuse, API requests are throttled. Each user is allowed a maximum of 10 requests per minute. This helps ensure fair usage and prevents server overload.
you can adjust the rate limit in the `.env` file settings: TOKEN_EXPIRATION_TIME

#### Admin Interface:
The admin interface is accessible at http://localhost/admin. It provides a simple dashboard for managing user accounts and viewing transaction history:
- Create, Read, Update, Delete (CRUD) operations on user accounts.
- Tracking of all transactions across users.

#### Authentication:
Users can log in using their debit card number and a 4-digit PIN. Use Laravel Passport for token-based authentication.

#### Accessing the Database:
You can access the database using the following command:
```bash
./vendor/bin/sail mysql
```
default database 'laravel', and testing database 'testing'


