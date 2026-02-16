FROM php:8.4-fpm-alpine

# Arguments
ARG WWWGROUP
ARG WWWUSER

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    zip \
    unzip \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        exif \
        opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create system user for Laravel application
RUN addgroup -g ${WWWGROUP} sail \
    && adduser -u ${WWWUSER} -G sail -D sail

# Set permissions
RUN chown -R sail:sail /var/www/html \
    && chmod -R ug+rwx /var/www/html

# Expose port (PHP-FPM default)
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]