# Use an official PHP image with Apache as the base image.
FROM php:8.2-apache

# Install system dependencies. We want MySQL CLI so we can communicate with container on socket via Docker from outside.
RUN apt-get update && \
    apt-get install -y --no-install-recommends default-mysql-client dos2unix vim-tiny

# Use this utility to install php extensions, since docker-php-ext-install doesn't support them all.
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# MySQLi is required for REDCap
# ImageMagick is required for some RSVC features (e.g. C.3.24.1700).  We use this tool to install it because of the "no installation candidate" error mentioned here: https://hub.docker.com/_/php
# Zip extension is currently used to download composer packages during cloud builds (faster than using git), but we might also use it in some actual tests down the road.
RUN install-php-extensions mysqli imagick zip

# Configure Mailhog
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.19.4.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go install github.com/mailhog/mhsendmail@v0.2.0
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail

# Copy a php.ini and set the Mailhog path
COPY php.ini /usr/local/etc/php
RUN echo "sendmail_path = '/usr/bin/mhsendmail --smtp-addr=mailhog:1025'" >> /usr/local/etc/php/php.ini

# Set up the entrypoint
COPY ./docker-entrypoint.sh /docker-entrypoint.sh
RUN dos2unix /docker-entrypoint.sh # Required when running on Windows with autocrlf enabled
RUN chmod +x /docker-entrypoint.sh

# Expose port 80 for Apache.
EXPOSE 80

# Start Apache web server.
CMD ["/docker-entrypoint.sh"]