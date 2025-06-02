# Use an official PHP image with Apache as the base image.
FROM thecodingmachine/php:8.2.28-v4-apache
USER root

# Install system dependencies. We want MySQL CLI so we can communicate with container on socket via Docker from outside.
RUN apt-get update && \
    apt-get install -y --no-install-recommends default-mysql-client dos2unix vim-tiny ssmtp

# Configure sSMTP to send to Mailhog
RUN echo mailhub=mailhog:1025 >> /etc/ssmtp/ssmtp.conf

# Download and install Composer
RUN curl -Lsf https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure xDebug
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.coverage_enable=1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.coverage_output_dir=/var/www/html/coverage/" >> /usr/local/etc/php/php.ini && \
    echo "memory_limit=512M" >> /usr/local/etc/php/php.ini && \
    echo "auto_prepend_file	=/var/www/html/save-code-coverage.php" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/php.ini

# Make the default shell work better
RUN ln -sf /bin/bash /bin/sh

# Set up the entrypoint
COPY ./docker-entrypoint.sh /docker-entrypoint.sh
RUN dos2unix /docker-entrypoint.sh # Required when running on Windows with autocrlf enabled
RUN chmod +x /docker-entrypoint.sh

# Expose port 80 for Apache.
EXPOSE 80

# Start Apache web server.
CMD ["/docker-entrypoint.sh"]