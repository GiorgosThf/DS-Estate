# Use the official PHP image as a base
FROM php:8.1-cli

WORKDIR /app


# Copy project to /tmp inside the container
COPY . /app

# Install dependencies and Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

# Install PHP dependencies
RUN composer install

# Expose port 5000 and start the PHP built-in server
EXPOSE 5000
CMD ["php", "-S", "0.0.0.0:5000"]
