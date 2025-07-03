FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    netcat-openbsd zip unzip git curl libpq-dev libonig-dev libzip-dev libxml2-dev \
    libicu-dev libjpeg-dev libpng-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath intl exif gd \
    && docker-php-ext-enable intl exif gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Configure PHP settings
RUN echo "upload_max_filesize=512M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini

# Configure git
RUN git config --global --add safe.directory /var/www/html

# Copy application code
COPY . .

# Set correct permissions for Laravel storage and cache folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev --prefer-dist
# RUN php artisan vendor:publish --force --tag=livewire:assets
# Run Artisan commands
RUN php artisan filament:assets
RUN php artisan storage:link
RUN php artisan route:clear
RUN php artisan config:clear
RUN php artisan config:cache
RUN php artisan view:clear
RUN php artisan cache:clear
RUN php artisan key:generate
RUN php artisan migrate --force

EXPOSE 8000

CMD ["php-fpm"]