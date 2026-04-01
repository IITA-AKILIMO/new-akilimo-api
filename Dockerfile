# -----------------------------------
# Build stage for frontend assets
# -----------------------------------
FROM node:20-alpine AS frontend-build

WORKDIR /app

# Install pnpm globally
RUN npm install -g pnpm

# Copy only the package.json and pnpm-lock.yaml to leverage Docker cache
COPY package.json pnpm-lock.yaml ./

# Install dependencies
RUN pnpm install --frozen-lockfile

# Copy the rest of the frontend source
COPY . .

# Build the application
RUN pnpm build

# -----------------------------------
# Final production stage
# -----------------------------------
FROM masgeek/laravel-service:php-8.4

# Set working directory
WORKDIR /var/www/html/fuelrod

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Copy frontend build
COPY --from=frontend-build --chown=www-data:www-data /app/public/build ./public/build

# Set correct ownership and permissions
RUN chown -R www-data:www-data /var/www/html/fuelrod \
    && chmod -R 775 /var/www/html/fuelrod/storage /var/www/html/fuelrod/bootstrap/cache

# Switch to www-data user for runtime
#USER www-data

# Start all services using Supervisor
CMD ["/usr/local/bin/start.sh"]
