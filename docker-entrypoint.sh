#!/bin/bash
set -e

echo "Starting Docker Entrypoint..."

# If .env does not exist, copy from example
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    # Generate app key if needed
    php artisan key:generate --force
fi

# Configure Laravel to use the external decoupled database directory
DB_PATH="/var/www/html/database_data/database.sqlite"
# Safely replace DB_DATABASE without altering the bind-mount inode
sed "s|^DB_DATABASE=.*|DB_DATABASE=$DB_PATH|" .env > /tmp/.env.tmp && cat /tmp/.env.tmp > .env && rm /tmp/.env.tmp

# Ensure the decoupled database directory exists
mkdir -p /var/www/html/database_data

# Create sqlite database file if it doesn't exist
if [ ! -f "$DB_PATH" ]; then
    echo "Creating SQLite database file at $DB_PATH..."
    
    # If there's an existing database in the local folder, copy it over
    if [ -f database/database.sqlite ]; then
        echo "Found existing local database, migrating it to external volume..."
        cp database/database.sqlite "$DB_PATH"
    else
        touch "$DB_PATH"
    fi
fi

# Set proper permissions for database
chmod 666 "$DB_PATH"
chown -R www-data:www-data /var/www/html/database_data

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Optimize application for production 
if [ "$APP_ENV" = "production" ] || [ "$APP_ENV" = "prod" ] || [ "$APP_ENV" = "homelab" ]; then
    echo "Caching configuration and routes..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
fi

echo "Starting Application..."
# Execute the CMD instruction
exec "$@"
