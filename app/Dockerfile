# Use an official PHP image with Apache as the base image.
FROM thecodingmachine/php:8.2.28-v4-apache
USER root

# Install system dependencies. We want MySQL CLI so we can communicate with container on socket via Docker from outside.
RUN apt-get update && \
    apt-get install -y --no-install-recommends default-mysql-client dos2unix vim-tiny ssmtp

# Configure sSMTP to send to Mailhog
RUN echo mailhub=mailhog:1025 >> /etc/ssmtp/ssmtp.conf

# php.ini changes
ARG PHP_INI_PATH="/usr/lib/php/8.2/php.ini-production"
RUN echo "post_max_size = 100M" >> ${PHP_INI_PATH} && \
    echo "upload_max_filesize = 100M" >> ${PHP_INI_PATH}

# Make vim-tiny behave more like vim
RUN echo -e "set backspace=indent,eol,start\nset nocompatible" >> /root/.vimrc

# Make the default shell work better
RUN ln -sf /bin/bash /bin/sh

# Set up the entrypoint
COPY ./docker-entrypoint.sh /docker-entrypoint.sh
COPY ./azure /var/www/html/azure
RUN dos2unix /docker-entrypoint.sh # Required when running on Windows with autocrlf enabled
RUN chmod +x /docker-entrypoint.sh

# Expose port 80 for Apache.
EXPOSE 80

# Start Apache web server.
CMD ["/docker-entrypoint.sh"]