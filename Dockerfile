
FROM php:8.1-apache


COPY . /var/www/html/


RUN chown -R www-data:www-data /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

EXPOSE 80


CMD ["apache2-foreground"]
