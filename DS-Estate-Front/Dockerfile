# Use the official PHP image as a base
FROM php:8.1-cli

WORKDIR /app


# Copy project to /tmp inside the container
COPY ./src /app

# Expose port 8000 and start the PHP built-in server
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000"]
