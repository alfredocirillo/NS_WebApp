FROM php:8.1-apache

# Install Curl e iproute2 (necessario per il comando 'ip')
RUN apt-get update && apt-get install -y openssh-client iproute2 && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your web files
COPY . /var/www/html/


EXPOSE 8080
