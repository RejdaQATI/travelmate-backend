FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y libzip-dev unzip git && \
    docker-php-ext-install pdo pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY / /var/www/html

RUN chmod +x /var/www/html/artisan

RUN chown -R www-data:www-data /var/www/html

RUN composer install --verbose

RUN php artisan storage:link

RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 1000

CMD php artisan migrate --force && \
    php artisan config:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-1000}