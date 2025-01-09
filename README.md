<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API Blog with Authentication (Laravel)

This project is a simple API for managing blog posts and comments with user authentication (registration, login, logout). The API follows RESTful principles and is built using Laravel 11.x, leveraging modern design patterns for scalability and maintainability.

## Setup Instructions

### 1. Clone the Repository

Start by cloning the repository to your local machine.

```bash
git clone https://github.com/hafizhzikry24/SPA-BE_Alturian_test.git
cd SPA-BE_Alturian_test
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Set Up Environment Variables

```bash
cp .env.example .env
```

Edit the .env file and update the following variables:

```bash
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

### 4. App Key
Generate the application key by running the following command:

```bash
php artisan key:generate
```

### 5. Migrate the Database
Run the Laravel migration command to create the necessary database tables for the application.

```bash
php artisan migrate
```

### 6. Run the Application
Now, you can start the Laravel development server to run the application:

```bash
php artisan serve
```


## Architectural Decisions
1. Separation of Concerns with Repository Pattern
We adopted the Repository Pattern to keep business logic separate from the controller. This allows us to easily extend the application in the future without bloating the controllers. All database operations are handled by the repository layer, which makes the codebase more maintainable.
2. Use of Traits for Reusable Response Logic
The ResponseMessageTrait is used to standardize the structure of API responses across the application. This ensures that all responses, whether successful or erroneous, follow a consistent format, making it easier to handle on the frontend.
3. JWT for API Authentication
We are using Laravel Sanctum for API authentication, which is a simple and lightweight solution for token-based authentication. This is particularly suited for SPAs (Single Page Applications) and mobile applications.
4. Paginated Responses
Pagination has been implemented for posts and comments to avoid performance issues when dealing with large datasets. The API returns paginated data along with metadata (current page, total pages, etc.) to help the frontend display the data accordingly.
5. Input Validation
Input validation is done using Laravel's Validator class, ensuring that all data received via API requests is well-formed and meets the necessary constraints. This reduces the risk of errors and malicious data being saved into the database.
6. Error Handling
Error handling is done in a centralized manner using a custom trait (ResponseMessageTrait). This makes the code cleaner and ensures that error messages are returned consistently in the same format.
7. Security Considerations
Passwords are hashed using bcrypt (Laravel's default) before storing them in the database.
API endpoints that require authentication are protected by token validation (via Laravel Sanctum).
Input sanitization and validation are implemented to prevent SQL injection and other forms of malicious input.

