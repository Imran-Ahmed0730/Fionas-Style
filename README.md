# Fionas-Style

## About Fionas Style

Fionas Style is an e-commerce platform built with Laravel. It features a robust admin panel, POS system integration, and a customer-facing storefront.

## Key Features

- **Admin Dashboard**: Comprehensive overview of sales, orders, and customer statistics.
- **Product Management**: Create, edit, and manage products with variants and inventory tracking.
- **Point of Sale (POS)**: Integrated POS system for physical store sales.
- **Customer Management**: track customer orders and details.
- **Reports**: Generate sales reports, balance sheets, and cashbooks.
- **Settings**: Configurable site settings including currency, logos, and social media links.

## Tech Stack

- **Backend**: Laravel Framework
- **Frontend**: Blade Templating, Bootstrap, jQuery
- **Database**: MySQL

## Installation

1. Clone the repository.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and configure your database settings working directory.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate --seed`.
6. Serve the application with `php artisan serve`.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).