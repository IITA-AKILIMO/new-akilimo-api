# Build stage for frontend assets
# Use Node.js 20 Alpine image with Yarn pre-installed
FROM node:20-alpine AS frontend-build

# Set the working directory inside the container
WORKDIR /app

# Copy only the package.json and yarn.lock to leverage Docker cache for dependencies
COPY package.json yarn.lock ./

# Install dependencies
RUN yarn install --frozen-lockfile

# Copy the rest of the application source code
COPY . .

# Build the application
RUN yarn build

# Final production stage
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html/akilimo

# Install system dependencies
RUN apk add --no-cache \
    linux-headers \
    supervisor \
    bash \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    postgresql-dev \
    oniguruma-dev \
    $PHPIZE_DEPS

# Configure and install libzip
RUN apk add --no-cache --virtual .build-deps \
    libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install PHP extensions required for Laravel
RUN docker-php-ext-install \
    pdo_pgsql \
    pdo_mysql \
    bcmath \
    zip \
    gd \
    opcache \
    pcntl \
    intl \
    mbstring \
    xml \
    && pecl install redis \
    && docker-php-ext-enable redis opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Copy frontend build
COPY --from=frontend-build --chown=www-data:www-data /app/public/build ./public/build

# Metadata labels
LABEL maintainer="Sammy Barasa <sammy@munywele.co.ke>"
LABEL description="A production-ready Laravel application served by Nginx and PHP-FPM with process management handled by Supervisor."


# Create supervisor directory and PHP-FPM directory
RUN mkdir -p /etc/supervisor.d/ \
    && mkdir -p /var/run/php-fpm/ \
    && mkdir -p /var/log/php-fpm/

# Create necessary directories and set ownership
RUN mkdir -p /var/log/supervisor  \
    && chown -R www-data:www-data /var/log/supervisor  \
    && chown -R www-data:www-data  /var/run

# Set PHP-FPM configuration directory permissions
RUN chown -R www-data:www-data /var/run/php-fpm \
    && chown -R www-data:www-data /var/log/php-fpm

# Ensure all storage and cache directories are accessible to www-data
RUN mkdir -p /var/www/html/akilimo/storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data /var/www/html/akilimo/storage \
    && chmod -R 775 /var/www/html/akilimo/storage \
    && chown -R www-data:www-data /var/www/html/akilimo/bootstrap/cache \
    && chmod -R 775 /var/www/html/akilimo/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/akilimo/storage/logs \
    && chmod -R 775 /var/www/html/akilimo/storage/logs

# Create Nginx temporary directories and set permissions
RUN mkdir -p /var/tmp/nginx/{client_body,fastcgi,proxy,scgi}

RUN chown -R www-data:www-data /var/tmp/nginx

# Copy custom PHP configuration
COPY ./docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Configure Nginx
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/supervisor/start.sh /usr/local/bin/start.sh
COPY docker/supervisor/laravel-scheduler.sh /etc/scheduler/laravel-scheduler.sh

RUN chmod +x /usr/local/bin/start.sh

RUN chmod +x /etc/scheduler/laravel-scheduler.sh

RUN   chown -R www-data:www-data /var/www/html/akilimo \
        && chmod -R 775 /var/www/html/akilimo


EXPOSE 80


# Switch to www-data user for running the application
#USER www-data

# Start services
CMD ["/usr/local/bin/start.sh"]
