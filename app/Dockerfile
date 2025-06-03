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

# php.ini changes - We overwrite generated_conf.ini because all it contains are some xdebug lines we want to replace anyway.
ARG PHP_INI_PATH="/etc/php/8.2/mods-available/generated_conf.ini"
RUN echo "post_max_size = 100M" >> ${PHP_INI_PATH} && \
    echo "upload_max_filesize = 100M" >> ${PHP_INI_PATH} && \
    echo "xdebug.mode=coverage" >> ${PHP_INI_PATH} && \
    echo "xdebug.coverage_enable=1" >> ${PHP_INI_PATH} && \
    echo "xdebug.coverage_output_dir=/var/www/html/coverage/" >> ${PHP_INI_PATH} && \
    echo "memory_limit=512M" >> ${PHP_INI_PATH} && \
    echo "auto_prepend_file	=/var/www/html/save-code-coverage.php" >> ${PHP_INI_PATH} && \
    echo "xdebug.client_host=host.docker.internal" >> ${PHP_INI_PATH}

# Make vim-tiny behave more like vim
RUN echo -e "set backspace=indent,eol,start\nset nocompatible" >> /root/.vimrc

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