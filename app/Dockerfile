# Use an official PHP image with Apache as the base image.
FROM php:8.2-apache

# Install system dependencies. We want MySQL CLI so we can communicate with container on socket via Docker from outside.
RUN apt-get update && \
    apt-get install -y git default-mysql-client dos2unix

# Install PHP extensions.  This is key because REDCap uses MySQLi
RUN docker-php-ext-install -j$(nproc) mysqli

# Install xDebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Configure Mailhog
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.19.4.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go install github.com/mailhog/mhsendmail@v0.2.0
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail

# Copy a php.ini and set the Mailhog path
COPY php.ini /usr/local/etc/php
RUN echo "sendmail_path = '/usr/bin/mhsendmail --smtp-addr=mailhog:1025'" >> /usr/local/etc/php/php.ini

# Download and install Composer
RUN curl -Lsf https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configure xDebug
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.coverage_enable=1" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.coverage_output_dir=/var/www/html/coverage/" >> /usr/local/etc/php/php.ini && \
    echo "memory_limit=512M" >> /usr/local/etc/php/php.ini && \
    echo "auto_prepend_file	=/var/www/html/save-code-coverage.php" >> /usr/local/etc/php/php.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/php.ini

# Set up the entrypoint
COPY ./docker-entrypoint.sh /docker-entrypoint.sh
RUN dos2unix /docker-entrypoint.sh # Required when running on Windows with autocrlf enabled
RUN chmod +x /docker-entrypoint.sh

# Expose port 80 for Apache.
EXPOSE 80

# Start Apache web server.
CMD ["/docker-entrypoint.sh"]