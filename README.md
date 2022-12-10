## BestBuy

This is a system designed for versatile e-commerce support, allowing the listing and matching of arbitrary types of products.

## Installation

Initial installation can be done as for any Laravel project. 

1. Clone the repository. 
2. Run `composer install` inside the repository to resolve php dependiences.
3. Run `cp .env.example .env` to copy the example environment setup.
4. Run `php artisan key:generate` to generate an encryption key for your environment.

The database must be imported directly into your database management system. Use either the DATABASE.sql or DATABASE_FILLED.sql to include schema alone or both schema and data.
Remember to configure your .env to correctly refer to your database.
All passwords in the filled table are the same as their usernames.
Data generation and needmap creation require an account with the username admin. Data generation also requires at least one vendor account.

Refer to report.pdf for a more thorough explanation of the system.

