# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . /app

# Install any needed dependencies
RUN apt-get update && \
    apt-get install -y git curl libmcrypt-dev libicu-dev zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-install pdo pdo_mysql mysqli gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dotenv package
RUN composer require vlucas/phpdotenv

# Expose port 80 for Apache
EXPOSE 80

# Start Apache service
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
