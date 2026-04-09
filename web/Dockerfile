FROM php:8.1-apache

# Enable Apache mod_rewrite and SSL
RUN a2enmod rewrite ssl

# Install MySQL extension, curl and iproute2
RUN apt-get update && apt-get install -y openssh-client iproute2 && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Generate self-signed certificate for HTTPS
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj '/CN=10.20.0.10'

# Update default-ssl.conf to use our certificate
RUN sed -i 's|SSLCertificateFile.*|SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt|g' /etc/apache2/sites-available/default-ssl.conf && \
    sed -i 's|SSLCertificateKeyFile.*|SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key|g' /etc/apache2/sites-available/default-ssl.conf

# Enable default-ssl site
RUN a2ensite default-ssl

# Copy your web files
COPY . /var/www/html/

# CRITICAL: Change ownership IMMEDIATELY after copy
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/ && \
    chmod -R 777 /var/www/html/uploads

# Verify it worked
RUN ls -la /var/www/html/ | grep uploads

EXPOSE 80 443
