FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install MySQL extension, curl e iproute2
RUN apt-get update && apt-get install -y openssh-client iproute2 && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your web files
COPY . /var/www/html/

# CRITICAL: Change ownership IMMEDIATELY after copy
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/ && \
    chmod -R 777 /var/www/html/uploads

# Verify it worked
RUN ls -la /var/www/html/ | grep uploads

EXPOSE 80

