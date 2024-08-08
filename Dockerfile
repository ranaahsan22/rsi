# Use the official PHP image with Apache
FROM php:7.4-apache

# Set working directory
WORKDIR /var/www/html

# Copy application source code to the container
COPY . /var/www/html

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions required by CodeIgniter
RUN docker-php-ext-install pdo pdo_mysql

# Set up Apache virtual host
COPY ./.htaccess /var/www/html/.htaccess

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache service
CMD ["apache2-foreground"]
