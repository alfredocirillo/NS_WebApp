FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your web files
COPY . /var/www/html/

EXPOSE 8080
