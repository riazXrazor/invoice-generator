# Invoice Generator (GST Billing Application)

A professional GST billing application built with Laravel and Filament. This application automates invoice generation, PDF creation, and simplifies the printing process with dynamic, database-driven configurations.

## Features

- **Automated GST Calculations:** Conditional tax logic (IGST vs. CGST/SGST) based on state codes.
- **Dynamic PDF Generation:** Configure and switch between different invoice templates.
- **Client & Company Management:** Centralized administration for clients, company details, and products.
- **Containerized Deployment:** Ready to be deployed via Docker.

## Tech Stack

- **Framework:** Laravel 12
- **Admin Panel:** Filament v3
- **Deployment:** Docker
- **Database:** PostgreSQL / MySQL (configurable)

## Getting Started

To run the project locally, ensure you have PHP, Composer, and Node.js installed, or use Docker/Laravel Sail.

### Standard Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/riazXrazor/invoice-generator.git
   cd invoice-generator
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install frontend dependencies and build:
   ```bash
   npm install && npm run build
   ```
4. Copy the `.env.example` to `.env` and configure your database settings:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Run database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
6. Start the local development server:
   ```bash
   php artisan serve
   ```

### Docker Deployment

This application includes a `Dockerfile` and `docker-compose.yml` for containerized deployment.

1. Create your `.env` file based on `.env.example` and set the appropriate environment variables.
2. Build and start the containers using Docker Compose:
   ```bash
   docker-compose up -d --build
   ```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
